<?php

namespace App\Controllers\KetuaKoperasi;
use App\Controllers\BaseController;

class Dashboard extends BaseController
{
    protected $db;
    protected $sessionUser;

    public function __construct() {
        $this->db = \Config\Database::connect();
        $this->sessionUser = session()->get('id_user') ?? 1; 
    }

    public function index()
    {
        // Ambil data temporary klik dari session pimpinan yang aktif
        $berkas_diklik_pinjaman = session()->get('berkas_diklik_pinjaman') ?? [0];
        $berkas_diklik_penarikan = session()->get('berkas_diklik_penarikan') ?? [0];

        // 1. Total Dana Simpanan Akumulatif (Pokok + Wajib + Sukarela)
        $query_simpanan = $this->db->table('simpanan')
                                   ->select('SUM(simpanan_pokok + simpanan_wajib + simpanan_sukarela) as total')
                                   ->get()->getRowArray();
        $total_simpanan = $query_simpanan['total'] ?? 0;
        
        // 2. Total Angsuran Masuk Bulan Ini
        $query_angsuran = $this->db->table('pembayaran_pinjaman')
                                   ->like('tgl_bayar', date('Y-m'))
                                   ->selectSum('jumlah_bayar', 'total')
                                   ->get()->getRowArray();
        $angsuran_bulan_ini = $query_angsuran['total'] ?? 0;
        
        // 3. Total Anggota Koperasi Aktif BPS
        $total_anggota = $this->db->table('anggota')->countAllResults();

        // 4. Ranking Prioritas SAW (Top 5 Widget Progress)
        $ranking_saw = $this->db->table('pengajuan_pinjaman')
                                ->select('pengajuan_pinjaman.*, anggota.nama_anggota')
                                ->join('anggota', 'anggota.id_anggota = pengajuan_pinjaman.id_anggota', 'left')
                                ->where('pengajuan_pinjaman.status_pengajuan', 'proses')
                                ->whereNotIn('pengajuan_pinjaman.id_pengajuan', $berkas_diklik_pinjaman)
                                ->orderBy('pengajuan_pinjaman.nilai_saw', 'DESC')
                                ->limit(5)
                                ->get()->getResultArray();

        // 5. Tabel Manifes Antrean Pengajuan Berdasarkan Peringkat SAW (Bawah Kiri)
        $list_antrean_pinjaman = $this->db->table('pengajuan_pinjaman')
                                          ->select('pengajuan_pinjaman.*, anggota.nama_anggota')
                                          ->join('anggota', 'anggota.id_anggota = pengajuan_pinjaman.id_anggota', 'left')
                                          ->where('pengajuan_pinjaman.status_pengajuan', 'proses')
                                          ->whereNotIn('pengajuan_pinjaman.id_pengajuan', $berkas_diklik_pinjaman)
                                          ->orderBy('pengajuan_pinjaman.nilai_saw', 'DESC')
                                          ->get()->getResultArray();

        // 6. Panel Penarikan Dana Menunggu Validasi (Bawah Kanan)
        $tbl_penarikan = $this->db->tableExists('pengajuan_penarikan') ? 'pengajuan_penarikan' : ($this->db->tableExists('penarikan') ? 'penarikan' : 'penarikan_simpanan');
        $list_antrean_penarikan = [];
        
        if (!empty($tbl_penarikan) && $this->db->tableExists($tbl_penarikan)) {
            $list_antrean_penarikan = $this->db->table($tbl_penarikan)
                                               ->select($tbl_penarikan . '.*, anggota.nama_anggota')
                                               ->join('anggota', 'anggota.id_anggota = ' . $tbl_penarikan . '.id_anggota', 'left')
                                               ->where($tbl_penarikan . '.status_penarikan', 'disetujui')
                                               ->whereNotIn($tbl_penarikan . '.id_penarikan', $berkas_diklik_penarikan)
                                               ->orderBy($tbl_penarikan . '.id_penarikan', 'DESC')
                                               ->get()->getResultArray();
        }

        return view('ketua_koperasi/v_dashboard', [
            'title'                  => 'Dashboard Ketua Koperasi | BPS Sumsel',
            'total_simpanan'         => $total_simpanan,
            'angsuran_bulan_ini'     => $angsuran_bulan_ini,
            'total_anggota'          => $total_anggota,
            'ranking_saw'            => $ranking_saw,
            'list_antrean_pinjaman'  => $list_antrean_pinjaman,
            'list_antrean_penarikan' => $list_antrean_penarikan
        ]);
    }

    /**
     * INTERSEPTOR: Kunci Seluruh ID Antrean Pinjaman ke Session
     */
    public function buka_semua_pinjaman() 
    {
        $semua = $this->db->table('pengajuan_pinjaman')->where('status_pengajuan', 'proses')->get()->getResultArray();
        $berkas = session()->get('berkas_diklik_pinjaman') ?? [];
        foreach ($semua as $s) {
            if (!in_array($s['id_pengajuan'], $berkas)) { $berkas[] = $s['id_pengajuan']; }
        }
        session()->set('berkas_diklik_pinjaman', $berkas);
        return redirect()->to(base_url('ketua/pengajuan'));
    }

    /**
     * INTERSEPTOR: Kunci Seluruh ID Antrean Penarikan ke Session (Dinamis Sesuai Database)
     */
    public function buka_semua_penarikan() 
    {
        // 🟢 FIX: Menggunakan deteksi tabel dinamis agar query tidak patah/404
        $tbl_penarikan = $this->db->tableExists('pengajuan_penarikan') ? 'pengajuan_penarikan' : ($this->db->tableExists('penarikan') ? 'penarikan' : 'penarikan_simpanan');
        $berkas = session()->get('berkas_diklik_penarikan') ?? [];
        
        if (!empty($tbl_penarikan) && $this->db->tableExists($tbl_penarikan)) {
            $semua = $this->db->table($tbl_penarikan)->where('status_penarikan', 'disetujui')->get()->getResultArray();
            foreach ($semua as $s) {
                if (!in_array($s['id_penarikan'], $berkas)) { $berkas[] = $s['id_penarikan']; }
            }
        }
        session()->set('berkas_diklik_penarikan', $berkas);
        return redirect()->to(base_url('ketua/penarikan'));
    }

    /**
     * Kunci ID Tunggal Pinjaman ke Session
     */
    public function buka_pinjaman($id)
    {
        $berkas = session()->get('berkas_diklik_pinjaman') ?? [];
        if (!in_array($id, $berkas)) { $berkas[] = $id; }
        session()->set('berkas_diklik_pinjaman', $berkas);
        return redirect()->to(base_url('ketua/pengajuan')); 
    }

    /**
     * Kunci ID Tunggal Penarikan ke Session
     */
    public function buka_penarikan($id)
    {
        $berkas = session()->get('berkas_diklik_penarikan') ?? [];
        if (!in_array($id, $berkas)) { $berkas[] = $id; }
        session()->set('berkas_diklik_penarikan', $berkas);
        return redirect()->to(base_url('ketua/penarikan'));
    }
}