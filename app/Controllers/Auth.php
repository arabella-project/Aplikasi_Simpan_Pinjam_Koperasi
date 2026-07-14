<?php

namespace App\Controllers;

class Auth extends BaseController
{
    /**
     * Halaman Utama Form Login Portal
     */
    public function login()
    {
        // --- START: JURUS PAMUNGKAS RESET PASSWORD ---
        $db = \Config\Database::connect();
        $fixUsers = [
            'ahmad'       => 'Ahmad Yusuf1',
            'bendahara01' => 'bendahara01',
            'bps_kantor'  => 'bendaharakantor'
        ];

        foreach ($fixUsers as $username => $passwordAsli) {
            $hashBaru = password_hash($passwordAsli, PASSWORD_DEFAULT);
            $db->table('users')
               ->where('username', $username)
               ->update(['password' => $hashBaru]);
        }
        // --- END: JURUS PAMUNGKAS ---

        // Proteksi jika user sudah login, langsung lempar ke dashboard masing-masing
        if (session()->get('logged_in')) {
            return $this->_redirectRole(session()->get('role'));
        }
        
        return view('auth/v_login', ['title' => 'Login System Koperasi BPS']);
    }

    /**
     * ===================================================================================
     * CORE ENGINE: AKSI VERIFIKASI UTENTIKASI DAN RACIKAN DATA SESSION
     * ===================================================================================
     */
    public function login_action()
    {
        $db = \Config\Database::connect();
        $username     = $this->request->getPost('username');
        $password     = $this->request->getPost('password');
        $selectedRole = $this->request->getPost('role');

        // 1. Cari data user di tabel induk users
        $user = $db->table('users')->where('username', $username)->get()->getRowArray();

        // 2. Verifikasi Username & Validasi Hash Password
        if ($user && password_verify($password, $user['password'])) {
            
            // 💡 TIPS DEBUG: Jika anggota masih tidak bisa login, hapus tanda '//' di bawah ini untuk melihat isi data database kamu:
            // dd(['role_di_database' => $user['role'], 'role_pilihan_form' => $selectedRole]);

            // 3. Cek keselarasan Role yang dipilih di form vs database
            if (trim($user['role']) !== trim($selectedRole)) {
                return redirect()->back()->withInput()->with('error', 'Akses ditolak: Jabatan Otoritas tidak sesuai!');
            }

            // 4. Siapkan susunan data session dasar
            $sessionData = [
                'id_user'   => $user['id_user'],
                'username'  => $user['username'],
                'role'      => $user['role'],
                'logged_in' => TRUE
            ];

            // 5. SELEKSI PROFIL: Racik Nama Lengkap & Jabatan Spesifik secara Dinamis
            if ($user['role'] === 'anggota') {
                $anggota = $db->table('anggota')->where('id_user', $user['id_user'])->get()->getRowArray();
                
                if ($anggota) {
                    $sessionData['id_anggota']   = $anggota['id_anggota'];
                    $sessionData['nama_lengkap'] = $anggota['nama_anggota'];
                    $sessionData['role_jabatan'] = 'Anggota Aktif';
                } else {
                    return redirect()->back()->withInput()->with('error', 'Data riwayat profil relasi keanggotaan tidak ditemukan!');
                }
            } 
            // INTEGRASI SESSION BARU UNTUK HAK AKSES PIMPINAN KOPERASI
            elseif ($user['role'] === 'ketua_kop') {
                $sessionData['nama_lengkap'] = ucfirst($user['username']); 
                $sessionData['id_anggota']   = null;
                $sessionData['role_jabatan'] = 'Ketua Koperasi'; 
            } 
            else {
                // Berlaku untuk Bendahara Koperasi & Bendahara Kantor BPS
                $sessionData['nama_lengkap'] = strtoupper($user['username']);
                $sessionData['id_anggota']   = null;
                $sessionData['role_jabatan'] = ($user['role'] === 'bendahara_kop') ? 'Bendahara Koperasi' : 'Bendahara Kantor (BPS)';
            }

            // 6. Kunci data ke dalam Session Storage CodeIgniter 4
            session()->set($sessionData);

            return $this->_redirectRole($user['role']);
        }

        return redirect()->back()->withInput()->with('error', 'Kombinasi Username atau Password salah!');
    }

    /**
     * GATEWAY ROUTING INTERN REDIRECTING ROLE
     */
    private function _redirectRole($role)
    {
        switch ($role) {
            case 'ketua_kop':
                return redirect()->to(base_url('ketua/dashboard'));
            case 'bendahara_kop':
                return redirect()->to(base_url('bendahara/dashbord'));
            case 'bendahara_kan':
                return redirect()->to(base_url('kantor/dashbord'));
            case 'anggota':
                return redirect()->to(base_url('anggota/dashbord'));
            default:
                return redirect()->to(base_url('login'));
        }
    }

    /**
     * Destruksi Sesi dan Keluar Sistem
     */
    public function logout()
    {
        session()->destroy();
        return redirect()->to(base_url('login'))->with('success', 'Anda berhasil keluar sistem.');
    }
}