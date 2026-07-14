<?php

namespace App\Models;

use CodeIgniter\Model;

class PinjamanModel extends Model
{
    protected $table            = 'pinjaman';
    protected $primaryKey       = 'id_pinjaman';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = [
        'id_anggota', 'jumlah_pinjaman', 'tgl_pinjaman', 
        'lama_pinjaman', 'bunga', 'total_pinjaman', 
        'sisa_hutang', 'angsuran_ke', 'status_pinjaman'
    ];
}