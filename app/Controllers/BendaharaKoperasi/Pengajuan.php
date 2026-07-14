<?php

namespace App\Controllers\BendaharaKoperasi;

use App\Controllers\BaseController;
use App\Models\PengajuanModel;

class Pengajuan extends BaseController
{
    protected $pengajuanM;
    protected $db;

    public function __construct()
    {
        $this->pengajuanM = new PengajuanModel();
        $this->db         = \Config\Database::connect();
    }

    /**
     * ===================================================================================
     * TAHAP 1: FORM UTAMA ANTREAN MASUK BERKAS 'PENDING' UNTUK DIREVIEW BENDAHARA
     * ===================================================================================
     */
    public function dashboard_antrean()
    {
        // Mengambil berkas masuk baru milik anggota yang berstatus 'pending'
        $antrean_pending = $this->db->table('pengajuan_pinjaman')
                                    ->select('pengajuan_pinjaman.*, anggota.nama_anggota')
                                    ->join('anggota', 'anggota.id_anggota = pengajuan_pinjaman.id_anggota', 'left')
                                    ->where('pengajuan_pinjaman.status_pengajuan', 'pending')
                                    ->orderBy('pengajuan_pinjaman.id_pengajuan', 'ASC')
                                    ->get()->getResultArray();

        return view('bendahara_koperasi/v_antrean_pending', [
            'title'   => 'Antrean Berkas Masuk (Menunggu Verifikasi Administrasi)',
            'antrean' => $antrean_pending
        ]);
    }

    /**
     * TAHAP 1.2: AKSI SIMPAN REVIEW BENDAHARA (Status 'pending' naik menjadi 'proses')
     */
    public function simpan_review() 
    {
        $id_pengajuan = $this->request->getPost('id_pengajuan');
        $skor_c1 = $this->request->getPost('skor_c1'); 
        $skor_c2 = $this->request->getPost('skor_c2'); 

        $this->db->table('pengajuan_pinjaman')
                 ->where('id_pengajuan', $id_pengajuan)
                 ->update([
                     'c1_persetujuan_pasangan' => $skor_c1,
                     'c2_rhb'                  => $skor_c2, 
                     'status_pengajuan'        => 'proses' // Berkas bergeser ke area hitung SAW 
                 ]);

        // SINKRONISASI EMAIL NOTIFIKASI KETUA: Cek kapasitas tumpukan berkas di meja kerja Ketua Koperasi
        $antrean_proses_ketua = $this->db->table('pengajuan_pinjaman')
                                          ->where('status_pengajuan', 'proses')
                                          ->countAllResults();

        // Jika kuota antrean terkumpul >= 5, kirim email alert ke Ketua Koperasi otomatis
        if ($antrean_proses_ketua >= 5) {
            $this->kirimEmailNotifikasi(
                'ketua@koperasi-bps.com',
                'Pemberitahuan Pimpinan: Otorisasi Berkas Prioritas SAW',
                'Selamat datang Pimpinan, diinformasikan bahwa saat ini terdapat ' . $antrean_proses_ketua . ' berkas rekomendasi pembiayaan baru hasil kalkulasi SAW bendahara yang menunggu keputusan veto (Setuju/Tolak) Anda. Silakan akses panel pimpinan.'
            );
        }

        return redirect()->to(base_url('bendahara/pengajuan'))->with('success', 'Review administrasi berkas disimpan. Data otomatis bergeser ke Lembar Perhitungan SAW.');
    }

