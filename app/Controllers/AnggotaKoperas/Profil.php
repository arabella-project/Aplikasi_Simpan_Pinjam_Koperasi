<?php

namespace App\Controllers\AnggotaKoperasi;

use App\Controllers\BaseController;

class Profil extends BaseController
{
    protected $db;

    public function __construct() {
        $this->db = \Config\Database::connect();
    }

    public function index()
    {
        $id_user = session()->get('id_user');
        
        // Mengambil data gabungan dengan JOIN antara users dan anggota
        $data_user = $this->db->table('anggota')
                              ->join('users', 'users.id_user = anggota.id_user')
                              ->where('anggota.id_user', $id_user)
                              ->get()->getRowArray();

        $data = [
            'title' => 'Pengaturan Akun',
            'user'  => $data_user
        ];
        return view('anggota/v_profil', $data);
    }

    public function update()
    {
        $id_user = session()->get('id_user');
        
        // 1. Ambil data kiriman dari formulir
        $nama_lengkap = $this->request->getPost('nama_lengkap');
        $no_telp      = $this->request->getPost('no_telp');
        $email        = $this->request->getPost('email');
        $old_foto     = $this->request->getPost('old_foto');

        // 2. Proses upload file gambar ke folder lokal laptop
        $file_foto = $this->request->getFile('foto');
        $nama_file_foto = $old_foto; 

        if ($file_foto && $file_foto->isValid() && !$file_foto->hasMoved()) {
            $nama_file_foto = $file_foto->getRandomName();
            $file_foto->move(FCPATH . 'uploads/profil/', $nama_file_foto);
            
            // Hapus berkas lama jika ada penggantian foto baru
            if (!empty($old_foto) && file_exists(FCPATH . 'uploads/profil/' . $old_foto)) {
                @unlink(FCPATH . 'uploads/profil/' . $old_foto);
            }
        }

        // 3. Sinkronisasi Tabel 'users' (Update kolom nama_lengkap)
        $this->db->table('users')->where('id_user', $id_user)->update([
            'nama_lengkap' => $nama_lengkap
        ]);

        // 4. Sinkronisasi Tabel 'anggota' (Update nama_anggota, email, no_telp, foto)
        $this->db->table('anggota')->where('id_user', $id_user)->update([
            'nama_anggota' => $nama_lengkap, // Menyelaraskan nama_anggota dengan nama_lengkap
            'email'        => $email,
            'no_telp'      => $no_telp,
            'foto'         => $nama_file_foto
        ]);

        // 5. Segarkan data session nama supaya Topbar langsung berubah otomatis
        session()->set('nama_lengkap', $nama_lengkap);

        return redirect()->back()->with('success', 'Data profil dan foto Anda berhasil diperbarui!');
    }

    public function update_password()
    {
        $id_user = session()->get('id_user');
        $pass_lama = $this->request->getPost('old_password');
        $pass_baru = $this->request->getPost('new_password');
        $konfirmasi = $this->request->getPost('confirm_password');

        $user = $this->db->table('users')->where('id_user', $id_user)->get()->getRowArray();

        if (!password_verify($pass_lama, $user['password'])) {
            return redirect()->back()->with('error', 'Password lama Anda salah!');
        }

        if ($pass_baru !== $konfirmasi) {
            return redirect()->back()->with('error', 'Konfirmasi password baru tidak cocok!');
        }

        $this->db->table('users')->where('id_user', $id_user)->update([
            'password' => password_hash($pass_baru, PASSWORD_DEFAULT)
        ]);

        return redirect()->back()->with('success', 'Password akun Anda berhasil diperbarui!');
    }
}