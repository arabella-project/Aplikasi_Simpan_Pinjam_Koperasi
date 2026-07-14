<?php

namespace App\Controllers\KetuaKoperasi;

use App\Controllers\BaseController;

class Pengajuan extends BaseController
{
    protected $db;
    protected $sessionUser;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
        $this->sessionUser = session()->get('id_user') ?? 1; // ID verifikator dari session
    }

    /**
     * ===================================================================================
     * MODUL 1: OTORISASI PINJAMAN PEGAWAI
     * ===================================================================================
     */
    public function index()
    {
        $semua_pengajuan = $this->db->table('pengajuan_pinjaman')
                                    ->select('pengajuan_pinjaman.*, anggota.nama_anggota')
                                    ->join('anggota', 'anggota.id_anggota = pengajuan_pinjaman.id_anggota', 'left')
                                    ->orderBy('pengajuan_pinjaman.nilai_saw', 'DESC')
                                    ->get()->getResultArray();

        // Hitung real-time vote dari tabel vote_pengajuan Anda
        foreach ($semua_pengajuan as &$p) {
            $p['vote_setuju'] = $this->db->table('vote_pengajuan')->where(['id_pengajuan' => $p['id_pengajuan'], 'kategori' => 'pinjaman', 'pilihan' => 'setuju'])->countAllResults();
            $p['vote_tolak']  = $this->db->table('vote_pengajuan')->where(['id_pengajuan' => $p['id_pengajuan'], 'kategori' => 'pinjaman', 'pilihan' => 'tolak'])->countAllResults();
            
            $cekUser = $this->db->table('vote_pengajuan')->where(['id_pengajuan' => $p['id_pengajuan'], 'kategori' => 'pinjaman', 'id_user_verifikator' => $this->sessionUser])->get()->getRow();
            $p['user_sudah_vote'] = $cekUser ? true : false;
        }

        return view('ketua_koperasi/v_persetujuan_pinjaman', [
            'title'   => 'Persetujuan Pinjaman | Ketua Koperasi',
            'antrean' => $semua_pengajuan
        ]);
    }

    public function vote_pinjaman($id, $aksi)
    {
        $pilihan = ($aksi === 'setuju') ? 'setuju' : 'tolak';

        $sudahVote = $this->db->table('vote_pengajuan')->where([
            'id_pengajuan'        => $id,
            'id_user_verifikator' => $this->sessionUser,
            'kategori'            => 'pinjaman'
        ])->get()->getRow();

        if ($sudahVote) {
            return redirect()->to(base_url('ketua/pengajuan'))->with('success', 'Anda sudah menyalurkan suara untuk berkas pinjaman ini.');
        }

        $this->db->table('vote_pengajuan')->insert([
            'id_pengajuan'        => $id,
            'id_user_verifikator' => $this->sessionUser,
            'pilihan'             => $pilihan,
            'kategori'            => 'pinjaman'
        ]);

        $total_setuju = $this->db->table('vote_pengajuan')->where(['id_pengajuan' => $id, 'kategori' => 'pinjaman', 'pilihan' => 'setuju'])->countAllResults();
        $total_tolak  = $this->db->table('vote_pengajuan')->where(['id_pengajuan' => $id, 'kategori' => 'pinjaman', 'pilihan' => 'tolak'])->countAllResults();

        if (($total_setuju + $total_tolak) >= 3) {
            $status_akhir = ($total_setuju >= 2) ? 'disetujui' : 'ditolak';
            $this->db->table('pengajuan_pinjaman')->where('id_pengajuan', $id)->update(['status_pengajuan' => $status_akhir]);
            return redirect()->to(base_url('ketua/pengajuan'))->with('success', 'Validasi Lengkap! Berkas resmi dinyatakan ' . strtoupper($status_akhir) . ' berdasarkan hasil vote.');
        }

        return redirect()->to(base_url('ketua/pengajuan'))->with('success', 'Suara Anda berhasil dikirim ke dalam sistem board.');
    }

    /**
     * ===================================================================================
     * MODUL 2: OTORISASI PENARIKAN DANA SIMPANAN
     * ===================================================================================
     */
    public function penarikan()
    {
        $semua_penarikan = $this->db->table('pengajuan_penarikan')
                                    ->select('pengajuan_penarikan.*, anggota.nama_anggota')
                                    ->join('anggota', 'anggota.id_anggota = pengajuan_penarikan.id_anggota', 'left')
                                    ->whereIn('pengajuan_penarikan.status_penarikan', ['disetujui', 'ditransfer', 'ditolak'])
                                    ->orderBy('pengajuan_penarikan.id_penarikan', 'DESC')
                                    ->get()->getResultArray();

        foreach ($semua_penarikan as &$p) {
            if ($p['status_penarikan'] === 'disetujui') {
                $p['status_internal'] = 'proses'; 
            } else {
                $p['status_internal'] = $p['status_penarikan'];
            }

            $p['vote_setuju'] = $this->db->table('vote_pengajuan')->where(['id_pengajuan' => $p['id_penarikan'], 'kategori' => 'penarikan', 'pilihan' => 'setuju'])->countAllResults();
            $p['vote_tolak']  = $this->db->table('vote_pengajuan')->where(['id_pengajuan' => $p['id_penarikan'], 'kategori' => 'penarikan', 'pilihan' => 'tolak'])->countAllResults();
            
            $cekUser = $this->db->table('vote_pengajuan')->where(['id_pengajuan' => $p['id_penarikan'], 'kategori' => 'penarikan', 'id_user_verifikator' => $this->sessionUser])->get()->getRow();
            $p['user_sudah_vote'] = $cekUser ? true : false;
        }

        return view('ketua_koperasi/v_persetujuan_penarikan', [
            'title'   => 'Otorisasi Berkas Penarikan Dana Simpanan',
            'antrean' => $semua_penarikan
        ]);
    }

    public function vote_penarikan($id, $aksi)
    {
        $pilihan = ($aksi === 'setuju') ? 'setuju' : 'tolak';

        $sudahVote = $this->db->table('vote_pengajuan')->where([
            'id_pengajuan'        => $id,
            'id_user_verifikator' => $this->sessionUser,
            'kategori'            => 'penarikan'
        ])->get()->getRow();

        if ($sudahVote) {
            return redirect()->to(base_url('ketua/penarikan'))->with('success', 'Anda sudah memberikan suara untuk berkas penarikan ini.');
        }

        $this->db->table('vote_pengajuan')->insert([
            'id_pengajuan'        => $id,
            'id_user_verifikator' => $this->sessionUser,
            'pilihan'             => $pilihan,
            'kategori'            => 'penarikan'
        ]);

        $total_setuju = $this->db->table('vote_pengajuan')->where(['id_pengajuan' => $id, 'kategori' => 'penarikan', 'pilihan' => 'setuju'])->countAllResults();
        $total_tolak  = $this->db->table('vote_pengajuan')->where(['id_pengajuan' => $id, 'kategori' => 'penarikan', 'pilihan' => 'tolak'])->countAllResults();

        if (($total_setuju + $total_tolak) >= 3) {
            $status_akhir = ($total_setuju >= 2) ? 'ditransfer' : 'ditolak';
            
            $updateData = ['status_penarikan' => $status_akhir];
            if ($status_akhir === 'ditolak') {
                $updateData['alasan_penolakan'] = 'Ditolak berdasarkan kesepakatan voting terbanyak tim verifikator pimpinan.';
            }

            $this->db->table('pengajuan_penarikan')->where('id_penarikan', $id)->update($updateData);
            return redirect()->to(base_url('ketua/penarikan'))->with('success', 'Otorisasi Lengkap! Keputusan penarikan final diubah menjadi ' . strtoupper($status_akhir));
        }

        return redirect()->to(base_url('ketua/penarikan'))->with('success', 'Suara voting berhasil direkam ke dalam database sistem.');
    }
}