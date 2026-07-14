<?php

namespace App\Controllers\AnggotaKoperasi;

use App\Controllers\BaseController;

class Dashbord extends BaseController
{
    protected $db;

    public function __construct() {
        $this->db = \Config\Database::connect();
    }

    public function index()
    {
        $id_anggota = session()->get('id_anggota');

        // 1. Data Ringkasan (Simpanan & Pinjaman)
        $simpanan = $this->db->table('simpanan')->where('id_anggota', $id_anggota)->get()->getRowArray();
        $total_simpanan = ($simpanan) ? (float)$simpanan['simpanan_pokok'] + (float)$simpanan['simpanan_wajib'] + (float)$simpanan['simpanan_sukarela'] : 0;

        $pinjaman = $this->db->table('pinjaman')->where('id_anggota', $id_anggota)->where('sisa_hutang >', 0)->get()->getRowArray();

        // 2. Data Pengajuan Pinjaman dengan Tracking Status
        $pengajuan_log = $this->db->table('pengajuan_pinjaman')
                                  ->where('id_anggota', $id_anggota)
                                  ->orderBy('tgl_pengajuan', 'DESC')
                                  ->get()->getResultArray();

        // 3. LOGIKA BARU: Ambil Riwayat Transaksi Penarikan Dana untuk Tracking Dashboard
        $penarikan_log = $this->db->table('pengajuan_penarikan')
                                  ->where('id_anggota', $id_anggota)
                                  ->orderBy('tgl_pengajuan', 'DESC')
                                  ->get()->getResultArray();

        $data = [
            'title'          => 'Dashboard Utama',
            'nama_lengkap'   => session()->get('nama_lengkap'),
            'id_anggota'     => $id_anggota,
            'total_simpanan' => 'Rp ' . number_format($total_simpanan, 0, ',', '.'),
            'sisa_pinjaman'  => $pinjaman ? 'Rp ' . number_format($pinjaman['sisa_hutang'], 0, ',', '.') : 'Rp 0',
            'has_loan'       => $pinjaman ? true : false,
            'status_pinjam'  => $pinjaman ? 'Ada Pinjaman Aktif' : 'Bebas Pinjaman',
            'pengajuan'      => $pengajuan_log,
            'penarikan'      => $penarikan_log // Dikirim ke view anggota
        ];

        return view('anggota/v_dashboard', $data);
    }
}