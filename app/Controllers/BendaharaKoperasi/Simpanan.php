<?php

namespace App\Controllers\BendaharaKoperasi;

use App\Controllers\BaseController;
use App\Models\SimpananModel;
use App\Models\AnggotaModel;
use App\Models\PinjamanModel;

class Simpanan extends BaseController
{
    protected $simpananM;
    protected $pinjamanM;
    protected $db;

    public function __construct() {
        $this->simpananM = new SimpananModel();
        $this->pinjamanM = new PinjamanModel();
        $this->db = \Config\Database::connect();
    }

    public function index()
    {
        // 1. Ambil data master pagination simpanan
        $simpanan_master = $this->simpananM->select('simpanan.*, anggota.nama_anggota')
                                            ->join('anggota', 'anggota.id_anggota = simpanan.id_anggota')
                                            ->paginate(10, 'group1');

        // 2. Hitung statistik kumulatif untuk 4 Top Card Widgets
        $all_simpanan = $this->simpananM->findAll();
        $total_anggota = count($all_simpanan);
        
        $total_saldo_koperasi = 0;
        $max_saldo = 0;
        $max_nama = '-';

        foreach ($all_simpanan as $s) {
            $subtotal = (float)$s['simpanan_pokok'] + (float)$s['simpanan_wajib'] + (float)$s['simpanan_sukarela'];
            $total_saldo_koperasi += $subtotal;

            if ($subtotal > $max_saldo) {
                $max_saldo = $subtotal;
                // Cari nama anggota yang memiliki simpanan tertinggi tersebut
                $member = $this->db->table('anggota')->where('id_anggota', $s['id_anggota'])->get()->getRowArray();
                $max_nama = $member['nama_anggota'] ?? '-';
            }
        }

        $rata_rata_saldo = $total_anggota > 0 ? ($total_saldo_koperasi / $total_anggota) : 0;

        $data = [
            'title'                => 'Manajemen Simpanan | Koperasi BPS',
            'simpanan'             => $simpanan_master, 
            'pager'                => $this->simpananM->pager, 
            'anggota'              => (new AnggotaModel())->findAll(),
            
            // Suplai data ke 4 Card atas
            'total_anggota'        => $total_anggota,
            'total_saldo_koperasi' => $total_saldo_koperasi,
            'rata_rata_saldo'      => $rata_rata_saldo,
            'simpanan_tertinggi'   => [
                'nominal' => $max_saldo,
                'nama'    => $max_nama
            ]
        ];

        return view('bendahara_koperasi/v_simpanan', $data);
    }

    public function detail($id_anggota)
    {
        $anggota = (new AnggotaModel())->find($id_anggota);
        if (!$anggota) return redirect()->to('/bendahara/simpanan')->with('error', 'Anggota tidak ditemukan.');

        $data = [
            'title'   => 'Histori Simpanan Anggota',
            'anggota' => $anggota,
            'histori' => $this->simpananM->setTable('transaksi_simpanan')
                                        ->where('id_anggota', $id_anggota)
                                        ->orderBy('tgl_transaksi', 'DESC')
                                        ->paginate(10, 'group1'),
            'pager'   => $this->simpananM->pager
        ];
        return view('bendahara_koperasi/v_simpanan_detail', $data);
    }

    public function store()
    {
        $id_anggota = $this->request->getPost('id_anggota');
        $jenis      = $this->request->getPost('jenis_simpanan'); 
        $jumlah     = $this->request->getPost('jumlah');

        $this->db->transBegin();

        $this->db->table('transaksi_simpanan')->insert([
            'id_anggota'        => $id_anggota,
            'tgl_transaksi'     => $this->request->getPost('tgl_transaksi') ?: date('Y-m-d'),
            'jenis_simpanan'    => $jenis,
            'jumlah'            => $jumlah,
            'metode_pembayaran' => 'transfer',
            'bank'              => $this->request->getPost('bank'),
            'keterangan'        => $this->request->getPost('keterangan')
        ]);

        $kolom = "simpanan_" . $jenis;
        $this->db->table('simpanan')
                 ->where('id_anggota', $id_anggota)
                 ->set($kolom, "$kolom + $jumlah", FALSE)
                 ->update();

        $this->db->transCommit();
        return redirect()->to(base_url('bendahara/simpanan'))->with('success', 'Simpanan berhasil dicatat.');
    }

