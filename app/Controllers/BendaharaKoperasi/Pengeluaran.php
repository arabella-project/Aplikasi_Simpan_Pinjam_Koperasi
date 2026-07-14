<?php

namespace App\Controllers\BendaharaKoperasi;

use App\Controllers\BaseController;
use App\Models\PengeluaranModel;

class Pengeluaran extends BaseController
{
    protected $pengrawanM;
    protected $db;

    public function __construct()
    {
        $this->pengrawanM = new PengeluaranModel();
        $this->db         = \Config\Database::connect();
    }

    /**
     * Menampilkan Halaman Utama Daftar Pengeluaran & Akumulasi 4 Metrics Ringkasan
     */
    public function index()
    {
        $all_pengeluaran = $this->pengrawanM->orderBy('tgl_pengeluaran', 'DESC')->findAll();

        // Hitung total beban berdasarkan klasifikasi kas/bank penerbit dana
        $total_pengeluaran = 0;
        $beban_bri = 0;
        $beban_bsi = 0;
        $beban_tunai = 0;

        foreach ($all_pengeluaran as $p) {
            $nominal = (float)$p['jumlah'];
            $total_pengeluaran += $nominal;

            $bank = strtoupper(trim($p['bank']));
            if ($bank === 'BRI') {
                $beban_bri += $nominal;
            } elseif ($bank === 'BSI') {
                $beban_bsi += $nominal;
            } elseif ($bank === 'TUNAI') {
                $beban_tunai += $nominal;
            }
        }

        $data = [
            'title'             => 'Data Pengeluaran Koperasi | Koperasi BPS',
            'pengeluaran'       => $all_pengeluaran,
            
            // Pengiriman variabel ke 4 Top Card Widgets Atas
            'total_pengeluaran' => $total_pengeluaran,
            'beban_bri'         => $beban_bri,
            'beban_bsi'         => $beban_bsi,
            'beban_tunai'       => $beban_tunai
        ];

        return view('bendahara_koperasi/v_pengeluaran', $data);
    }

    /**
     * Menyimpan Data Pengeluaran Baru
     */
    public function store()
    {
        $data = [
            'tgl_pengeluaran' => $this->request->getPost('tgl_pengeluaran'),
            'keterangan'      => $this->request->getPost('keterangan'),
            'kategori'        => $this->request->getPost('kategori'),
            'jumlah'          => $this->request->getPost('jumlah'),
            'bank'            => $this->request->getPost('bank')
        ];

        if ($this->pengrawanM->save($data)) {
            return redirect()->to(base_url('bendahara/pengeluaran'))->with('success', 'Catatan pengeluaran baru berhasil disimpan.');
        }

        return redirect()->back()->with('error', 'Gagal mencatat pengeluaran.');
    }

    /**
     * Memperbarui Data Pengeluaran (Edit)
     */
    public function update()
    {
        $id = $this->request->getPost('id_pengeluaran');
        
        $data = [
            'tgl_pengeluaran' => $this->request->getPost('tgl_pengeluaran'),
            'keterangan'      => $this->request->getPost('keterangan'),
            'kategori'        => $this->request->getPost('kategori'),
            'jumlah'          => $this->request->getPost('jumlah'),
            'bank'            => $this->request->getPost('bank')
        ];

        if ($this->pengrawanM->update($id, $data)) {
            return redirect()->to(base_url('bendahara/pengeluaran'))->with('success', 'Perubahan data pengeluaran berhasil divalidasi.');
        }

        return redirect()->back()->with('error', 'Gagal memperbarui data pengeluaran.');
    }

    /**
     * Menghapus Data Pengeluaran (Delete)
     */
    public function delete($id)
    {
        if ($this->pengrawanM->delete($id)) {
            return redirect()->to(base_url('bendahara/pengeluaran'))->with('success', 'Catatan log pengeluaran berhasil dihapus.');
        }

        return redirect()->back()->with('error', 'Gagal menghapus catatan pengeluaran.');
    }

