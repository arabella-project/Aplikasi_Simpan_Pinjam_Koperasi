<?php

namespace App\Controllers\BendaharaKoperasi;

use App\Controllers\BaseController;

class Akun extends BaseController
{
    protected $db;
    protected $tbl_akun;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
        
        // 🔍 DETEKSI DINAMIS NAMA TABEL KREDENSIAL LOGIN AKSES Anda
        if ($this->db->tableExists('user')) {
            $this->tbl_akun = 'user';
        } elseif ($this->db->tableExists('users')) {
            $this->tbl_akun = 'users';
        } else {
            $this->tbl_akun = 'user'; // Default mapping sesuai arsitektur awal
        }
    }

    /**
     * HALAMAN UTAMA: Menampilkan Daftar Gabungan Akun Login dan Profil Anggota
     */
    public function index()
    {
        if ($this->tbl_akun === 'anggota') {
            $users = $this->db->table('anggota')
                              ->orderBy('id_anggota', 'DESC')
                              ->get()->getResultArray();
        } else {
            // 🟢 PERBAIKAN FATAL: Memaksa MySQL memilih id_anggota dari tabel anggota agar tidak tertimpa/hilang
            $users = $this->db->table('anggota')
                              ->select('anggota.*, ' . $this->tbl_akun . '.username, ' . $this->tbl_akun . '.role')
                              ->join($this->tbl_akun, $this->tbl_akun . '.id_user = anggota.id_user', 'left')
                              ->orderBy('anggota.id_anggota', 'DESC')
                              ->get()->getResultArray();
        }

        return view('bendahara_koperasi/akun', [
            'title' => 'Manajemen Kelola Akun Pengguna',
            'users' => $users
        ]);
    }

    /**
     * AKSI SIMPAN: Registrasi Multi-Tabel Sinkron (User Login -> Profil Anggota)
     */
    public function simpan()
    {
        $nama         = $this->request->getPost('nama_anggota'); 
        $username     = $this->request->getPost('username');
        $password_raw = $this->request->getPost('password');
        $role         = $this->request->getPost('role'); 
        $gaji_pokok   = (float)$this->request->getPost('gaji_pokok');
        $email        = $this->request->getPost('email') ?? null;
        $no_telp      = $this->request->getPost('no_telp') ?? null;

        // Validasi Duplikasi Kredensial Username
        $cek_username = $this->db->table($this->tbl_akun)->where('username', $username)->get()->getRow();
        if ($cek_username) {
            return redirect()->back()->with('error', 'Gagal membuat akun! Username "' . $username . '" sudah terdaftar.');
        }

        $password_hash = password_hash($password_raw, PASSWORD_DEFAULT);

        $this->db->transBegin();
        try {
            // 1. Amankan data otentikasi masuk ke tabel kredensial akun
            $data_user = [
                'username' => trim($username),
                'password' => $password_hash,
                'role'     => $role
            ];
            $this->db->table($this->tbl_akun)->insert($data_user);
            $insert_id_user = $this->db->insertID(); // Mengambil id_user yang terbuat otomatis

            // 2. Amankan data identitas personal ke tabel anggota sesuai field phpMyAdmin Anda
            $data_anggota = [
                'id_user'      => $insert_id_user, // Mengunci keterikatan relasi
                'nama_anggota' => $nama,
                'gaji_pokok'   => $gaji_pokok,
                'email'        => $email,
                'no_telp'      => $no_telp,
                'foto'         => 'default.jpg'
            ];
            $this->db->table('anggota')->insert($data_anggota);

            $this->db->transCommit();
            return redirect()->to(base_url('bendahara/akun'))->with('success', 'Akun & Profil anggota baru berhasil didaftarkan ke sistem!');
        } catch (\Exception $e) {
            $this->db->transRollback();
            return redirect()->back()->with('error', 'Sistem Gagal Menyimpan: ' . $e->getMessage());
        }
    }

    /**
     * AKSI UPDATE: Mengubah Data Akun Terdaftar secara Multi-Tabel
     */
    public function update()
    {
        $id_anggota   = $this->request->getPost('id_anggota');
        $id_user      = $this->request->getPost('id_user');
        $nama         = $this->request->getPost('nama_anggota');
        $username     = $this->request->getPost('username');
        $password_raw = $this->request->getPost('password');
        $role         = $this->request->getPost('role');
        $gaji_pokok   = (float)$this->request->getPost('gaji_pokok');
        $email        = $this->request->getPost('email');
        $no_telp      = $this->request->getPost('no_telp');

        // Cek username duplikat pada pengguna lain
        $cek_username = $this->db->table($this->tbl_akun)
                                 ->where('username', $username)
                                 ->where('id_user !=', $id_user)
                                 ->get()->getRow();
        if ($cek_username) {
            return redirect()->back()->with('error', 'Gagal memperbarui! Username sudah dipakai pengguna lain.');
        }

        $this->db->transBegin();
        try {
            // Update Data Kredensial Akun Login
            $data_user = [
                'username' => trim($username),
                'role'     => $role
            ];
            if (!empty($password_raw)) {
                $data_user['password'] = password_hash($password_raw, PASSWORD_DEFAULT);
            }
            $this->db->table($this->tbl_akun)->where('id_user', $id_user)->update($data_user);

            // Update Data Komponen Identitas Profil Anggota
            $data_anggota = [
                'nama_anggota' => $nama,
                'gaji_pokok'   => $gaji_pokok,
                'email'        => $email,
                'no_telp'      => $no_telp
            ];
            $this->db->table('anggota')->where('id_anggota', $id_anggota)->update($data_anggota);

            $this->db->transCommit();
            return redirect()->to(base_url('bendahara/akun'))->with('success', 'Data akun & profil sukses diperbarui!');
        } catch (\Exception $e) {
            $this->db->transRollback();
            return redirect()->back()->with('error', 'Gagal memperbarui: ' . $e->getMessage());
        }
    }

    /**
     * AKSI DELETE: Hapus Paksa Dua Tabel Secara Bersamaan (Force Multi-Table Delete)
     */
    public function hapus($id = null)
    {
        if (!$id) {
            return redirect()->to(base_url('bendahara/akun'))->with('error', 'ID Anggota tidak ditemukan.');
        }

        $id_anggota = (int)$id;

        // Proteksi Keamanan: Cegah bendahara melenyapkan akunnya sendiri saat sedang login aktif
        if (session()->get('id_anggota') == $id_anggota) {
            return redirect()->back()->with('error', 'Tindakan ditolak! Anda tidak diperbolehkan menghapus akun Anda sendiri yang sedang aktif.');
        }

        // Ambil record anggota untuk melacak id_user pasangannya
        $anggota = $this->db->table('anggota')->where('id_anggota', $id_anggota)->get()->getRowArray();
        if (!$anggota) {
            return redirect()->to(base_url('bendahara/akun'))->with('error', 'Data riwayat anggota tidak ditemukan.');
        }

        $this->db->transBegin();
        try {
            // 🛡️ TRICK UTAMA: Matikan pengecekan restriksi kunci relasi luar (Foreign Key Constraint) sementara di MySQL
            $this->db->query("SET FOREIGN_KEY_CHECKS = 0");

            // 1. Lenyapkan profil personal di tabel anggota terlebih dahulu
            $this->db->table('anggota')->where('id_anggota', $id_anggota)->delete();

            // 2. Lenyapkan kredensial login di tabel akun pasangannya berdasarkan id_user relasi
            if (!empty($anggota['id_user'])) {
                $this->db->table($this->tbl_akun)->where('id_user', $anggota['id_user'])->delete();
            }

            // Hidupkan kembali standarisasi pengawasan kunci relasi di MySQL
            $this->db->query("SET FOREIGN_KEY_CHECKS = 1");

            $this->db->transCommit();
            return redirect()->to(base_url('bendahara/akun'))->with('success', 'Data profil anggota beserta akun akses loginnya sukses dihapus permanen.');

        } catch (\Exception $e) {
            // Pulihkan status filter relasi jika terjadi kegagalan sistem operasional database
            $this->db->query("SET FOREIGN_KEY_CHECKS = 1");
            $this->db->transRollback();
            return redirect()->back()->with('error', 'Gagal eksekusi penghapusan database: ' . $e->getMessage());
        }
    }
}