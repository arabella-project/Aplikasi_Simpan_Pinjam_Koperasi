<?php

namespace App\Controllers\AnggotaKoperasi;

use App\Controllers\BaseController;

class Pinjaman extends BaseController
{
    protected $db;

    public function __construct() {
        $this->db = \Config\Database::connect();
    }

    /**
     * Menampilkan Dashboard Pinjaman Pribadi Anggota (Multi-Kontrak Support)
     */
    public function index()
    {
        // 1. Ambil ID Anggota dari session
        $id_anggota = session()->get('id_anggota'); 

        // 2. AMBIL SEMUA DATA PINJAMAN AKTIF (Gunakan getResultArray bukan getRowArray)
        $list_pinjaman_aktif = $this->db->table('pinjaman')
                                        ->where('id_anggota', $id_anggota)
                                        ->where('status_pinjaman', 'aktif')
                                        ->orderBy('id_pinjaman', 'ASC')
                                        ->get()->getResultArray();

        // 3. SISIPKAN HISTORI PEMBAYARAN KE MASING-MASING KONTRAK PINJAMAN
        foreach ($list_pinjaman_aktif as &$pinjaman) {
            $pinjaman['histori_angsuran'] = $this->db->table('pembayaran_pinjaman')
                                                     ->where('id_pinjaman', $pinjaman['id_pinjaman'])
                                                     ->orderBy('angsuran_ke', 'DESC') // Angsuran terbaru di atas
                                                     ->get()->getResultArray();
        }
        unset($pinjaman); // Memutuskan referensi memori loop

        // 4. RIWAYAT PENGAJUAN (Status SAW: pending/proses/ditolak)
        $riwayat_pengajuan = $this->db->table('pengajuan_pinjaman')
                                      ->where('id_anggota', $id_anggota)
                                      ->orderBy('tgl_pengajuan', 'DESC')
                                      ->get()->getResultArray();

        $data = [
            'title'      => 'Pinjaman Saya | Koperasi BPS',
            'list_aktif' => $list_pinjaman_aktif, // Mengirim data array multi-kontrak
            'pengajuan'  => $riwayat_pengajuan,
            'nama'       => session()->get('nama_lengkap')
        ];

        return view('anggota/v_pinjaman', $data);
    }
}