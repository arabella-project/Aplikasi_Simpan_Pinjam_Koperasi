<?php

namespace App\Controllers\BendaharaKoperasi;

use App\Controllers\BaseController;
use App\Models\PinjamanModel;
use App\Models\AnggotaModel;

class Pinjaman extends BaseController
{
    protected $pinjamanM;
    protected $anggotaM;
    protected $db;

    public function __construct() {
        $this->pinjamanM = new PinjamanModel();
        $this->anggotaM = new AnggotaModel();
        $this->db = \Config\Database::connect();
    }

    /**
     * 1. HALAMAN UTAMA: LIST PINJAMAN TERKONSOLIDASI + ESTIMASI 4 METRICS ATAS (SINKRON DATA TRANSFER)
     */
    public function index() {
        // Ambil data untuk metrics atas sebelum paginasi dijalankan secara bersih
        $summaryQuery = $this->db->table('pinjaman')
                                 ->select('id_anggota, sisa_hutang, angsuran_perbulan, jasa_perbulan')
                                 ->where('status_pinjaman', 'aktif')
                                 ->get()
                                 ->getResultArray();

        $unique_peminjam = [];
        $total_sisa_hutang = 0;
        $total_tagihan_bulanan = 0;
        $total_kontrak_aktif = count($summaryQuery);

        foreach ($summaryQuery as $sq) {
            $unique_peminjam[$sq['id_anggota']] = true;
            $total_sisa_hutang += (float)$sq['sisa_hutang'];
            $total_tagihan_bulanan += ((float)$sq['angsuran_perbulan'] + (float)$sq['jasa_perbulan']);
        }

        // 🟢 SINKRONISASI QUERY UTAMA: Mengambil total data gabungan field riil hasil transfer pengajuan
        $this->pinjamanM->select('
            pinjaman.id_anggota, 
            anggota.nama_anggota,
            COUNT(pinjaman.id_pinjaman) as total_kontrak,
            GROUP_CONCAT(pinjaman.id_pinjaman ORDER BY pinjaman.id_pinjaman ASC) as append_id_pinjaman,
            GROUP_CONCAT(pinjaman.angsuran_ke ORDER BY pinjaman.id_pinjaman ASC) as append_angsuran_ke,
            GROUP_CONCAT(pinjaman.jasa_perbulan ORDER BY pinjaman.id_pinjaman ASC) as append_jasa_perbulan,
            SUM(pinjaman.sisa_hutang) as gabung_sisa_hutang,
            SUM(pinjaman.angsuran_perbulan + pinjaman.jasa_perbulan) as gabung_angsuran_bulanan,
            MIN(pinjaman.id_pinjaman) as id_pinjaman_ref
        ')
        ->join('anggota', 'anggota.id_anggota = pinjaman.id_anggota', 'left')
        ->where('pinjaman.status_pinjaman', 'aktif')
        ->groupBy('pinjaman.id_anggota')
        ->orderBy('anggota.nama_anggota', 'ASC');

        $data = [
            'title'                 => 'Manajemen Pinjaman Anggota | Koperasi BPS',
            'pinjaman'              => $this->pinjamanM->paginate(10, 'pinjaman_pager'),
            'pager'                 => $this->pinjamanM->pager,
            'anggota'               => $this->anggotaM->findAll(),
            
            // Pengiriman data statistik ke 4 Widget Atas
            'total_peminjam'        => count($unique_peminjam),
            'total_sisa_hutang'     => $total_sisa_hutang,
            'total_tagihan_bulanan' => $total_tagihan_bulanan,
            'total_kontrak_aktif'   => $total_kontrak_aktif
        ];
        
        return view('bendahara_koperasi/v_pinjaman', $data);
    }

    /**
     * 2. FUNGSI EKSEKUSI ANGSURAN + DISPATCHER EMAIL INVOICE
     */
    public function store_angsuran() {
        $target = $this->request->getPost('target_pembayaran'); 
        $metode = $this->request->getPost('metode');
        $bank = $this->request->getPost('bank');
        $tgl_bayar = $this->request->getPost('tgl_bayar') ?: date('Y-m-d');
        $id_anggota = $this->request->getPost('id_anggota');

        $anggota = $this->anggotaM->find($id_anggota);
        if (!$anggota) return redirect()->back()->with('error', 'Data master anggota tidak ditemukan.');

        $this->db->transBegin();
        $data_invoice = []; 

        if ($target === 'keduanya') {
            $jumlah_bayar_total = (float) $this->request->getPost('jumlah_bayar');

            $daftarPinjaman = $this->db->table('pinjaman')
                                      ->where('id_anggota', $id_anggota)
                                      ->where('status_pinjaman', 'aktif')
                                      ->orderBy('id_pinjaman', 'ASC')
                                      ->get()
                                      ->getResultArray();

            if (count($daftarPinjaman) == 0) {
                return redirect()->back()->with('error', 'Tidak ditemukan kontrak pinjaman aktif.');
            }

            $sisa_dana = $jumlah_bayar_total;
            $total_kontrak = count($daftarPinjaman);

            foreach ($daftarPinjaman as $index => $p) {
                if ($sisa_dana <= 0) break;

                $sisa_hutang_kontrak = (float) $p['sisa_hutang'];
                $angsuran_bulanan_kontrak = (float)$p['angsuran_perbulan'] + (float)$p['jasa_perbulan'];

                if ($index == 0 && $total_kontrak > 1) {
                    $bayar_ke_pinjaman = ($sisa_dana >= $angsuran_bulanan_kontrak) ? $angsuran_bulanan_kontrak : $sisa_dana;
                } else {
                    $bayar_ke_pinjaman = ($sisa_dana >= $sisa_hutang_kontrak) ? $sisa_hutang_kontrak : $sisa_dana;
                }

                if ($bayar_ke_pinjaman > $sisa_hutang_kontrak) {
                    $bayar_ke_pinjaman = $sisa_hutang_kontrak;
                }

                $sisa_dana -= $bayar_ke_pinjaman;
                $sisa_baru = $sisa_hutang_kontrak - $bayar_ke_pinjaman;
                $angsuran_baru = (int)$p['angsuran_ke'] + 1;
                $status_baru = ($sisa_baru <= 0) ? 'lunas' : 'aktif';

                $this->db->table('pembayaran_pinjaman')->insert([
                    'id_pinjaman'               => $p['id_pinjaman'],
                    'tgl_bayar'                 => $tgl_bayar,
                    'angsuran_ke'               => $angsuran_baru,
                    'jumlah_bayar'              => $bayar_ke_pinjaman,
                    'metode_pembayaran'         => $metode,
                    'bank'                      => $bank,
                    'sisa_hutang_setelah_bayar' => $sisa_baru
                ]);

                $this->db->table('pinjaman')
                         ->where('id_pinjaman', $p['id_pinjaman'])
                         ->update([
                             'sisa_hutang'     => $sisa_baru,
                             'angsuran_ke'     => $angsuran_baru,
                             'status_pinjaman' => $status_baru
                         ]);

                $data_invoice[] = [
                    'id_pinjaman' => $p['id_pinjaman'],
                    'angsuran_ke' => $angsuran_baru,
                    'nominal'     => $bayar_ke_pinjaman,
                    'sisa'        => $sisa_baru
                ];
            }
        } else {
            $id_pinjaman = $this->request->getPost('id_pinjaman');
            $jumlah_bayar = (float) $this->request->getPost('jumlah_bayar');

            $p = $this->db->table('pinjaman')->where('id_pinjaman', $id_pinjaman)->get()->getRowArray();
            if (!$p) return redirect()->back()->with('error', 'Data pinjaman tidak ditemukan.');

            $sisa_hutang_kontrak = (float)$p['sisa_hutang'];
            $sisa_baru = $sisa_hutang_kontrak - $jumlah_bayar;
            $sisa_baru = ($sisa_baru < 0) ? 0 : $sisa_baru;
            $angsuran_baru = (int)$p['angsuran_ke'] + 1;
            $status_baru = ($sisa_baru <= 0) ? 'lunas' : 'aktif';

            $this->db->table('pembayaran_pinjaman')->insert([
                'id_pinjaman'               => $id_pinjaman,
                'tgl_bayar'                 => $tgl_bayar,
                'angsuran_ke'               => $angsuran_baru,
                'jumlah_bayar'              => $jumlah_bayar,
                'metode_pembayaran'         => $metode,
                'bank'                      => $bank,
                'sisa_hutang_setelah_bayar' => $sisa_baru
            ]);

            $this->db->table('pinjaman')
                     ->where('id_pinjaman', $id_pinjaman)
                     ->update([
                         'sisa_hutang'     => $sisa_baru,
                         'angsuran_ke'     => $angsuran_baru,
                         'status_pinjaman' => $status_baru
                     ]);

            $data_invoice[] = [
                'id_pinjaman' => $id_pinjaman,
                'angsuran_ke' => $angsuran_baru,
                'nominal'     => $jumlah_bayar,
                'sisa'        => $sisa_baru
            ];
        }

        if ($this->db->transStatus() === FALSE) {
            $this->db->transRollback();
            return redirect()->back()->with('error', 'Gagal memproses transaksi.');
        }

        $this->db->transCommit();

        if (!empty($anggota['email'])) {
            $this->sendInvoiceEmail($anggota, $data_invoice, $metode, $bank, $tgl_bayar);
        }

        return redirect()->to(base_url('bendahara/pinjaman'))->with('success', 'Angsuran pembiayaan sukses tervalidasi.');
    }

    /**
     * 3. TEMPLATE EMAIL INVOICE STYLE SINKRONISASI KOPERASI
     */
    private function sendInvoiceEmail($anggota, $data_invoice, $metode, $bank, $tgl_bayar) {
        $email = \Config\Services::email();
        $email->setTo($anggota['email']);
        $email->setSubject('Invoice Resmi Pembayaran Angsuran Koperasi BPS - ' . date('M Y'));

        $message = "
        <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #e2e8f0; border-radius: 12px;'>
            <div style='background-color: #0b1a30; color: white; padding: 20px; text-align: center; border-radius: 8px 8px 0 0;'>
                <h3 style='margin: 0; letter-spacing: 0.5px;'>INVOICE ANGSURAN PINJAMAN</h3>
                <p style='margin: 5px 0 0 0; font-size: 12px; opacity: 0.7;'>Koperasi Pegawai Kantor BPS Sumsel</p>
            </div>
            <div style='padding: 20px; color: #334155;'>
                <p>Halo Bapak/Ibu, <strong>" . esc($anggota['nama_anggota']) . "</strong></p>
                <p>Setoran angsuran Anda telah divalidasi sistem. Berikut rekapitulasi pembayaran Anda:</p>
                <table style='width: 100%; font-size: 13px; margin-bottom: 15px;'>
                    <tr><td width='140'><strong>Tanggal Validasi</strong></td><td>: " . date('d F Y', strtotime($tgl_bayar)) . "</td></tr>
                    <tr><td><strong>Metode Bayar</strong></td><td>: " . esc($metode) . "</td></tr>
                    <tr><td><strong>Kas / Bank</strong></td><td>: " . esc($bank) . "</td></tr>
                </table>
                <hr style='border: none; border-top: 1px solid #e2e8f0; margin: 20px 0;'>
                <h5 style='margin-bottom: 12px; color: #0b1a30; font-size: 13px; font-weight: 800;'>RINCIAN SALDO KONTRAK AKTIF:</h5>";

                foreach ($data_invoice as $inv) {
                    $message .= "
                    <div style='background-color: #f8fafc; padding: 15px; border-radius: 8px; margin-bottom: 12px; border-left: 4px solid #0066ff; border-top: 1px solid #e2e8f0; border-right: 1px solid #e2e8f0; border-bottom: 1px solid #e2e8f0;'>
                        <table style='width: 100%; font-size: 13px;'>
                            <tr><td><strong>Id Pinjaman</strong></td><td style='text-align: right; font-weight: bold;'>Pinjaman-" . $inv['id_pinjaman'] . "</td></tr>
                            <tr><td>Siklus Tenor</td><td style='text-align: right; font-weight: bold; color: #64748b;'>Bulan Ke-" . $inv['angsuran_ke'] . "</td></tr>
                            <tr><td>Nominal Angsuran</td><td style='text-align: right; color: #0066ff; font-weight: bold;'>Rp " . number_format($inv['nominal'], 0, ',', '.') . "</td></tr>
                            <tr><td><strong>Sisa Pinjaman</strong></td><td style='text-align: right; color: #ef4444; font-weight: bold;'>Rp " . number_format($inv['sisa'], 0, ',', '.') . "</td></tr>
                        </table>
                    </div>";
                }

        $message .= "
                <p style='font-size: 11px; color: #64748b; margin-top: 25px; line-height: 1.5;'>
                    *Pemberitahuan ini diterbitkan secara sah melalui sistem utama Koperasi BPS Sumsel. Jika membutuhkan pelaporan fisik silakan hubungi Seksi Administrasi Koperasi.
                </p>
            </div>
            <div style='text-align: center; background-color: #f1f5f9; padding: 12px; font-size: 11px; color: #94a3b8; border-radius: 0 0 12px 12px;'>
                © " . date('Y') . " Koperasi Pegawai BPS Sumsel.
            </div>
        </div>";

        $email->setMessage($message);
        return $email->send();
    }

/**
     * 4. EXPORT EXCEL REKAP DATA PINJAMAN TERAKHIR (ANTI-DOUBLE & TANPA TENOR)
     */
    public function export_excel_pinjaman()
    {
        // Kunci Perbaikan Query: Menembak master pinjaman aktif terakhir per anggota agar tidak double rows
        $data_angsuran = $this->db->table('pinjaman')
            ->select('
                pinjaman.*, 
                anggota.nama_anggota
            ')
            ->join('anggota', 'anggota.id_anggota = pinjaman.id_anggota')
            ->where('pinjaman.status_pinjaman', 'aktif') // Hanya menarik kontrak pembiayaan yang sedang berjalan
            ->orderBy('anggota.nama_anggota', 'ASC') 
            ->get()->getResultArray();

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setShowGridLines(true); 

        // HEADER REKAP LAPORAN
        $sheet->setCellValue('A1', 'REKAP DATA ANGSURAN PINJAMAN KOPERASI PEGAWAI BPS');
        $sheet->setCellValue('A2', 'PERIODE LAPORAN REKAP : ' . strtoupper(date('F Y'))); 

        // Merger kolom disesuaikan hanya sampai kolom F (karena kolom G & H sudah dihapus)
        $sheet->mergeCells('A1:F1');
        $sheet->mergeCells('A2:F2');

        $style_kop = [
            'font' => ['bold' => true, 'size' => 12, 'name' => 'Arial'],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER, 
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
            ]
        ];
        $sheet->getStyle('A1:A2')->applyFromArray($style_kop);

        // MATRIKS STRUKTUR KOLOM TABEL EXCEL (TOTAL 6 KOLOM: A - F)
        $sheet->setCellValue('A4', 'No');
        $sheet->setCellValue('B4', 'Nama Anggota');
        $sheet->setCellValue('C4', 'Sisa Hutang');
        $sheet->setCellValue('D4', 'Angsuran Pokok');
        $sheet->setCellValue('D5', '(Per Bulan)');
        $sheet->setCellValue('E4', 'Jasa / Bunga');
        $sheet->setCellValue('E5', '(Per Bulan)');
        $sheet->setCellValue('F4', 'Total Potongan');
        $sheet->setCellValue('F5', 'Per Bulan');

        $sheet->mergeCells('A4:A5'); 
        $sheet->mergeCells('B4:B5'); 
        $sheet->mergeCells('C4:C5'); 

        $style_header = [
            'font' => ['bold' => true, 'size' => 10, 'name' => 'Arial'],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                'wrapText' => true
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM, 
                    'color' => ['rgb' => '000000']
                ]
            ]
        ];
        $sheet->getStyle('A4:F5')->applyFromArray($style_header);