    /**
     * ===================================================================================
     * TAHAP 2: LEMBAR KALKULASI METODE SAW SESUAI RUMUS OPTIMASI BAB 3
     * ===================================================================================
     */
    public function index()
    {
        // Alur Status Tahap 2: Menghitung berkas yang berstatus 'proses' hasil verifikasi bendahara
        $pengajuan = $this->db->table('pengajuan_pinjaman')
                             ->join('anggota', 'anggota.id_anggota = pengajuan_pinjaman.id_anggota', 'left')
                             ->where('pengajuan_pinjaman.status_pengajuan', 'proses')
                             ->get()->getResultArray();

        if (!empty($pengajuan)) {
            // VEKTOR BOBOT UTUH ASLI SESUAI TABEL 3.2 TA
            $W = ['c1' => 1, 'c2' => 2, 'c3' => 3, 'c4' => 4, 'c5' => 5, 'c6' => 5];

            // LOOPING I: Pembentukan Matriks Keputusan Awal (X)
            foreach ($pengajuan as &$p) {
                $p['class'] = "secondary";
                $p['saran'] = "Belum Dinilai";

                $p['c3_jumlah_gaji']      = (float)$p['c3_jumlah_gaji'];
                $p['c4_lama_keanggotaan'] = (float)$p['c4_lama_keanggotaan'];

                // Hitung riil tunggakan dari database
                $pinjaman_aktif = $this->db->table('pinjaman')
                                           ->where('id_anggota', $p['id_anggota'])
                                           ->where('status_pinjaman', 'aktif')
                                           ->orderBy('id_pinjaman', 'DESC')
                                           ->get()->getRowArray();

                $total_tunggakan_riil = 0;
                
                if ($pinjaman_aktif) {
                    $pembayaran = $this->db->table('pembayaran_pinjaman')
                                           ->where('id_pinjaman', $pinjaman_aktif['id_pinjaman'])
                                           ->get()->getResultArray();

                    if (count($pembayaran) > 0) {
                        $tgl_awal  = new \DateTime($pembayaran[0]['tgl_bayar']);
                        $tgl_akhir = new \DateTime(date('Y-m-d'));
                        $interval  = $tgl_awal->diff($tgl_akhir);
                        $bulan_berjalan_kalender = ($interval->y * 12) + $interval->m + 1;
                        
                        $selisih_macet = $bulan_berjalan_kalender - count($pembayaran);
                        $total_tunggakan_riil = ($selisih_macet > 0) ? $selisih_macet : 0;
                    } else {
                        $tgl_kontrak = new \DateTime($pinjaman_aktif['tgl_pinjam'] ?? date('Y-m-d'));
                        $tgl_sekarang = new \DateTime(date('Y-m-d'));
                        $interval_kontrak = $tgl_kontrak->diff($tgl_sekarang);
                        $bulan_lewat = ($interval_kontrak->y * 12) + $interval_kontrak->m;
                        
                        $total_tunggakan_riil = max(0, $bulan_lewat); 
                    }
                }
                
                $p['tunggakan_bulan_riil'] = $total_tunggakan_riil;

                // Skor C6 Cost 
                if ($total_tunggakan_riil == 0) { $p['c6_skor'] = 1; }
                elseif ($total_tunggakan_riil <= 3) { $p['c6_skor'] = 2; }
                elseif ($total_tunggakan_riil <= 6) { $p['c6_skor'] = 3; }
                elseif ($total_tunggakan_riil <= 9) { $p['c6_skor'] = 4; }
                else { $p['c6_skor'] = 5; }

                // Skoring C5 Cost 
                $pinjam_nominal = (float)$p['jumlah_diajukan'];
                if ($pinjam_nominal < 3000000) { $p['c5_skor'] = 1; }
                elseif ($pinjam_nominal <= 4999999) { $p['c5_skor'] = 2; }
                elseif ($pinjam_nominal <= 7999999) { $p['c5_skor'] = 3; }
                elseif ($pinjam_nominal <= 10000000) { $p['c5_skor'] = 4; }
                else { $p['c5_skor'] = 5; }
            }
            unset($p);

            // TAHAP 1.2: Ambil Max/Min pembagi matriks normalisasi
            $maxC1 = max(array_column($pengajuan, 'c1_persetujuan_pasangan')) ?: 1;
            $maxC2 = max(array_column($pengajuan, 'c2_rhb')) ?: 1;
            $maxC3 = max(array_column($pengajuan, 'c3_jumlah_gaji')) ?: 1;
            $maxC4 = max(array_column($pengajuan, 'c4_lama_keanggotaan')) ?: 1;
            $minC5 = min(array_column($pengajuan, 'c5_skor')) ?: 1;
            $minC6 = min(array_column($pengajuan, 'c6_skor')) ?: 1;

            // LOOPING II: Perhitungan Nilai Optimasi Preferensi V
            foreach ($pengajuan as &$p) {
                $r1 = $p['c1_persetujuan_pasangan'] / $maxC1; 
                $r2 = ($p['c2_rhb'] ?: 1) / $maxC2; 
                $r3 = $p['c3_jumlah_gaji'] / $maxC3;
                $r4 = $p['c4_lama_keanggotaan'] / $maxC4;
                
                $r5 = ($p['c5_skor'] > 0) ? ($minC5 / $p['c5_skor']) : 0;
                $r6 = ($p['c6_skor'] > 0) ? ($minC6 / $p['c6_skor']) : 0; 

                $v = ($r1 * $W['c1']) + ($r2 * $W['c2']) + ($r3 * $W['c3']) + ($r4 * $W['c4']) + ($r5 * $W['c5']) + ($r6 * $W['c6']);
                
                // Nilai SAW dibulatkan setelah persamaan optimasi selesai
                $p['nilai_saw'] = round($v, 2);

                // UPDATE DATA MATANG PERMANEN KE DATABASE BARU
                $this->db->table('pengajuan_pinjaman')
                         ->where('id_pengajuan', $p['id_pengajuan'])
                         ->update([
                             'nilai_saw'            => $p['nilai_saw'],
                             'tunggakan_bulan_riil' => $p['tunggakan_bulan_riil']
                         ]);

                $tunggakan_riil = (int)$p['tunggakan_bulan_riil'];
                
                if ($p['nilai_saw'] >= 16.0) { $p['saran'] = "Sangat Layak"; $p['class'] = "success"; }
                elseif ($p['nilai_saw'] >= 12.0) { $p['saran'] = "Layak"; $p['class'] = "primary"; }
                else { $p['saran'] = "Kurang Layak"; $p['class'] = "danger"; }

                if ($tunggakan_riil >= 12) {
                    $p['saran'] = "Kurang Layak (Macet)";
                    $p['class'] = "danger";
                }
            }
            unset($p); 

            // Urutkan peringkat berdasarkan Indeks Kelayakan V Terbesar
            usort($pengajuan, function($a, $b) {
                return $b['nilai_saw'] <=> $a['nilai_saw'];
            });
        }

        return view('bendahara_koperasi/v_pengajuan_saw', [
            'title'     => 'Analisis Kelayakan Pinjaman ',
            'pengajuan' => $pengajuan
        ]);
    }

