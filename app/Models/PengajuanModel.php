<?php

namespace App\Models;

use CodeIgniter\Model;

class PengajuanModel extends Model
{
    protected $table            = 'pengajuan_pinjaman';
    protected $primaryKey       = 'id_pengajuan';
    protected $allowedFields    = [
        'id_anggota', 'jumlah_diajukan', 'tgl_pengajuan',
        'c1_persetujuan_pasangan', 'bukti_c1', 'c2_rhb', 
        'detail_rhb', 'c3_jumlah_gaji', 'c4_lama_keanggotaan', 
        'c5_jumlah_pinjaman', 'c6_riwayat_peminjaman',
        'status_pengajuan', 'nilai_saw'
    ];

    public function getPengajuanByStatus($status)
    {
        return $this->select('pengajuan_pinjaman.*, anggota.nama_anggota, anggota.gaji_pokok')
                    ->join('anggota', 'anggota.id_anggota = pengajuan_pinjaman.id_anggota')
                    ->where('pengajuan_pinjaman.status_pengajuan', $status)
                    ->orderBy('pengajuan_pinjaman.tgl_pengajuan', 'DESC')
                    ->findAll();
    }
}