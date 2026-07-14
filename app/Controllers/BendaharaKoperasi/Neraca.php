<?php

namespace App\Controllers\BendaharaKoperasi;

use App\Controllers\BaseController;
use App\Models\NeracaModel;

class Neraca extends BaseController
{
    protected $db;

    public function __construct() {
        $this->db = \Config\Database::connect();
    }

    public function index()
    {
        $neracaM = new NeracaModel();

        // Mengambil array kalkulasi data akuntansi terintegrasi dari Model
        $neracaData = $neracaM->hitungNeraca();

        $data = [
            'title'         => 'Laporan Neraca Koperasi',
            'neraca'        => $neracaData,
            'tanggal_cetak' => date('d F Y')
        ];

        return view('bendahara_koperasi/v_neraca', $data);
    }

    // ===================================================================================
    // FITUR UTAMA: EXPORT NERACA AKUNTANSI DUA KOLOM BERDAMPINGAN PREMIUM
    // ===================================================================================
    public function export_excel_neraca()
    {
        $neracaM = new NeracaModel();
        $neraca = $neracaM->hitungNeraca();

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setShowGridLines(true);

        // 1. MEMBUAT KOP JUDUL LAPORAN KELEMBAGAAN
        $sheet->setCellValue('A1', 'KOPERASI PEGAWAI BPS PROVINSI SUMATERA SELATAN');
        $sheet->setCellValue('A2', 'Neraca Posisi Keuangan Riil Seluruh Transaksi');
        $sheet->setCellValue('A3', 'Konsolidasi Transaksi Periode s/d Tanggal: ' . date('d F Y') . ' | Tahun Buku: ' . ($neraca['tahun'] ?? date('Y')));

        // Satukan bentang kop judul laporan dari kolom A sampai E
        $sheet->mergeCells('A1:E1');
        $sheet->mergeCells('A2:E2');
        $sheet->mergeCells('A3:E3');

        $style_kop = [
            'font' => ['bold' => true, 'size' => 12, 'name' => 'Arial'],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
            ]
        ];
        $sheet->getStyle('A1:A3')->applyFromArray($style_kop);

