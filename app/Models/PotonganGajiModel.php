<?php

namespace App\Models;

use CodeIgniter\Model;

class PotonganGajiModel extends Model
{
    protected $table            = 'potongan_gaji';
    protected $primaryKey       = 'id_potongan';
    protected $allowedFields    = [
        'bulan_tahun', 'id_anggota', 'total_potongan', 
        'bank_tujuan', 'status_potongan', 'tgl_pengajuan'
    ];

    public function getLaporanBulanan($bulan)
    {
        return $this->select('potongan_gaji.*, anggota.nama_anggota, anggota.nip')
                    ->join('anggota', 'anggota.id_anggota = potongan_gaji.id_anggota')
                    ->where('bulan_tahun', $bulan)
                    ->findAll();
    }
}