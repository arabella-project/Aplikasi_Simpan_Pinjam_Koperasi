<?php

namespace App\Models;

use CodeIgniter\Model;

class AnggotaModel extends Model
{
    protected $table            = 'anggota';
    protected $primaryKey       = 'id_anggota';
    protected $allowedFields    = ['id_user', 'nama_anggota', 'nip', 'gaji_pokok'];

    public function getAnggotaWithUser()
    {
        return $this->select('anggota.*, users.username, users.role')
                    ->join('users', 'users.id_user = anggota.id_user')
                    ->findAll();
    }
}