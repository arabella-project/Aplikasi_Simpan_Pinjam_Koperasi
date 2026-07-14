<?php

namespace App\Controllers\BendaharaKantor;

use App\Controllers\BaseController;

class LogRiwayat extends BaseController
{
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    /**
     * HALAMAN UTAMA: LOG RIWAYAT PEMOTONGAN GAJI BULANAN KANTOR BPS
     */
    public function index()
    {
        $bulan_filter = $this->request->getGet('bulan') ?: date('Y-m');

        // DETEKSI OTOMATIS STRUKTUR KOLOM TABEL 'potongan_gaji' DI PHPMYADMIN
        $fields = $this->db->getFieldNames('potongan_gaji');
        
        $kolom_status = 'status'; 
        if (in_array('status_potongan', $fields)) {
            $kolom_status = 'status_potongan';
        } elseif (in_array('status_verifikasi', $fields)) {
            $kolom_status = 'status_verifikasi';
        }

        $kolom_nominal = 'total_potongan'; 
        if (in_array('jumlah_potongan', $fields)) {
            $kolom_nominal = 'jumlah_potongan';
        }

        // Penyesuaian Kolom Tanggal agar sinkron dengan Dashbord
        $kolom_tgl = 'tgl_pengajuan';
        if (in_array('tgl_validasi', $fields)) {
            $kolom_tgl = 'tgl_validasi';
        } elseif (in_array('tgl_eksekusi', $fields)) {
            $kolom_tgl = 'tgl_eksekusi';
        }

        // EKSEKUSI DATA QUERY SECARA AMAN DAN AMBIL ALIAS NYA UNTUK VIEW
        $log = $this->db->table('potongan_gaji')
                        ->select("potongan_gaji.*, anggota.nama_anggota, anggota.email, 
                                 potongan_gaji.{$kolom_status} as status_verifikasi, 
                                 potongan_gaji.{$kolom_nominal} as jumlah_potongan, 
                                 potongan_gaji.{$kolom_tgl} as tgl_eksekusi")
                        ->join('anggota', 'anggota.id_anggota = potongan_gaji.id_anggota')
                        // FIX: Menambahkan variasi diterima dan ditolak
                        ->whereIn("potongan_gaji.{$kolom_status}", ['berhasil', 'gagal', 'diterima', 'ditolak'])
                        ->like("potongan_gaji.{$kolom_tgl}", $bulan_filter, 'after')
                        ->orderBy("potongan_gaji.{$kolom_tgl}", 'DESC')
                        ->get()
                        ->getResultArray();

        // Menghitung ringkasan total uang payroll bulanan terproses
        $summary = [
            'sukses_nominal' => 0,
            'gagal_nominal'  => 0,
            'sukses_count'   => 0,
            'gagal_count'    => 0,
        ];

        foreach ($log as $row) {
            // FIX: Cek status array
            if (in_array(strtolower($row['status_verifikasi']), ['berhasil', 'diterima'])) {
                $summary['sukses_nominal'] += (float)$row['jumlah_potongan'];
                $summary['sukses_count']++;
            } else {
                $summary['gagal_nominal'] += (float)$row['jumlah_potongan'];
                $summary['gagal_count']++;
            }
        }

        $data = [
            'title'        => 'Log Audit Riwayat Pemotongan Gaji Bulanan',
            'log'          => $log,
            'summary'      => $summary,
            'bulan_pilihan'=> $bulan_filter
        ];

        return view('bendahara_kantor/v_log_riwayat', $data);
    }
}