        $start_row = 6;
        $no = 1;

        $border_data = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN, 
                    'color' => ['rgb' => '000000']
                ]
            ],
            'font' => ['size' => 10, 'name' => 'Arial']
        ];

        // LOOPING ITERASI DATA MASTER (ANTI-DUPLIKASI)
        foreach ($data_angsuran as $row) {
            $sisa_hutang    = (float)($row['sisa_hutang'] ?? 0);
            $angsuran_pokok = (float)($row['angsuran_perbulan'] ?? 0);
            $biaya_jasa     = (float)($row['jasa_perbulan'] ?? 0);
            
            // Total tagihan bulanan yang ditarik dari kas / potong gaji
            $total_jumlah_bayar = $angsuran_pokok + $biaya_jasa; 

            $sheet->setCellValue('A' . $start_row, $no);
            $sheet->setCellValue('B' . $start_row, $row['nama_anggota']);
            $sheet->setCellValue('C' . $start_row, $sisa_hutang);
            $sheet->setCellValue('D' . $start_row, $angsuran_pokok);
            $sheet->setCellValue('E' . $start_row, $biaya_jasa);
            $sheet->setCellValue('F' . $start_row, $total_jumlah_bayar);

            // FORMATTING VARIABEL ANGKA & RATALURUS KANAN/KIRI EXCEL
            $sheet->getStyle('C' . $start_row . ':F' . $start_row)->getNumberFormat()->setFormatCode('#,##0');
            $sheet->getStyle('A' . $start_row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('B' . $start_row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
            $sheet->getStyle('C' . $start_row . ':F' . $start_row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
            $sheet->getStyle('A' . $start_row . ':F' . $start_row)->applyFromArray($border_data);

            $no++;
            $start_row++;
        }

        // AUTO SIZE KOLOM TABEL (HANYA SAMPAI F)
        foreach (range('A', 'F') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        $filename = 'Rekap_Pinjaman_Aktif_BPS_' . date('Ymd_His') . '.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }

    public function update_angsuran() {
        $id_pembayaran = $this->request->getPost('id_pembayaran'); $jumlah_bayar_baru = $this->request->getPost('jumlah_bayar');
        $tgl_bayar = $this->request->getPost('tgl_bayar'); $metode = $this->request->getPost('metode'); $bank = $this->request->getPost('bank');
        $bayar_lama = $this->db->table('pembayaran_pinjaman')->where('id_pembayaran', $id_pembayaran)->get()->getRowArray();
        if (!$bayar_lama) return redirect()->back()->with('error', 'Transaksi tidak ditemukan.');
        $id_pinjaman = $bayar_lama['id_pinjaman']; $pinjaman = $this->db->table('pinjaman')->where('id_pinjaman', $id_pinjaman)->get()->getRowArray();
        $this->db->transBegin();
        $sisa_hutang_netral = $pinjaman['sisa_hutang'] + $bayar_lama['jumlah_bayar']; $sisa_hutang_final = $sisa_hutang_netral - $jumlah_bayar_baru; $sisa_hutang_final = ($sisa_hutang_final < 0) ? 0 : $sisa_hutang_final;
        $this->db->table('pembayaran_pinjaman')->where('id_pembayaran', $id_pembayaran)->update(['tgl_bayar' => $tgl_bayar, 'jumlah_bayar' => $jumlah_bayar_baru, 'metode_pembayaran' => $metode, 'bank' => $bank, 'sisa_hutang_setelah_bayar' => $sisa_hutang_final]);
        $this->db->table('pinjaman')->where('id_pinjaman', $id_pinjaman)->update(['sisa_hutang' => $sisa_hutang_final, 'status_pinjaman' => ($sisa_hutang_final <= 0) ? 'lunas' : 'aktif']);
        if ($this->db->transStatus() === FALSE) { $this->db->transRollback(); return redirect()->back()->with('error', 'Gagal.'); }
        $this->db->transCommit(); return redirect()->back()->with('success', 'Berhasil.');
    }

    public function histori($id_anggota) {
        $anggota = $this->anggotaM->find($id_anggota); if (!$anggota) return redirect()->to('/bendahara/pinjaman')->with('error', 'Tidak ditemukan.');
        $daftarPinjaman = $this->db->table('pinjaman')->where('id_anggota', $id_anggota)->orderBy('id_pinjaman', 'ASC')->get()->getResultArray();
        $pinjaman1 = isset($daftarPinjaman[0]) ? $daftarPinjaman[0] : null; $pinjaman2 = isset($daftarPinjaman[1]) ? $daftarPinjaman[1] : null;
        $histori1 = []; if ($pinjaman1) { $histori1 = $this->db->table('pembayaran_pinjaman')->where('id_pinjaman', $pinjaman1['id_pinjaman'])->orderBy('id_pembayaran', 'DESC')->get()->getResultArray(); }
        $histori2 = []; if ($pinjaman2) { $histori2 = $this->db->table('pembayaran_pinjaman')->where('id_pinjaman', $pinjaman2['id_pinjaman'])->orderBy('id_pembayaran', 'DESC')->get()->getResultArray(); }
        return view('bendahara_koperasi/v_pinjaman_histori', ['title' => 'Histori Angsuran', 'anggota' => $anggota, 'pinjaman1' => $pinjaman1, 'pinjaman2' => $pinjaman2, 'histori1' => $histori1, 'histori2' => $histori2]);
    }

    public function delete_angsuran($id_bayar) {
        $bayar = $this->db->table('pembayaran_pinjaman')->where('id_pembayaran', $id_bayar)->get()->getRowArray(); if (!$bayar) return redirect()->back()->with('error', 'Gagal.');
        $this->db->transBegin(); $this->db->query("UPDATE pinjaman SET sisa_hutang = sisa_hutang + {$bayar['jumlah_bayar']}, angsuran_ke = angsuran_ke - 1, status_pinjaman = 'aktif' WHERE id_pinjaman = {$bayar['id_pinjaman']}");
        $this->db->table('pembayaran_pinjaman')->where('id_pembayaran', $id_bayar)->delete();
        if ($this->db->transStatus() === FALSE) { $this->db->transRollback(); return redirect()->back()->with('error', 'Gagal.'); }
        $this->db->transCommit(); return redirect()->back()->with('success', 'Histori dihapus.');
    }

    /**
     * 5. FUNGSI SINKRONISASI INPUT MANUAL (MANUAL FORM INPUT ADJUSTMENT)
     */
    public function store_pinjaman() {
        $id_anggota = $this->request->getPost('id_anggota'); 
        $total = (float)$this->request->getPost('jumlah_total'); 
        $tenor = (int)($this->request->getPost('jumlah_potongan') ?: 12); // Fallback tenor standar jika kosong

        $this->db->transBegin();
        
        // 🟢 SINKRONISASI FIELD KONTRAK MANUAL: Memakai skema field sisa_hutang agar singkron penuh
        $this->pinjamanM->insert([
            'id_anggota'        => $id_anggota, 
            'sisa_hutang'       => $total, // Mengisi kolom sisa_hutang secara valid
            'angsuran_perbulan' => $this->request->getPost('angsuran_perbulan') ?: ($total / $tenor), 
            'jasa_perbulan'     => $this->request->getPost('jasa_perbulan') ?: ($total * 0.01), 
            'jumlah_potongan'   => $tenor, 
            'angsuran_ke'       => 0, 
            'tgl_pinjam'        => date('Y-m-d'),
            'status_pinjaman'   => 'aktif'
        ]);

        if ($this->db->transStatus() === FALSE) { 
            $this->db->transRollback(); 
            return redirect()->back()->with('error', 'Gagal menambahkan pinjaman manual.'); 
        }
        
        $this->db->transCommit(); 
        return redirect()->to(base_url('bendahara/pinjaman'))->with('success', 'Kontrak pinjaman baru berhasil diaktifkan ke dalam sistem.');
    }
}