    public function update_transaksi()
    {
        $id_transaksi = $this->request->getPost('id_transaksi');
        $jumlah_baru  = $this->request->getPost('jumlah');

        $lama = $this->db->table('transaksi_simpanan')->where('id_transaksi_simpanan', $id_transaksi)->get()->getRowArray();
        if (!$lama) return redirect()->back()->with('error', 'Data tidak ditemukan.');

        $selisih = $jumlah_baru - $lama['jumlah'];
        $this->db->transBegin();

        $kolom = "simpanan_" . $lama['jenis_simpanan'];
        $this->db->table('simpanan')
                 ->where('id_anggota', $lama['id_anggota'])
                 ->set($kolom, "$kolom + ($selisih)", FALSE)
                 ->update();

        $this->db->table('transaksi_simpanan')->where('id_transaksi_simpanan', $id_transaksi)->update([
            'jumlah' => $jumlah_baru,
            'keterangan' => $this->request->getPost('keterangan'),
            'tgl_transaksi' => $this->request->getPost('tgl_transaksi')
        ]);

        $this->db->transCommit();
        return redirect()->back()->with('success', 'Transaksi berhasil diperbarui.');
    }

    public function delete_transaksi($id)
    {
        $transaksi = $this->db->table('transaksi_simpanan')->where('id_transaksi_simpanan', $id)->get()->getRowArray();
        if (!$transaksi) return redirect()->back()->with('error', 'Data tidak ditemukan.');

        $this->db->transBegin();

        $kolom = "simpanan_" . $transaksi['jenis_simpanan'];
        $this->db->table('simpanan')
                 ->where('id_anggota', $transaksi['id_anggota'])
                 ->set($kolom, "$kolom - " . $transaksi['jumlah'], FALSE)
                 ->update();

        $this->db->table('transaksi_simpanan')->where('id_transaksi_simpanan', $id)->delete();

        $this->db->transCommit();
        return redirect()->back()->with('success', 'Transaksi berhasil dihapus.');
    }

    public function import_excel_simpanan()
    {
        $file = $this->request->getFile('file_excel');
        if (!$file || !$file->isValid()) {
            return redirect()->back()->with('error', 'Pilih file Excel yang valid.');
        }

        try {
            $filePath = $file->getTempName();
            $inputFileType = \PhpOffice\PhpSpreadsheet\IOFactory::identify($filePath);
            $reader        = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($inputFileType);
            $reader->setReadDataOnly(true);
            $spreadsheet = $reader->load($filePath);
            $sheetData   = $spreadsheet->getActiveSheet()->toArray();
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal membaca Excel: ' . $e->getMessage());
        }

        $successCount = 0;
        $this->db->transBegin();

        try {
            foreach ($sheetData as $index => $row) {
                if ($index == 0) continue; 

                $nama_anggota_excel = isset($row[1]) ? trim($row[1]) : '';
                if (empty($nama_anggota_excel)) continue;

                $anggota = $this->db->table('anggota')->where('nama_anggota', $nama_anggota_excel)->get()->getRowArray();
                if (!$anggota) continue;
                $id_anggota = $anggota['id_anggota'];

                $simpanan_wajib    = isset($row[2]) ? (float)$row[2] : 0;
                $simpanan_sukarela = isset($row[3]) ? (float)$row[3] : 0;
                $simpanan_pokok    = isset($row[4]) ? (float)$row[4] : 0;

                $daftar_setoran = [
                    'wajib'    => $simpanan_wajib,
                    'sukarela' => $simpanan_sukarela,
                    'pokok'    => $simpanan_pokok
                ];

                $ada_transaksi = false;
                foreach ($daftar_setoran as $jenis => $jumlah) {
                    if ($jumlah > 0) {
                        $this->db->table('transaksi_simpanan')->insert([
                            'id_anggota'        => $id_anggota,
                            'tgl_transaksi'     => date('Y-m-d'),
                            'jenis_simpanan'    => $jenis,
                            'jumlah'            => $jumlah,
                            'metode_pembayaran' => 'transfer',
                            'bank'              => 'BRI',
                            'keterangan'        => 'Pencatatan Kolektif Bulanan via Impor Excel'
                        ]);

                        $kolom_target = "simpanan_" . $jenis;
                        $this->db->table('simpanan')
                                 ->where('id_anggota', $id_anggota)
                                 ->set($kolom_target, "$kolom_target + $jumlah", FALSE)
                                 ->update();

                        $ada_transaksi = true;
                    }
                }
                if ($ada_transaksi) $successCount++;
            }

            $this->db->transCommit();
            return redirect()->back()->with('success', 'Berhasil memproses ' . $successCount . ' baris simpanan anggota dari Excel.');
        } catch (\Exception $e) {
            $this->db->transRollback();
            return redirect()->back()->with('error', 'Impor Gagal: ' . $e->getMessage());
        }
    }