    /**
     * HALAMAN REVIEW BERKAS ADMINISTRASI
     */
    public function review($id)
    {
        $pengajuan = $this->db->table('pengajuan_pinjaman')
                              ->join('anggota', 'anggota.id_anggota = pengajuan_pinjaman.id_anggota', 'left')
                              ->where('id_pengajuan', $id)
                              ->get()->getRowArray();
   
        if (!$pengajuan) {
            return redirect()->to(base_url('bendahara/pengajuan'))->with('error', 'Data pengajuan tidak ditemukan!');
        }

        $detail_data = json_decode($pengajuan['detail_rhb'] ?? '[]', true);
        
        return view('bendahara_koperasi/v_pengajuan_review', [
            'title'    => 'Review Berkas Lampiran Administrasi',
            'p'        => $pengajuan,
            'judul'    => $detail_data['judul_rincian'] ?? '-',
            'rhb_list' => $detail_data['item'] ?? []
        ]);
    }

    /**
     * ===================================================================================
     * TAHAP 3: LOG HISTORIS & MANDAT PENCAIRAN (MURNI MEMBACA BERKAS 'DISETUJUI' OLEH KETUA)
     * ===================================================================================
     */
    public function riwayat() {
        $riwayat = $this->db->table('pengajuan_pinjaman')
                            ->join('anggota', 'anggota.id_anggota = pengajuan_pinjaman.id_anggota', 'left')
                            ->whereIn('pengajuan_pinjaman.status_pengajuan', ['disetujui', 'ditolak', 'transfer'])
                            ->orderBy('pengajuan_pinjaman.id_pengajuan', 'DESC')
                            ->get()->getResultArray();

        return view('bendahara_koperasi/v_pengajuan_riwayat', [
            'title'   => 'Log Historis & Mandat Pencairan Dana', 
            'riwayat' => $riwayat
        ]);
    }

