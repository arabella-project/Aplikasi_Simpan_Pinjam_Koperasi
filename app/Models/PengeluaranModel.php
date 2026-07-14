<?php

namespace App\Models;

use CodeIgniter\Model;

class PengeluaranModel extends Model
{
    protected $table            = 'pengeluaran';
    protected $primaryKey       = 'id_pengeluaran';
    protected $allowedFields    = ['tgl_pengeluaran', 'keterangan', 'kategori', 'jumlah', 'bank'];
}