        // Berikan baris kosong tebal pemisah judul dengan isi tabel (Meniru elemen <hr> di view)
        $sheet->getStyle('A4:E4')->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM);

        // 2. FORMALISASI HEADER DUA BLOK (Baris Ke-6)
        $sheet->setCellValue('A6', 'AKTIVA (ASET LANCAR)');
        $sheet->mergeCells('A6:B6');
        
        // Kolom C sengaja kita kosongkan sebagai jeda / spacer pemisah dua tabel
        
        $sheet->setCellValue('D6', 'PASIVA (KEWAJIBAN & EKUITAS)');
        $sheet->mergeCells('D6:E6');

        $style_sub_header = [
            'font' => ['bold' => true, 'size' => 11, 'name' => 'Arial', 'color' => ['rgb' => 'FFFFFF']],
            'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => '0b1a30'] // Senada dengan warna biru gelap premium BPS
            ]
        ];
        $sheet->getStyle('A6:B6')->applyFromArray($style_sub_header);
        $sheet->getStyle('D6:E6')->applyFromArray($style_sub_header);

        // 3. MENYUNTIKKAN NILAI DATA AKUNTANSI SECARA PARALEL BERDAMPINGAN
        // Border tipis untuk baris komponen data keuangan
        $border_thin_flat = [
            'borders' => [
                'bottom' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN, 'color' => ['rgb' => 'E2E8F0']]
            ],
            'font' => ['size' => 10, 'name' => 'Arial']
        ];

        // --- BARIS 1 ---
        $sheet->setCellValue('A7', 'Kas Tunai (kas_pinjaman)');
        $sheet->setCellValue('B7', (float)($neraca['kas_pinjaman'] ?? 0));
        
        $sheet->setCellValue('D7', 'Simpanan Pokok Anggota (simpanan_pokok_total)');
        $sheet->setCellValue('E7', (float)($neraca['simpanan_pokok_total'] ?? 0));

        // --- BARIS 2 ---
        $sheet->setCellValue('A8', 'Saldo Rekening Bank (bank)');
        $sheet->setCellValue('B8', (float)($neraca['bank'] ?? 0));
        
        $sheet->setCellValue('D8', 'Simpanan Wajib Anggota (simpanan_wajib_total)');
        $sheet->setCellValue('E8', (float)($neraca['simpanan_wajib_total'] ?? 0));

        // --- BARIS 3 ---
        $sheet->setCellValue('A9', 'Piutang Simpan Pinjam Anggota');
        $sheet->setCellValue('B9', (float)($neraca['piutang_simpan_pinjam'] ?? 0));
        
        $sheet->setCellValue('D9', 'Simpanan Sukarela Mandiri');
        $sheet->setCellValue('E9', (float)($neraca['simpanan_sukarela'] ?? 0));

        // --- BARIS 4 ---
        $sheet->setCellValue('D10', 'Alokasi Cadangan Dana Sosial (dana_sosial)');
        $sheet->setCellValue('E10', (float)($neraca['dana_sosial'] ?? 0));

        // --- BARIS 5 ---
        $sheet->setCellValue('D11', 'Sisa Hasil Usaha (SHU) Berjalan Bersih');
        $shu_bersih = (float)($neraca['shu_berjalan'] ?? 0) - (float)($neraca['dana_sosial'] ?? 0);
        $sheet->setCellValue('E11', $shu_bersih);

        // Tempelkan style border flat tipis pada baris data
        for ($i = 7; $i <= 11; $i++) {
            $sheet->getStyle('A' . $i . ':B' . $i)->applyFromArray($border_thin_flat);
            $sheet->getStyle('D' . $i . ':E' . $i)->applyFromArray($border_thin_flat);
        }

        // 4. PEMBENTUKAN BARIS TOTAL AKHIR (TOTAL AKTIVA VS TOTAL PASIVA)
        $sheet->setCellValue('A13', 'TOTAL AKTIVA LANCAR');
        $sheet->setCellValue('B13', (float)($neraca['total_aktiva_lancar'] ?? 0));
        
        $sheet->setCellValue('D13', 'TOTAL PASIVA');
        $sheet->setCellValue('E13', (float)($neraca['total_pasiva'] ?? 0));

        $style_total_footer = [
            'font' => ['bold' => true, 'size' => 11, 'name' => 'Arial'],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'F8FAFC'] // Latar belakang abu-abu sangat muda
            ],
            'borders' => [
                'top' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_DOUBLE, 'color' => ['rgb' => '000000']],
                'bottom' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK, 'color' => ['rgb' => '000000']]
            ]
        ];
        $sheet->getStyle('A13:B13')->applyFromArray($style_total_footer);
        $sheet->getStyle('D13:E13')->applyFromArray($style_total_footer);

        // 5. FORMATTING PERATAAN & INDIKATOR RIBUAN TITIK
        // Kolom Angka (B dan E) Rata Kanan, Kolom Teks (A dan D) Rata Kiri
        $sheet->getStyle('B7:B13')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
        $sheet->getStyle('E7:E13')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
        $sheet->getStyle('B7:B13')->getNumberFormat()->setFormatCode('#,##0');
        $sheet->getStyle('E7:E13')->getNumberFormat()->setFormatCode('#,##0');

        // Set Lebar Kolom C Kecil Saja sebagai pembatas spacer
        $sheet->getColumnDimension('C')->setWidth(4);

        // 6. MEMBUAT BARIS NOTIFIKASI BALANCE STATUS
        $sheet->setCellValue('A15', 'STATUS VALIDASI: NERACA DINYATAKAN SEIMBANG (AKTIVA = PASIVA)');
        $sheet->mergeCells('A15:E15');
        $style_status_balance = [
            'font' => ['bold' => true, 'size' => 10, 'name' => 'Arial', 'color' => ['rgb' => '155724']],
            'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'D4EDDA'] // Hijau pudar khas alert success Bootstrap
            ]
        ];
        $sheet->getStyle('A15:E15')->applyFromArray($style_status_balance);

        // 7. AUTOFIT WIDTH DIMENSION COLUMN EXCEL
        foreach (['A', 'B', 'D', 'E'] as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        // 8. STREAM PROSES PIPING DOWNLOAD FILE EXCEL LANGSUNG KE COMPUTER USER
        $filename = 'Laporan_Neraca_Keuangan_Koperasi_BPS_' . date('Ymd_His') . '.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }
}