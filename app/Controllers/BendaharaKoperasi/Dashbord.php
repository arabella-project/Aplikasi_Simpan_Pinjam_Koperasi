<?php

namespace App\Controllers\BendaharaKoperasi;

use App\Controllers\BaseController;

class Dashbord extends BaseController
{
    public function index()
    {
        // Inisialisasi koneksi query builder database
        $db = \Config\Database::connect();

        // 🟢 AMBIL DATA TEMPORARY KLIK DARI SESSION BENDAHARA
        $berkas_diklik_bendahara = session()->get('bendahara_diklik_penarikan') ?? [0];

        // 1. Ambil total nominal keuangan secara langsung menggunakan Query Builder sesuai skema DB asli
        
        // Menghitung Total Simpanan (Akumulasi Pokok + Wajib + Sukarela dari tabel simpanan)
        $totalSimpanan = $db->table('simpanan')->select('SUM(simpanan_pokok + simpanan_wajib + simpanan_sukarela) as total')->get()->getRow()->total ?? 0;
        
        // Menghitung Total Pinjaman (Menggunakan tabel 'pinjaman' dan kolom 'jumlah_total' sesuai hasil DESC Anda)
        $totalPinjaman = $db->table('pinjaman')->selectSum('jumlah_total')->get()->getRow()->jumlah_total ?? 0;
        
        // Menghitung Total Pengeluaran (Menggunakan tabel 'pengeluaran' dan kolom 'jumlah')
        $totalPengeluaran = $db->table('pengeluaran')->selectSum('jumlah')->get()->getRow()->jumlah ?? 0;
        
        // Hitung Grand Total untuk pembagi persentase chart
        $grandTotal = $totalSimpanan + $totalPinjaman + $totalPengeluaran;

        // 2. Hitung persentase real-time untuk Ringkasan Keuangan (Donut Chart)
        $persen_simpanan    = $grandTotal > 0 ? round(($totalSimpanan / $grandTotal) * 100) : 0;
        $persen_pinjaman    = $grandTotal > 0 ? round(($totalPinjaman / $grandTotal) * 100) : 0;
        $persen_pengeluaran = $grandTotal > 0 ? round(($totalPengeluaran / $grandTotal) * 100) : 0;

        // 3. Logika Saldo BRI (Simpanan + Angsuran - Pengeluaran)
        $simBRI = $db->table('transaksi_simpanan')->selectSum('jumlah')->where('bank', 'BRI')->get()->getRow()->jumlah ?? 0;
        $angBRI = $db->table('pembayaran_pinjaman')->selectSum('jumlah_bayar')->where('bank', 'BRI')->get()->getRow()->jumlah_bayar ?? 0;
        $outBRI = $db->table('pengeluaran')->selectSum('jumlah')->where('bank', 'BRI')->get()->getRow()->jumlah ?? 0;
        
        // 4. Logika Saldo BSI
        $simBSI = $db->table('transaksi_simpanan')->selectSum('jumlah')->where('bank', 'BSI')->get()->getRow()->jumlah ?? 0;
        $angBSI = $db->table('pembayaran_pinjaman')->selectSum('jumlah_bayar')->where('bank', 'BSI')->get()->getRow()->jumlah_bayar ?? 0;
        $outBSI = $db->table('pengeluaran')->selectSum('jumlah')->where('bank', 'BSI')->get()->getRow()->jumlah ?? 0;

        // 5. Ambil data list detail permohonan penarikan dana sesuai kolom asli DB
        // 🛠️ INTEGRASI: Ditambahkan whereNotIn untuk memfilter berkas yang sudah diklik bendahara
        $list_penarikan = $db->table('pengajuan_penarikan')
                             ->select('pengajuan_penarikan.id_penarikan, pengajuan_penarikan.jumlah_ditarik, pengajuan_penarikan.tgl_pengajuan, anggota.nama_anggota')
                             ->join('anggota', 'anggota.id_anggota = pengajuan_penarikan.id_anggota')
                             ->where('status_penarikan', 'pending')
                             ->whereNotIn('pengajuan_penarikan.id_penarikan', $berkas_diklik_bendahara)
                             ->orderBy('pengajuan_penarikan.id_penarikan', 'DESC')
                             ->limit(5)
                             ->get()
                             ->getResultArray();

        // Hitung jumlah total antrean penarikan dana masuk yang tersisa untuk ditampilkan di badge info
        $penarikan_masuk = count($list_penarikan);

        // 6. Logika Otomatis Arus Kas Bulanan (6 Bulan Terakhir)
        $months = [];
        $pemasukan_data = [];
        $pengeluaran_data = [];

        for ($i = 5; $i >= 0; $i--) {
            $date = date('Y-m', strtotime("-$i months"));
            $monthName = date('M', strtotime("-$i months"));
            $months[] = $monthName;

            // Pemasukan dari transaksi_simpanan
            $pemasukan_sim = $db->table('transaksi_simpanan')
                                ->selectSum('jumlah')
                                ->like('tgl_transaksi', $date, 'after')
                                ->get()->getRow()->jumlah ?? 0;

            // Pemasukan dari pembayaran_pinjaman
            $pemasukan_ang = $db->table('pembayaran_pinjaman')
                                ->selectSum('jumlah_bayar')
                                ->like('tgl_bayar', $date, 'after')
                                ->get()->getRow()->jumlah_bayar ?? 0;

            $pemasukan_data[] = (float)($pemasukan_sim + $pemasukan_ang);

            // Pengeluaran dari tabel pengeluaran
            $pengeluaran_data[] = (float)($db->table('pengeluaran')
                                              ->selectSum('jumlah')
                                              ->like('tgl_pengeluaran', $date, 'after')
                                              ->get()->getRow()->jumlah ?? 0);
        }

        $data = [
            'title'             => 'Dashboard Bendahara | Koperasi BPS',
            'saldo_bri'         => ($simBRI + $angBRI) - $outBRI,
            'saldo_bsi'         => ($simBSI + $angBSI) - $outBSI,
            'total_simpanan'    => $totalSimpanan,
            'pengajuan_pending' => $db->table('pengajuan_pinjaman')->where('status_pengajuan', 'pending')->countAllResults(),
            'penarikan_masuk'   => $penarikan_masuk,
            'list_penarikan'    => $list_penarikan,
            'list_pengajuan'    => $db->table('pengajuan_pinjaman')
                                    ->join('anggota', 'anggota.id_anggota = pengajuan_pinjaman.id_anggota')
                                    ->where('status_pengajuan', 'pending')
                                    ->orderBy('tgl_pengajuan', 'DESC')
                                    ->limit(5)
                                    ->get()
                                    ->getResultArray(),
            
            'chart_months'      => json_encode($months),
            'chart_pemasukan'   => json_encode($pemasukan_data),
            'chart_pengeluaran' => json_encode($pengeluaran_data),

            'persen_simpanan'    => $persen_simpanan,
            'persen_pinjaman'    => $persen_pinjaman,
            'persen_pengeluaran' => $persen_pengeluaran
        ];

        return view('bendahara_koperasi/v_dashboard', $data);
    }

    // 🛠️ INTERSEPTOR 1: Fungsi pemicu saat salah satu item permohonan anggota diklik
    public function LogikaBukaPenarikan($id)
    {
        $berkas = session()->get('bendahara_diklik_penarikan') ?? [];
        if (!in_array($id, $berkas)) { 
            $berkas[] = $id; 
        }
        session()->set('bendahara_diklik_penarikan', $berkas);
        
        return redirect()->to(base_url('bendahara/penarikan')); 
    }

    // 🛠️ INTERSEPTOR 2: Fungsi pemicu saat tombol bawah "Proses Pencairan" diklik massal
    public function LogikaBukaSemuaPenarikan() 
    {
        $db = \Config\Database::connect();
        $semua = $db->table('pengajuan_penarikan')->where('status_penarikan', 'pending')->get()->getResultArray();
        
        $berkas = session()->get('bendahara_diklik_penarikan') ?? [];
        foreach ($semua as $s) {
            if (!in_array($s['id_penarikan'], $berkas)) { 
                $berkas[] = $s['id_penarikan']; 
            }
        }
        session()->set('bendahara_diklik_penarikan', $berkas);
        
        return redirect()->to(base_url('bendahara/penarikan'));
    }
}