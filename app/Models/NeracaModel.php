<?php

namespace App\Models;

use CodeIgniter\Model;

class NeracaModel extends Model
{
    protected $table            = 'neraca';
    protected $primaryKey       = 'id_neraca';
    protected $allowedFields    = [
        'tahun', 'kas_pinjaman', 'bank', 'piutang_simpan_pinjam', 
        'total_aktiva_lancar', 'simpanan_pokok_total', 'simpanan_wajib_total', 
        'dana_sosial', 'total_pasiva'
    ];

    public function hitungNeraca()
    {
        $db = \Config\Database::connect();

        // 1. DATA SIMPANAN (Fisik Saldo Berjalan Saat Ini)
        $simpanan = $db->table('simpanan')
                       ->selectSum('simpanan_pokok', 'pokok')
                       ->selectSum('simpanan_wajib', 'wajib')
                       ->selectSum('simpanan_sukarela', 'sukarela')
                       ->get()->getRowArray();
        
        $simpanan_pokok_total = (float)($simpanan['pokok'] ?? 0);
        $simpanan_wajib_total = (float)($simpanan['wajib'] ?? 0);
        $simpanan_sukarela    = (float)($simpanan['sukarela'] ?? 0);
        $total_seluruh_simpanan = $simpanan_pokok_total + $simpanan_wajib_total + $simpanan_sukarela;

        // 2. DETEKSI AMAN NAMA KOLOM NOMINAL PINJAMAN (TABEL: pinjaman)
        $kolom_nominal = '';
        if ($db->tableExists('pinjaman')) {
            $fields_pinjam = $db->getFieldNames('pinjaman');
            if (in_array('jumlah_pinjaman', $fields_pinjam)) {
                $kolom_nominal = 'jumlah_pinjaman';
            } elseif (in_array('nominal_pinjaman', $fields_pinjam)) {
                $kolom_nominal = 'nominal_pinjaman';
            } elseif (in_array('jumlah_pengajuan', $fields_pinjam)) {
                $kolom_nominal = 'jumlah_pengajuan';
            } else {
                // Fallback jika tidak ada yang cocok, ambil field ke-3 dari database
                $kolom_nominal = $fields_pinjam[2] ?? '';
            }
        }

        $total_pencairan_pinjaman = 0;
        if (!empty($kolom_nominal)) {
            $total_pencairan_pinjaman = $db->table('pinjaman')
                                           ->selectSum($kolom_nominal, 'total')
                                           ->where('status_pinjaman', 'aktif')
                                           ->get()->getRow()->total ?? 0;
        }

        // 3. DETEKSI AMAN NAMA KOLOM ANGSURAN & BUNGA (TABEL: pembayaran_pinjaman)
        $kolom_angsuran = '';
        $kolom_bunga = '';

        if ($db->tableExists('pembayaran_pinjaman')) {
            $fields_bayar = $db->getFieldNames('pembayaran_pinjaman');
            
            // Cari kolom untuk pokok angsuran
            if (in_array('angsuran_pokok', $fields_bayar)) {
                $kolom_angsuran = 'angsuran_pokok';
            } elseif (in_array('pokok_dibayar', $fields_bayar)) {
                $kolom_angsuran = 'pokok_dibayar';
            } else {
                foreach ($fields_bayar as $f) {
                    if (strpos($f, 'pokok') !== false || strpos($f, 'angsuran') !== false) {
                        $kolom_angsuran = $f;
                        break;
                    }
                }
            }

            // Cari kolom untuk bunga angsuran (Mencegah Error 1054)
            if (in_array('bunga_angsuran', $fields_bayar)) {
                $kolom_bunga = 'bunga_angsuran';
            } elseif (in_array('bunga_dibayar', $fields_bayar)) {
                $kolom_bunga = 'bunga_dibayar';
            } elseif (in_array('jasa_angsuran', $fields_bayar)) {
                $kolom_bunga = 'jasa_angsuran';
            } else {
                foreach ($fields_bayar as $f) {
                    if (strpos($f, 'bunga') !== false || strpos($f, 'jasa') !== false || strpos($f, 'bagi_hasil') !== false) {
                        $kolom_bunga = $f;
                        break;
                    }
                }
            }
        }

        // Hitung akumulasi pokok angsuran masuk
        $total_angsuran_pokok_masuk = 0;
        if (!empty($kolom_angsuran)) {
            $total_angsuran_pokok_masuk = $db->table('pembayaran_pinjaman')
                                             ->selectSum($kolom_angsuran, 'total')
                                             ->get()->getRow()->total ?? 0;
        }

        // Piutang simpan pinjam riil yang masih berada di tangan anggota
        $piutang_simpan_pinjam = max(0, $total_pencairan_pinjaman - $total_angsuran_pokok_masuk);

        // 4. HITUNG DATA PENDAPATAN OPERASIONAL BUNGAN KOPERASI
        $total_pendapatan_bunga = 0;
        if (!empty($kolom_bunga)) {
            $total_pendapatan_bunga = $db->table('pembayaran_pinjaman')
                                         ->selectSum($kolom_bunga, 'total')
                                         ->get()->getRow()->total ?? 0;
        }
        
        $total_pendapatan_admin = 0;
        if ($db->tableExists('pinjaman')) {
            $fields_pinjam = $db->getFieldNames('pinjaman');
            if (in_array('biaya_admin', $fields_pinjam)) {
                $total_pendapatan_admin = $db->table('pinjaman')->selectSum('biaya_admin', 'total')->get()->getRow()->total ?? 0;
            }
        }
        $total_pendapatan = $total_pendapatan_bunga + $total_pendapatan_admin;

        // HITUNG DATA BEBAN OPERASIONAL KOPERASI
        $total_beban_pengeluaran = 0;
        if ($db->tableExists('pengeluaran')) {
            $fields_keluar = $db->getFieldNames('pengeluaran');
            $kolom_nominal_keluar = in_array('nominal', $fields_keluar) ? 'nominal' : (in_array('jumlah', $fields_keluar) ? 'jumlah' : '');
            if (!empty($kolom_nominal_keluar)) {
                $total_beban_pengeluaran = $db->table('pengeluaran')->selectSum($kolom_nominal_keluar, 'total')->get()->getRow()->total ?? 0;
            }
        }

        // 5. FORMULA LOGIKA ARUS KAS GLOBAL
        $total_kas_masuk  = $total_seluruh_simpanan + $total_angsuran_pokok_masuk + $total_pendapatan;
        $total_kas_keluar = $total_pencairan_pinjaman + $total_beban_pengeluaran;
        $kas_bersih       = $total_kas_masuk - $total_kas_keluar;

        // Distribusi pembagian porsi ke field neraca
        $kas_pinjaman = $kas_bersih * 0.40; 
        $bank         = $kas_bersih * 0.60; 

        $total_aktiva_lancar = $kas_pinjaman + $bank + $piutang_simpan_pinjam;

        $shu_berjalan = $total_pendapatan - $total_beban_pengeluaran;
        $dana_sosial  = $shu_berjalan * 0.10; 
        $sisa_ekuitas = $shu_berjalan - $dana_sosial;

        $total_pasiva = $total_seluruh_simpanan + $dana_sosial + $sisa_ekuitas;

        return [
            'tahun'                 => date('Y'),
            'kas_pinjaman'          => $kas_pinjaman,
            'bank'                  => $bank,
            'piutang_simpan_pinjam' => $piutang_simpan_pinjam,
            'total_aktiva_lancar'   => $total_aktiva_lancar,
            'simpanan_pokok_total'  => $simpanan_pokok_total,
            'simpanan_wajib_total'  => $simpanan_wajib_total,
            'simpanan_sukarela'     => $simpanan_sukarela,
            'dana_sosial'           => $dana_sosial,
            'shu_berjalan'          => $shu_berjalan,
            'total_pasiva'          => $total_pasiva
        ];
    }
}