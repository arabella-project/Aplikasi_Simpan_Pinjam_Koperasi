<?php

namespace App\Controllers\AnggotaKoperasi;

use App\Controllers\BaseController;

class Simpanan extends BaseController
{
    protected $db;

    public function __construct() {
        $this->db = \Config\Database::connect();
    }

    public function index()
    {
        // Mengambil ID Anggota dari session (hasil perbaikan Auth.php tadi)
        $id_anggota = session()->get('id_anggota');

        // 1. Ambil data saldo simpanan milik anggota
        $data_simpanan = $this->db->table('simpanan')
                                  ->where('id_anggota', $id_anggota)
                                  ->get()->getRowArray();

        // 2. Jika data saldo belum ada, set semua ke 0
        if (!$data_simpanan) {
            $data_simpanan = [
                'simpanan_pokok'    => 0,
                'simpanan_wajib'    => 0,
                'simpanan_sukarela' => 0
            ];
        }

        // 3. Ambil Riwayat Transaksi (Mutasi) Simpanan
        // PERBAIKAN: Menggunakan 'tgl_transaksi' sesuai struktur MariaDB kamu
        $history_transaksi = $this->db->table('transaksi_simpanan')
                                      ->where('id_anggota', $id_anggota)
                                      ->orderBy('tgl_transaksi', 'DESC')
                                      ->get()->getResultArray();

        // 4. Hitung Total Saldo
        $total_saldo = $data_simpanan['simpanan_pokok'] + 
                       $data_simpanan['simpanan_wajib'] + 
                       $data_simpanan['simpanan_sukarela'];

        $data = [
            'title'    => 'Data Simpanan Saya',
            'simpanan' => $data_simpanan,
            'history'  => $history_transaksi,
            'total'    => $total_saldo,
            'nama'     => session()->get('nama_lengkap')
        ];

        return view('anggota/v_simpanan', $data);
    }
}