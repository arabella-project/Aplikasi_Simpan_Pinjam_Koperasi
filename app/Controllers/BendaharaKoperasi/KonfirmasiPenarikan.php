<?php

namespace App\Controllers\BendaharaKoperasi;

use App\Controllers\BaseController;

class KonfirmasiPenarikan extends BaseController
{
    protected $db;

    public function __construct() {
        $this->db = \Config\Database::connect();
    }

    /**
     * MODUL 1: LEMBAR MEJA KERJA UTAMA BENDAHARA
     */
    public function index()
    {
        // Menampilkan pending, disetujui (di meja ketua), dan ditransfer (siap konfirmasi kirim uang)
        $list_antrean = $this->db->table('pengajuan_penarikan')
                                 ->select('pengajuan_penarikan.*, anggota.nama_anggota, anggota.email')
                                 ->join('anggota', 'anggota.id_anggota = pengajuan_penarikan.id_anggota', 'left')
                                 ->whereIn('status_penarikan', ['pending', 'disetujui', 'ditransfer'])
                                 ->orderBy('tgl_pengajuan', 'ASC')
                                 ->get()->getResultArray();

        $stats = [
            'total_pending'   => $this->db->table('pengajuan_penarikan')->where('status_penarikan', 'pending')->countAllResults(),
            'total_disetujui' => $this->db->table('pengajuan_penarikan')->where('status_penarikan', 'disetujui')->countAllResults(),
            'total_transfer'  => $this->db->table('pengajuan_penarikan')->where('status_penarikan', 'ditransfer')->countAllResults(),
        ];

        return view('bendahara_koperasi/v_konfirmasi_penarikan', [
            'title'        => 'Konfirmasi Penarikan Dana Anggota',
            'list_antrean' => $list_antrean,
            'stats'        => $stats
        ]);
    }

    /**
     * MODUL 2: MESIN EKSEKUSI PROSES BERJENJANG BENDAHARA
     */
    public function proses($id = null, $aksi = null)
    {
        if (!$id || !$aksi) {
            return redirect()->to(base_url('bendahara/konfirmasi_penarikan'))->with('error', 'Parameter data tidak valid!');
        }

        $penarikan = $this->db->table('pengajuan_penarikan')->where('id_penarikan', $id)->get()->getRowArray();
        if (!$penarikan) {
            return redirect()->to(base_url('bendahara/konfirmasi_penarikan'))->with('error', 'Data pengajuan tidak ditemukan!');
        }

        /**
         * TAHAP 1: BENDAHARA KIRIM BERKAS KE MEJA KETUA
         */
        if ($aksi == 'setuju') {
            // Mengubah status menjadi 'disetujui' (sesuai ENUM database kamu)
            $this->db->table('pengajuan_penarikan')->where('id_penarikan', $id)->update(['status_penarikan' => 'disetujui']);
            
            $this->kirimEmailNotifikasi(
                'ketua@koperasi-bps.com',
                'Pemberitahuan Pimpinan: Otorisasi Permohonan Penarikan Dana Baru',
                'Halo Pimpinan, terdapat permohonan penarikan dana simpanan baru yang telah diverifikasi Bendahara Koperasi dan menunggu otorisasi final dari Anda.'
            );

            return redirect()->to(base_url('bendahara/konfirmasi_penarikan'))->with('success', 'Berkas berhasil diteruskan ke Verifikator!');
        
        /**
         * TAHAP 2: EKSEKUSI AKHIR BENDAHARA POTONG SALDO REAL-TIME
         */
        } elseif ($aksi == 'transfer') {
            $id_anggota = $penarikan['id_anggota'];
            $nominal_tarik = (float)$penarikan['jumlah_ditarik'];

            $this->db->transStart();
            $saldo_riil = $this->db->table('simpanan')->where('id_anggota', $id_anggota)->get()->getRowArray();

            if ($saldo_riil) {
                $sukarela = (float)($saldo_riil['simpanan_sukarela'] ?? 0);
                $wajib    = (float)($saldo_riil['simpanan_wajib'] ?? 0);
                $pokok    = (float)($saldo_riil['simpanan_pokok'] ?? 0);

                // Algoritma Pemotongan Berjenjang Simpanan
                if ($sukarela >= $nominal_tarik) {
                    $sukarela -= $nominal_tarik;
                    $nominal_tarik = 0;
                } else {
                    $nominal_tarik -= $sukarela;
                    $sukarela = 0;

                    if ($wajib >= $nominal_tarik) {
                        $wajib -= $nominal_tarik;
                        $nominal_tarik = 0;
                    } else {
                        $nominal_tarik -= $wajib;
                        $wajib = 0;
                        $pokok = max(0, $pokok - $nominal_tarik);
                        $nominal_tarik = 0;
                    }
                }

                $this->db->table('simpanan')
                         ->where('id_anggota', $id_anggota)
                         ->update([
                             'simpanan_pokok'    => $pokok,
                             'simpanan_wajib'    => $wajib,
                             'simpanan_sukarela' => $sukarela
                         ]);
            } else {
                $this->db->transRollback();
                return redirect()->to(base_url('bendahara/konfirmasi_penarikan'))->with('error', 'Gagal! Data master simpanan tidak ditemukan.');
            }

            // KARENA LUNAS TRANSFER, BERKAS SELESAI DAN KELUAR DARI ANTREAN KERJA AKTIF
            $this->db->table('pengajuan_penarikan')->where('id_penarikan', $id)->delete();
            $this->db->transComplete();

            return redirect()->to(base_url('bendahara/konfirmasi_penarikan'))->with('success', 'Dana sukses dicairkan dan saldo komponen simpanan terpotong real-time!');
        
        /**
         * TAHAP 3: BENDAHARA TOLAK BERKAS
         */
        } elseif ($aksi == 'tolak') {
            $alasan = $this->request->getPost('alasan_penolakan') ?? 'Syarat tidak memenuhi ketentuan administrasi.';
            $this->db->table('pengajuan_penarikan')->where('id_penarikan', $id)->update([
                'status_penarikan' => 'ditolak',
                'alasan_penolakan' => $alasan
            ]);
            return redirect()->to(base_url('bendahara/konfirmasi_penarikan'))->with('success', 'Pengajuan penarikan dana berhasil ditolak.');
        }

        return redirect()->to(base_url('bendahara/konfirmasi_penarikan'));
    }

    private function kirimEmailNotifikasi($to, $subject, $message) {
        $email = \Config\Services::email();
        $email->setTo($to);
        $email->setSubject($subject);
        $email->setMessage($message);
        @$email->send();
    }
}