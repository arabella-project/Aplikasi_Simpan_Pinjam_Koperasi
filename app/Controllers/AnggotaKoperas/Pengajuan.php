<?php

namespace App\Controllers\AnggotaKoperasi;

use App\Controllers\BaseController;

class Pengajuan extends BaseController
{
    protected $db;

    public function __construct() {
        $this->db = \Config\Database::connect();
    }

    /**
     * HALAMAN FORM UTAMA: Mengizinkan Anggota Masuk Form & Cek Syarat Top-Up Minimal 1 Tahun
     */
    public function index()
    {
        $id_anggota = session()->get('id_anggota');
        
        // Ambil data pinjaman yang saat ini masih berjalan (aktif)
        $pjn_aktif = $this->db->table('pinjaman')
                              ->where('id_anggota', $id_anggota)
                              ->where('sisa_hutang >', 0)
                              ->where('status_pinjaman', 'aktif')
                              ->orderBy('id_pinjaman', 'DESC')
                              ->get()->getRowArray();

        $boleh_pinjam = true;
        $pesan_blokir = "";
        $skor_c6 = 1; // Default Skor = 1 (Sangat Lancar / Risiko Terendah dalam kriteria COST)
        
        // FIX INITIALIZATION: Deklarasikan variabel default di luar blok agar anti-error undefined
        $bulan_berjalan_total = 0;
        $sudah_berjalan_1_tahun = true;

        if ($pjn_aktif) {
            $pembayaran = $this->db->table('pembayaran_pinjaman')
                                   ->where('id_pinjaman', $pjn_aktif['id_pinjaman'])
                                   ->orderBy('angsuran_ke', 'ASC')
                                   ->get()->getResultArray();

            $total_tunggakan_riil = 0;

            // Jika sudah ada riwayat pembayaran angsuran sebelumnya
            if (count($pembayaran) > 0) {
                $tgl_awal  = new \DateTime($pembayaran[0]['tgl_bayar']);
                $tgl_akhir = new \DateTime(date('Y-m-d'));
                $interval  = $tgl_awal->diff($tgl_akhir);
                
                // Syarat Top-Up: Mengunci masa berjalan pinjaman sebelumnya minimal tepat 1 tahun (>= 12 bulan)
                $bulan_berjalan_total = ($interval->y * 12) + $interval->m + 1;
                if ($bulan_berjalan_total >= 12) {
                    $sudah_berjalan_1_tahun = true;
                } else {
                    $sudah_berjalan_1_tahun = false;
                }

                // Hitung selisih tunggakan kalender riil
                $selisih_macet = $bulan_berjalan_total - count($pembayaran);
                $total_tunggakan_riil = ($selisih_macet > 0) ? $selisih_macet : 0;
            } else {
                // FALLBACK: Jika ada pinjaman aktif tapi belum pernah bayar angsuran sama sekali
                $tgl_kontrak = new \DateTime($pjn_aktif['tgl_pinjam'] ?? date('Y-m-d'));
                $tgl_sekarang = new \DateTime(date('Y-m-d'));
                $interval_kontrak = $tgl_kontrak->diff($tgl_sekarang);
                
                $bulan_berjalan_total = ($interval_kontrak->y * 12) + $interval_kontrak->m;
                $sudah_berjalan_1_tahun = ($bulan_berjalan_total >= 12);
                $total_tunggakan_riil = $bulan_berjalan_total; // Tunggakan dihitung penuh selama bulan berjalan berjalan
            }

            // Pemetaan Skor Kriteria C6 Berdasarkan Konsep COST Skripsi
            if ($total_tunggakan_riil == 0) { $skor_c6 = 1; }
            elseif ($total_tunggakan_riil <= 3) { $skor_c6 = 2; }
            elseif ($total_tunggakan_riil <= 6) { $skor_c6 = 3; }
            elseif ($total_tunggakan_riil <= 9) { $skor_c6 = 4; }
            else { $skor_c6 = 5; }

            // VALIDASI PERBAIKAN: Blokir form hanya berlaku jika masa cicilan belum mencapai 1 tahun.
            if (!$sudah_berjalan_1_tahun) {
                $boleh_pinjam = false;
                $pesan_blokir = "Pengajuan Top-Up Pinjaman Baru Ditolak! Pinjaman sebelumnya belum berjalan minimal 1 Tahun (12 Bulan). Saat ini baru berjalan " . $bulan_berjalan_total . " bulan.";
            }
        }

        $data = [
            'title'        => 'Pengajuan Pinjaman Baru',
            'status_label' => $pjn_aktif ? 'Ada Pinjaman Berjalan' : 'Bersih / Lunas',
            'skor_c6'      => $skor_c6,
            'boleh_pinjam' => $boleh_pinjam,
            'pesan_blokir' => $pesan_blokir
        ];
        

        return view('anggota/Pengajuan_tamba', $data);
    }

