<?php

namespace App\Controllers\BendaharaKoperasi;

use App\Controllers\BaseController;

class TabelPengajuanPotonganGaji extends BaseController
{
    protected $db;

    public function __construct() {
        $this->db = \Config\Database::connect();
    }

    /**
     * TAMPILAN UTAMA
     */
    public function index()
    {
        // Menghitung berapa banyak data yang masih berstatus 'draft' untuk tombol ajukan
        $cek_draft = $this->db->table('potongan_gaji')
                             ->where('status_potongan', 'draft')
                             ->countAllResults();

        $data = [
            'title'         => 'Pengajuan Potongan Gaji Anggota',
            'list_potongan' => $this->db->query("
                SELECT p.*, a.nama_anggota
                FROM potongan_gaji p
                JOIN anggota a ON a.id_anggota = p.id_anggota
                ORDER BY p.tgl_pengajuan DESC
            ")->getResultArray(),
            'jumlah_draft' => $cek_draft 
        ];
        
        return view('bendahara_koperasi/v_pemotogan_gaji', $data);
    }

    /**
     * SINKRONISASI OTOMATIS: PINJAMAN -> POTONGAN GAJI
     */
    public function generate()
    {
        $bulan_tahun = date('m-Y'); 

        // Mengambil angsuran dari pinjaman yang statusnya 'aktif'
        $pinjaman_aktif = $this->db->query("
            SELECT id_anggota, SUM(angsuran_perbulan + jasa_perbulan) as total_angsuran
            FROM pinjaman 
            WHERE status_pinjaman = 'aktif' 
            GROUP BY id_anggota
        ")->getResultArray();

        if (empty($pinjaman_aktif)) {
            return redirect()->back()->with('error', 'Tidak ada pinjaman aktif untuk diproses bulan ini.');
        }

        $this->db->transBegin();

        foreach ($pinjaman_aktif as $p) {
            $total_potong = $p['total_angsuran'];
            
            $cek = $this->db->table('potongan_gaji')
                            ->where([
                                'id_anggota'  => $p['id_anggota'], 
                                'bulan_tahun' => $bulan_tahun
                            ])->get()->getRowArray();

            if (!$cek) {
                // Insert data baru sebagai Draft
                $this->db->table('potongan_gaji')->insert([
                    'bulan_tahun'       => $bulan_tahun,
                    'id_anggota'        => $p['id_anggota'],
                    'total_potongan'    => $total_potong,
                    'metode_pembayaran' => 'potong_gaji',
                    'bank_tujuan'       => 'BRI',
                    'status_potongan'   => 'draft',
                    'tgl_pengajuan'     => date('Y-m-d H:i:s')
                ]);
            } else if ($cek['status_potongan'] == 'draft') {
                // Update jika nominal berubah selama masih draft
                $this->db->table('potongan_gaji')
                         ->where('id_potongan', $cek['id_potongan'])
                         ->update(['total_potongan' => $total_potong]);
            }
        }

        $this->db->transCommit();
        return redirect()->back()->with('success', 'Data angsuran berhasil ditarik. Silakan klik tombol "Ajukan ke Kantor" agar diproses Bendahara Kantor.');
    }

    /**
     * PROSES PENGAJUAN KE BENDAHARA KANTOR
     */
    public function ajukan_semua()
    {
        // Mengubah semua yang statusnya 'draft' menjadi 'diajukan'
        // Data dengan status 'diajukan' inilah yang akan tampil di dashboard Bendahara Kantor
        $update = $this->db->table('potongan_gaji')
                           ->where('status_potongan', 'draft')
                           ->update([
                               'status_potongan' => 'diajukan',
                               'tgl_pengajuan'   => date('Y-m-d H:i:s')
                           ]);

        if ($update) {
            return redirect()->back()->with('success', 'Berhasil! Data telah dikirim ke Bendahara Kantor untuk divalidasi.');
        }

        return redirect()->back()->with('error', 'Gagal mengajukan data.');
    }
}