    /**
     * TAHAP 3.2: EKSEKUSI MUTLAK TRANSFER PENCAIRAN BENDAHARA
     */
    public function transfer_pencairan() 
    {
        $id_pengajuan = $this->request->getPost('id_pengajuan');
        $id_anggota   = $this->request->getPost('id_anggota');
        $plafon       = (float)$this->request->getPost('plafon_real');
        $biaya_admin  = (float)($this->request->getPost('biaya_admin') ?? 0);
        $bank_tujuan  = $this->request->getPost('bank_tujuan');
        
        if (!$id_pengajuan || !$id_anggota) { 
            return redirect()->back()->with('error', 'Token data tidak valid!'); 
        }
        
        $pengajuan_data = $this->db->table('pengajuan_pinjaman')
                                   ->where('id_pengajuan', $id_pengajuan)
                                   ->get()->getRowArray();
                                   
        $tenor_real = (!empty($pengajuan_data['tenor_bulan'])) ? (int)$pengajuan_data['tenor_bulan'] : 12;

        $this->db->transBegin();
        try {
            $angsuran_pokok_perbulan = $plafon / $tenor_real;

            // TAHAP 1: Daftarkan Kontrak Pinjaman Baru ke Sistem
            $this->db->table('pinjaman')->insert([
                'id_anggota'        => $id_anggota, 
                'angsuran_perbulan' => $angsuran_pokok_perbulan, 
                'jasa_perbulan'     => $plafon * 0.01, 
                'jumlah_total'      => $plafon,        
                'angsuran_ke'       => 0,              
                'jumlah_potongan'   => $tenor_real,    
                'sisa_hutang'       => $plafon,        
                'status_pinjaman'   => 'aktif'         
            ]);

            // TAHAP 2: Biaya admin otomatis masuk ke rekap pengeluaran operasional
            if ($biaya_admin > 0) { 
                $this->db->table('pengeluaran')->insert([
                    'tgl_pengeluaran' => date('Y-m-d'), 
                    'keterangan'      => 'Biaya Admin Bank Transfer (Ref ID #' . $id_pengajuan . ')', 
                    'kategori'        => 'Biaya Admin', 
                    'bank'            => $bank_tujuan, 
                    'jumlah'          => $biaya_admin
                ]); 
            }

            // TAHAP 3: Ubah status berkas menjadi 'transfer' (Cair lunas)
            $this->db->table('pengajuan_pinjaman')->where('id_pengajuan', $id_pengajuan)->update(['status_pengajuan' => 'transfer']);
            
            // TAHAP 4: Kirim notifikasi bukti transfer lewat Gmail resmi
            $anggota_data = $this->db->table('anggota')->where('id_anggota', $id_anggota)->get()->getRowArray();
            if (!empty($anggota_data['email'])) {
                $pesan_email = "<h3>Pemberitahuan Pencairan Dana Pinjaman</h3>"
                             . "<p>Halo <strong>" . esc($anggota_data['nama_anggota']) . "</strong>,</p>"
                             . "<p>Dana pengajuan pembiayaan Anda dengan ID Referensi <strong>#" . $id_pengajuan . "</strong> telah sukses ditransfer oleh Bendahara Koperasi.</p>"
                             . "<ul>"
                             . "<li><strong>Plafon Pembiayaan:</strong> Rp " . number_format($plafon, 0, ',', '.') . "</li>"
                             . "<li><strong>Jangka Waktu Tenor:</strong> " . $tenor_real . " Bulan</li>"
                             . "<li><strong>Bank Tujuan Pencairan:</strong> " . esc($bank_tujuan) . "</li>"
                             . "</ul>"
                             . "<p>Kontrak pinjaman Anda di dalam sistem koperasi kini dinyatakan telah aktif. Silakan lakukan pemeriksaan saldo rekening bank Anda secara berkala.</p>";

                $this->kirimEmailNotifikasi($anggota_data['email'], 'Pencairan Dana Pinjaman Sukses | Koperasi BPS', $pesan_email);
            }

            $this->db->transCommit();
            return redirect()->to(base_url('bendahara/pengajuan/riwayat'))->with('success', 'Dana pinjaman sukses dicairkan, biaya admin tercatat otomatis, dan notifikasi email lunas dikirim!');
        } catch (\Exception $e) { 
            $this->db->transRollback(); 
            return redirect()->back()->with('error', 'Sistem Gagal Memasukkan Data: ' . $e->getMessage()); 
        }
    } 

    public function delete($id)
    {
        $this->db->table('pengajuan_pinjaman')->where('id_pengajuan', $id)->delete();
        return redirect()->to(base_url('bendahara/pengajuan'))->with('success', 'Dokumen permohonan berhasil dihapus permanent dari sistem.');
    }

    /**
     * ===================================================================================
     * 🟢 CORE SMTP GATEWAY MAILER NOTIFICATION ENGINE (SINKRON DENGAN CONFIG GMAIL)
     * ===================================================================================
     */
    private function kirimEmailNotifikasi($to, $subject, $message) 
    {
        $email = \Config\Services::email();
        
        // 🟢 FIX: Baris ->initialize() dihapus agar tidak memicu error 'Expected 1 arguments'
        // Library otomatis membaca konfigurasi tersentralisasi dari app/Config/Email.php
        
        $email->setTo($to);
        $email->setSubject($subject);
        $email->setMessage($message);
        
        // Mengeksekusi pengiriman ke SMTP Server Google Mail
        if (!@$email->send()) {
            // Sembunyikan error log dengan symbol '@' agar tidak memutus antarmuka aplikasi user
        }
    }
}