    public function export_excel_simpanan()
    {
        $data_simpanan = $this->simpananM->select('simpanan.*, anggota.nama_anggota')
                                         ->join('anggota', 'anggota.id_anggota = simpanan.id_anggota')
                                         ->orderBy('anggota.nama_anggota', 'ASC')
                                         ->findAll();

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setShowGridLines(true); 

        $sheet->setCellValue('A1', 'POSISI SIMPANAN ANGGOTA KOPERASI SERBAGUNA');
        $sheet->setCellValue('A2', 'BPS PROVINSI SUMATERA SELATAN');
        $sheet->setCellValue('A3', 'per ' . date('d F Y')); 

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
        $sheet->setCellValue('B5', 'Nama');
        $sheet->setCellValue('C5', 'Simpanan');
        $sheet->setCellValue('C6', 'Pokok');
        $sheet->setCellValue('D5', 'Simpanan');
        $sheet->setCellValue('D6', 'Wajib');
        $sheet->setCellValue('E5', 'Simpanan');
        $sheet->setCellValue('E6', 'Sukarela');
        $sheet->setCellValue('F5', 'Jumlah');

        $sheet->mergeCells('A5:A6'); 
        $sheet->mergeCells('B5:B6'); 
        $sheet->mergeCells('F5:F6'); 

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
        $sheet->getStyle('A5:F6')->applyFromArray($style_header);

        $start_row = 7;
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

        foreach ($data_simpanan as $s) {
            $pokok    = (float)($s['simpanan_pokok'] ?? 0);
            $wajib    = (float)($s['simpanan_wajib'] ?? 0);
            $sukarela = (float)($s['simpanan_sukarela'] ?? 0);
            $total_baris = $pokok + $wajib + $sukarela;

            $sheet->setCellValue('A' . $start_row, $no);
            $sheet->setCellValue('B' . $start_row, $s['nama_anggota']);
            
            if ($total_baris == 0) {
                $sheet->setCellValue('C' . $start_row, '-');
                $sheet->setCellValue('D' . $start_row, '-');
                $sheet->setCellValue('E' . $start_row, '-');
                $sheet->setCellValue('F' . $start_row, '-');
                $sheet->getStyle('C' . $start_row . ':F' . $start_row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            } else {
                $sheet->setCellValue('C' . $start_row, $pokok);
                $sheet->setCellValue('D' . $start_row, $wajib);
                $sheet->setCellValue('E' . $start_row, $sukarela);
                $sheet->setCellValue('F' . $start_row, $total_baris);
                $sheet->getStyle('C' . $start_row . ':F' . $start_row)->getNumberFormat()->setFormatCode('#,##0');
                $sheet->getStyle('C' . $start_row . ':F' . $start_row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
            }

            $sheet->getStyle('A' . $start_row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('B' . $start_row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
            $sheet->getStyle('A' . $start_row . ':F' . $start_row)->applyFromArray($border_data);

            $no++;
            $start_row++;
        }

        foreach (range('A', 'F') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        $filename = 'Simpanan_Anggota_Koperasi_BPS_' . date('Ymd_His') . '.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }
}