    /**
     * AKSI SIMPAN SISI ANGGOTA: Hitung C6 Otomatis di Server & Penguncian Status Menuju Antrean Berkas
     */
    public function simpan()
    {
        $id_anggota = session()->get('id_anggota');
        $jumlah_diajukan = (float)$this->request->getPost('jumlah_diajukan');
        $tenor_bulan = (int)$this->request->getPost('tenor_bulan');

        // 1. Validasi Keamanan Server Sisi Nominal Dana
        if ($jumlah_diajukan > 80000000) {
            return redirect()->back()->withInput()->with('error', 'Gagal mengirim berkas! Batas maksimal pengajuan dana pinjaman adalah Rp 80.000.000');
        }

        // 2. KEAMANAN SERVER HARD-VALIDATION: Mengunci keabsahan pilihan kelipatan 6 bulan (Maks 24)
        $tenor_valid = [6, 12, 18, 24];
        if (!in_array($tenor_bulan, $tenor_valid)) {
            return redirect()->back()->withInput()->with('error', 'Gagal memproses! Pilihan masa tenor angsuran tidak valid atau memanipulasi sistem.');
        }
        
        $judul_rhb       = $this->request->getPost('judul_rhb'); 
        $nama_bank       = $this->request->getPost('nama_bank');
        $nomor_rekening  = $this->request->getPost('nomor_rekening');
        $c3              = $this->request->getPost('c3');
        $c4              = $this->request->getPost('c4');

        // KEAMANAN SERVER: HITUNG ULANG INDEKS SKOR C6 SECARA OTOMATIS (SISTEM BACKEND)
        $pjn_aktif = $this->db->table('pinjaman')
                              ->where('id_anggota', $id_anggota)
                              ->where('sisa_hutang >', 0)
                              ->where('status_pinjaman', 'aktif')
                              ->orderBy('id_pinjaman', 'DESC')
                              ->get()->getRowArray();

        $server_skor_c6 = 1; // Default jika lunas/bersih (Risiko Terendah)
        if ($pjn_aktif) {
            $pembayaran = $this->db->table('pembayaran_pinjaman')
                                   ->where('id_pinjaman', $pjn_aktif['id_pinjaman'])
                                   ->get()->getResultArray();

            if (count($pembayaran) > 0) {
                $tgl_awal  = new \DateTime($pembayaran[0]['tgl_bayar']);
                $tgl_akhir = new \DateTime(date('Y-m-d'));
                $interval  = $tgl_awal->diff($tgl_akhir);
                $bulan_berjalan_total = ($interval->y * 12) + $interval->m + 1;
                $selisih_macet = $bulan_berjalan_total - count($pembayaran);
                $total_tunggakan_riil = ($selisih_macet > 0) ? $selisih_macet : 0;
            } else {
                $tgl_kontrak = new \DateTime($pjn_aktif['tgl_pinjam'] ?? date('Y-m-d'));
                $tgl_sekarang = new \DateTime(date('Y-m-d'));
                $interval_kontrak = $tgl_kontrak->diff($tgl_sekarang);
                $total_tunggakan_riil = ($interval_kontrak->y * 12) + $interval_kontrak->m;
            }

            if ($total_tunggakan_riil == 0) { $server_skor_c6 = 1; }
            elseif ($total_tunggakan_riil <= 3) { $server_skor_c6 = 2; }
            elseif ($total_tunggakan_riil <= 6) { $server_skor_c6 = 3; }
            elseif ($total_tunggakan_riil <= 9) { $server_skor_c6 = 4; }
            else { $server_skor_c6 = 5; }
        }

        // Upload Dokumen Persetujuan Pasangan (C1)
        $file_c1 = $this->request->getFile('bukti_c1');
        $nama_file_c1 = '';
        if ($jumlah_diajukan >= 25000000 && (!$file_c1 || !$file_c1->isValid())) {
            return redirect()->back()->withInput()->with('error', 'Pengajuan dana di atas Rp 25.000.000 wajib mengunggah file Bukti Surat Persetujuan Pasangan.');
        }

        if ($file_c1 && $file_c1->isValid() && !$file_c1->hasMoved()) {
            $nama_file_c1 = $file_c1->getRandomName();
            $file_c1->move(FCPATH . 'uploads/bukti_c1/', $nama_file_c1);
        }

        // Parsing Tabel Rencana Hidup Berkelanjutan (C2)
        $rhb = $this->request->getPost('rhb');
        $json_rhb = '[]';
        if (!empty($rhb['kebutuhan'])) {
            $rhb_data = [];
            foreach ($rhb['kebutuhan'] as $key => $val) {
                if (!empty($val)) {
                    $rhb_data[] = [
                        'kebutuhan'    => $val,
                        'harga_satuan' => (int)($rhb['harga'][$key] ?? 0),
                        'qty'          => (int)($rhb['qty'][$key] ?? 0),
                        'subtotal'     => (int)($rhb['subtotal'][$key] ?? 0)
                    ];
                }
            }
            if (count($rhb_data) > 0) {
                $json_rhb = json_encode(['judul_rincian' => $judul_rhb, 'item' => $rhb_data]);
            }
        }

        // SIMPAN RECORDS DATA PENGAJUAN PINJAMAN BARU
        $data_pengajuan = [
            'id_anggota'              => $id_anggota,
            'jumlah_diajukan'         => $jumlah_diajukan,
            'tenor_bulan'             => $tenor_bulan, 
            'nama_bank'               => $nama_bank,
            'nomor_rekening'          => $nomor_rekening,
            'tgl_pengajuan'           => date('Y-m-d H:i:s'),
            'c1_persetujuan_pasangan' => 0, 
            'bukti_c1'                => $nama_file_c1,
            'c2_rhb'                  => 1, 
            'detail_rhb'              => $json_rhb,
            'c3_jumlah_gaji'          => $c3,
            'c4_lama_keanggotaan'     => $c4,
            'c5_jumlah_pinjaman'      => $jumlah_diajukan,
            'c6_riwayat_peminjaman'   => $server_skor_c6, 
            'status_pengajuan'        => 'pending', 
            'nilai_saw'               => 0.0000
        ];

        $this->db->table('pengajuan_pinjaman')->insert($data_pengajuan);
        return redirect()->to(base_url('anggota/dashbord'))->with('success', 'Pengajuan berhasil terkirim! Silakan serahkan berkas fisik ke Bendahara untuk diproses pada sistem analisis SAW.');
    }
}