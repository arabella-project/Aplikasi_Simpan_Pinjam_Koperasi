<?php

namespace App\Controllers\AnggotaKoperasi;

use App\Controllers\BaseController;

class Penarikan extends BaseController
{
    protected $db;

    public function __construct() {
        $this->db = \Config\Database::connect();
    }

    /**
     * Halaman Utama Form Penarikan Simpanan Anggota (Real-time Synchronized)
     */
    public function index()
    {
        $id_anggota = session()->get('id_anggota');

        // =========================================================================
        // IMPLEMENTASI FIX: Tarik data baris fresh fisik dari database simpanan
        // =========================================================================
        $simpanan_riil = $this->db->table('simpanan')
                                  ->where('id_anggota', $id_anggota)
                                  ->get()->getRowArray();

        // Ambil nominal dari masing-masing kolom horizontal di database
        $pokok    = (float)($simpanan_riil['simpanan_pokok'] ?? 0);
        $wajib    = (float)($simpanan_riil['simpanan_wajib'] ?? 0);
        $sukarela = (float)($simpanan_riil['simpanan_sukarela'] ?? 0);

        // Total akumulasi saldo berjalan riil saat ini setelah dikurangi transaksi sukses
        $total_simpanan_global = $pokok + $wajib + $sukarela;

        // 2. ANALISIS KREDIT MACET (Aturan Proteksi Sanksi Kunci Jaminan 30%)
        $is_macet = false;
        $dana_ditahan = 0;

        $pinjaman_aktif = $this->db->table('pinjaman')
                                   ->where('id_anggota', $id_anggota)
                                   ->where('status_pinjaman', 'aktif')
                                   ->orderBy('id_pinjaman', 'DESC')
                                   ->get()->getRowArray();

        if ($pinjaman_aktif) {
            $pembayaran = $this->db->table('pembayaran_pinjaman')
                                   ->where('id_pinjaman', $pinjaman_aktif['id_pinjaman'])
                                   ->orderBy('angsuran_ke', 'ASC')
                                   ->get()->getResultArray();

            if (count($pembayaran) > 0) {
                $tgl_awal  = new \DateTime($pembayaran[0]['tgl_bayar']);
                $tgl_akhir = new \DateTime(date('Y-m-d'));
                $interval  = $tgl_awal->diff($tgl_akhir);
                $bulan_berjalan_kalender = ($interval->y * 12) + $interval->m + 1;
                $tunggakan_riil = $bulan_berjalan_kalender - count($pembayaran);
                
                // Jika menunggak lebih dari 2 bulan, dikategorikan Kredit Macet
                if ($tunggakan_riil > 2) {
                    $is_macet = true;
                    // Pembatasan jaminan: 30% dari total seluruh simpanan global dikunci
                    $dana_ditahan = $total_simpanan_global * 0.30;
                }
            }
        }

        // Saldo bersih akhir yang benar-benar diizinkan untuk ditarik anggota saat ini
        $saldo_aktif_bisa_ditarik = $total_simpanan_global - $dana_ditahan;
        if ($saldo_aktif_bisa_ditarik < 0) { $saldo_aktif_bisa_ditarik = 0; }

        // 3. Ambil Riwayat Pengajuan Penarikan Anggota untuk Log Tabel View
        $histori_penarikan = $this->db->table('pengajuan_penarikan')
                                      ->where('id_anggota', $id_anggota)
                                      ->orderBy('id_penarikan', 'DESC')
                                      ->get()->getResultArray();

        $data = [
            'title'          => 'Tarik Dana Simpanan Koperasi',
            'saldo_sukarela' => $saldo_aktif_bisa_ditarik,
            'is_macet'       => $is_macet,
            'dana_ditahan'   => $dana_ditahan,
            'total_simpanan' => $total_simpanan_global,
            'histori'        => $histori_penarikan
        ];

        return view('anggota/v_penarikan_dana', $data);
    }

    /**
     * Proses Simpan dengan Validasi Keras Batas Saldo Jaminan Macet (Real-time Secure)
     */
    public function simpan()
    {
        $id_anggota = session()->get('id_anggota');
        $jumlah_ditarik = (float)$this->request->getPost('jumlah_ditarik');
        $bank_tujuan = $this->request->getPost('bank_tujuan');
        $no_rekening = $this->request->getPost('no_rekening');

        if ($jumlah_ditarik <= 0) {
            return redirect()->back()->with('error', 'Nominal penarikan dana harus di atas Rp 0!');
        }

        // Ambil data baris fresh fisik dari database simpanan untuk penguncian backend
        $simpanan_riil = $this->db->table('simpanan')
                                  ->where('id_anggota', $id_anggota)
                                  ->get()->getRowArray();

        if (!$simpanan_riil) {
            return redirect()->back()->with('error', 'Data master simpanan akun Anda belum terdaftar!');
        }

        $pokok    = (float)($simpanan_riil['simpanan_pokok'] ?? 0);
        $wajib    = (float)($simpanan_riil['simpanan_wajib'] ?? 0);
        $sukarela = (float)($simpanan_riil['simpanan_sukarela'] ?? 0);

        $total_simpanan_global = $pokok + $wajib + $sukarela;
        $dana_ditahan = 0;

        // Validasi Perlindungan Sanksi Kredit Macet Koperasi
        $pinjaman_aktif = $this->db->table('pinjaman')
                                   ->where('id_anggota', $id_anggota)
                                   ->where('status_pinjaman', 'aktif')
                                   ->get()->getRowArray();
                                   
        if ($pinjaman_aktif) {
            $pembayaran = $this->db->table('pembayaran_pinjaman')
                                   ->where('id_pinjaman', $pinjaman_aktif['id_pinjaman'])
                                   ->get()->getResultArray();
                                   
            if (count($pembayaran) > 0) {
                $tgl_awal = new \DateTime($pembayaran[0]['tgl_bayar']);
                $interval = $tgl_awal->diff(new \DateTime(date('Y-m-d')));
                $tunggakan = (($interval->y * 12) + $interval->m + 1) - count($pembayaran);
                
                if ($tunggakan > 2) { 
                    $dana_ditahan = $total_simpanan_global * 0.30; 
                }
            }
        }

        $saldo_tersedia = $total_simpanan_global - $dana_ditahan;

        // Validasi Keras: Mencegah bypass nominal manipulasi input HTML
        if ($jumlah_ditarik > $saldo_tersedia) {
            return redirect()->back()->with('error', 'Gagal mengajukan! Nominal penarikan melebihi batas aman dari sisa tabungan riil Anda.');
        }

        // Masukkan data rekening & bank tujuan transfer ke antrean bendahara
        $this->db->table('pengajuan_penarikan')->insert([
            'id_anggota'       => $id_anggota,
            'jenis_simpanan'   => 'sukarela',
            'jumlah_ditarik'   => $jumlah_ditarik,
            'bank_tujuan'      => $bank_tujuan,
            'no_rekening'      => $no_rekening,
            'tgl_pengajuan'    => date('Y-m-d H:i:s'),
            'status_penarikan' => 'pending'
        ]);

        return redirect()->to(base_url('anggota/penarikan'))->with('success', 'Pengajuan penarikan dana berhasil dikirim! Menunggu konfirmasi transfer bendahara.');
    }
}