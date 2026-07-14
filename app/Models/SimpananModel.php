<?php

namespace App\Models;

use CodeIgniter\Model;

class SimpananModel extends Model
{
    protected $table            = 'simpanan';
    protected $primaryKey       = 'id_simpanan';
    protected $allowedFields    = ['id_anggota', 'simpanan_pokok', 'simpanan_wajib', 'simpanan_sukarela'];

    public function getTotalSimpanan()
    {
        $result = $this->select('SUM(simpanan_pokok + simpanan_wajib + simpanan_sukarela) as total')
                       ->first();
        return $result['total'] ?? 0;
    }
}