    /**
     * EXPORT DATA JURNAL PENGELUARAN KOPERASI KE FORMAT EXCEL PREMIUM
     */
    public function export_excel_pengeluaran()
    {
        $data_pengeluaran = $this->db->table('pengeluaran')
                                     ->orderBy('tgl_pengeluaran', 'DESC')
                                     ->get()->getResultArray();

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setShowGridLines(true); 

        $sheet->setCellValue('A1', 'JURNAL PENGELUARAN DANA KOPERASI SERBAGUNA');
        $sheet->setCellValue('A2', 'BPS PROVINSI SUMATERA SELATAN');
        $sheet->setCellValue('A3', 'Periode Laporan Lunas: S.d ' . date('d F Y')); 

        $sheet->mergeCells('A1:F1');
        $sheet->mergeCells('A2:F2');
        $sheet->mergeCells('A3:F3');

        $style_kop = [
            'font' => ['bold' => true, 'size' => 12, 'name' => 'Arial'],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER, 
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
            ]
        ];
        $sheet->getStyle('A1:A3')->applyFromArray($style_kop);

        $sheet->setCellValue('A5', 'No');
        $sheet->setCellValue('B5', 'Tanggal');
        $sheet->setCellValue('C5', 'Kategori Pengeluaran');
        $sheet->setCellValue('D5', 'Rincian Keterangan');
        $sheet->setCellValue('E5', 'Metode / Bank');
        $sheet->setCellValue('F5', 'Total Jumlah (Rp)');

        $style_header = [
            'font' => ['bold' => true, 'size' => 10, 'name' => 'Arial'],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM, 
                    'color' => ['rgb' => '000000']
                ]
            ]
        ];
        $sheet->getStyle('A5:F5')->applyFromArray($style_header);

        $start_row = 6;
        $no = 1;
        $total_seluruh_pengeluaran = 0; 

        $border_data = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN, 
                    'color' => ['rgb' => '000000']
                ]
            ],
            'font' => ['size' => 10, 'name' => 'Arial']
        ];

        foreach ($data_pengeluaran as $row) {
            $nominal = (float)($row['jumlah'] ?? 0);
            $total_seluruh_pengeluaran += $nominal;

            $sheet->setCellValue('A' . $start_row, $no);
            $sheet->setCellValue('B' . $start_row, date('d-m-Y', strtotime($row['tgl_pengeluaran'])));
            $sheet->setCellValue('C' . $start_row, $row['kategori']);
            $sheet->setCellValue('D' . $start_row, $row['keterangan']);
            $sheet->setCellValue('E' . $start_row, $row['bank']);
            $sheet->setCellValue('F' . $start_row, $nominal);

            $sheet->getStyle('F' . $start_row)->getNumberFormat()->setFormatCode('#,##0');

            $sheet->getStyle('A' . $start_row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('B' . $start_row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('C' . $start_row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
            $sheet->getStyle('D' . $start_row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
            $sheet->getStyle('E' . $start_row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('F' . $start_row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);

            $sheet->getStyle('A' . $start_row . ':F' . $start_row)->applyFromArray($border_data);

            $no++;
            $start_row++;
        }

        $sheet->setCellValue('A' . $start_row, 'TOTAL PENGELUARAN KOLEKTIF');
        $sheet->mergeCells('A' . $start_row . ':E' . $start_row); 
        $sheet->setCellValue('F' . $start_row, $total_seluruh_pengeluaran);

        $sheet->getStyle('F' . $start_row)->getNumberFormat()->setFormatCode('#,##0');
        
        $style_total_footer = [
            'font' => ['bold' => true, 'size' => 10, 'name' => 'Arial'],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK, 
                    'color' => ['rgb' => '000000']
                ]
            ]
        ];
        $sheet->getStyle('A' . $start_row . ':F' . $start_row)->applyFromArray($style_total_footer);
        $sheet->getStyle('A' . $start_row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        foreach (range('A', 'F') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        $filename = 'Jurnal_Pengeluaran_Koperasi_BPS_' . date('Ymd_His') . '.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }
}