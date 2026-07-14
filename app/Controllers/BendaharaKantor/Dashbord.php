<?php

namespace App\Controllers\BendaharaKantor;

use App\Controllers\BaseController;

class Dashbord extends BaseController
{
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    /**
     * 1. HALAMAN UTAMA: DISPLAY RINGKASAN METRIK PROGRESS PAYROLL
     */
    public function index()
    {
        $fields = $this->db->getFieldNames('potongan_gaji');
        $kolom_status = in_array('status_potongan', $fields) ? 'status_potongan' : (in_array('status_verifikasi', $fields) ? 'status_verifikasi' : 'status');

        $stats = [
            'total_menunggu' => $this->db->table('potongan_gaji')->whereIn($kolom_status, ['diajukan', 'menunggu', 'pending'])->countAllResults(),
            'total_berhasil' => $this->db->table('potongan_gaji')->whereIn($kolom_status, ['diterima', 'berhasil'])->countAllResults(),
            'total_ditolak'  => $this->db->table('potongan_gaji')->whereIn($kolom_status, ['ditolak', 'gagal'])->countAllResults(),
        ];

        $data = [
            'title'        => 'Dashboard Bendahara Kantor',
            'nama_lengkap' => session()->get('nama_lengkap'),
            'stats'        => $stats,
        ];

        return view('bendahara_kantor/v_dashboard', $data);
    }

    /**
     * 2. TABEL ANTRIAN UTAMA: MANIFES DATA POTONG GAJI PEGAWAI RUNNING
     */
    public function tabel()
    {
        $fields = $this->db->getFieldNames('potongan_gaji');
        
        $kolom_status = 'status'; 
        if (in_array('status_potongan', $fields)) {
            $kolom_status = 'status_potongan';
        } elseif (in_array('status_verifikasi', $fields)) {
            $kolom_status = 'status_verifikasi';
        }

        $kolom_nominal = 'total_potongan'; 
        if (in_array('jumlah_potongan', $fields)) {
            $kolom_nominal = 'jumlah_potongan';
        }

        $kolom_tgl = 'tgl_pengajuan';
        if (!in_array('tgl_pengajuan', $fields)) {
            $kolom_tgl = in_array('tgl_eksekusi', $fields) ? 'tgl_eksekusi' : $fields[0];
        }

        $list_validasi = $this->db->table('potongan_gaji')
                        ->select("potongan_gaji.*, anggota.nama_anggota, anggota.email,
                                 potongan_gaji.{$kolom_status} as status_potongan,
                                 potongan_gaji.{$kolom_nominal} as total_potongan,
                                 potongan_gaji.{$kolom_tgl} as tgl_pengajuan")
                        ->join('anggota', 'anggota.id_anggota = potongan_gaji.id_anggota')
                        ->whereIn("potongan_gaji.{$kolom_status}", ['diajukan', 'menunggu', 'pending'])
                        ->orderBy("potongan_gaji.{$kolom_tgl}", 'ASC')
                        ->get()
                        ->getResultArray();

        $data = [
            'title'         => 'Verifikasi Potong Gaji',
            'nama_lengkap'  => session()->get('nama_lengkap'),
            'list_validasi' => $list_validasi,
        ];

        return view('bendahara_kantor/v_tabel_validasi', $data);
    }

    /**
     * 3. ACTION LOGIC ENGINE: EKSEKUSI MASSAL AUTO-DEBIT PAYROLL & EMAIL ROUTER
     */
    public function validasi_serentak()
    {
        $url_kembali = base_url('kantor/dashbord/tabel');
        $pilihan_opsi = $this->request->getPost('opsi_debit'); 

        if (empty($pilihan_opsi)) {
            return redirect()->to($url_kembali)->with('error', 'Tidak ada data potongan yang dipilih untuk divalidasi!');
        }

        $fields = $this->db->getFieldNames('potongan_gaji');
        $kolom_status = in_array('status_potongan', $fields) ? 'status_potongan' : (in_array('status_verifikasi', $fields) ? 'status_verifikasi' : 'status');
        $kolom_nominal = in_array('jumlah_potongan', $fields) ? 'jumlah_potongan' : 'total_potongan';
        $kolom_tgl_update = in_array('tgl_validasi', $fields) ? 'tgl_validasi' : (in_array('tgl_eksekusi', $fields) ? 'tgl_eksekusi' : $fields[0]);
        
        $status_tolak = in_array('status_potongan', $fields) ? 'ditolak' : 'gagal';
        $status_terima = in_array('status_potongan', $fields) ? 'diterima' : 'berhasil';

        $sukses_count = 0;
        $gagal_count = 0;

        $this->db->transBegin();

        foreach ($pilihan_opsi as $id_potongan => $aksi) {
            $potongan = $this->db->table('potongan_gaji')
                                 ->select("potongan_gaji.*, anggota.nama_anggota, anggota.email, potongan_gaji.{$kolom_nominal} as jumlah_potongan")
                                 ->join('anggota', 'anggota.id_anggota = potongan_gaji.id_anggota')
                                 ->where('id_potongan', $id_potongan)
                                 ->get()->getRowArray();

            if (!$potongan) continue;

            $id_anggota = $potongan['id_anggota'];
            $jumlah_debit = (float)$potongan['jumlah_potongan'];

            if ($aksi === 'terima') {
                $this->db->table('potongan_gaji')->where('id_potongan', $id_potongan)->update([
                    $kolom_status     => $status_terima,
                    $kolom_tgl_update => date('Y-m-d H:i:s')
                ]);

                $daftarPinjaman = $this->db->table('pinjaman')
                                           ->where('id_anggota', $id_anggota)
                                           ->where('status_pinjaman', 'aktif')
                                           ->orderBy('id_pinjaman', 'ASC')
                                           ->get()->getResultArray();

                $sisa_dana = $jumlah_debit;
                $total_kontrak = count($daftarPinjaman);
                $data_invoice = [];

                foreach ($daftarPinjaman as $index => $p) {
                    if ($sisa_dana <= 0) break;

                    $sisa_hutang_kontrak = (float)$p['sisa_hutang'];
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
                        'tgl_bayar'                 => date('Y-m-d'),
                        'angsuran_ke'               => $angsuran_baru,
                        'jumlah_bayar'              => $bayar_ke_pinjaman,
                        'metode_pembayaran'         => 'potong_gaji',
                        'bank'                      => 'Payroll BPS (Kantor)',
                        'sisa_hutang_setelah_bayar' => $sisa_baru
                    ]);

                    $this->db->table('pinjaman')->where('id_pinjaman', $p['id_pinjaman'])->update([
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

                if (!empty($potongan['email']) && filter_var($potongan['email'], FILTER_VALIDATE_EMAIL)) {
                    $email = \Config\Services::email();
                    $email->setTo($potongan['email']);
                    $email->setSubject("SUCCESS: Invoice Auto-Debit Potong Gaji Kantor BPS");
                    $email->setMessage($this->renderHtmlSukses($potongan, $data_invoice));
                    @$email->send();
                }
                $sukses_count++;

            } else {
                $this->db->table('potongan_gaji')->where('id_potongan', $id_potongan)->update([
                    $kolom_status     => $status_tolak,
                    $kolom_tgl_update => date('Y-m-d H:i:s')
                ]);

                if (!empty($potongan['email']) && filter_var($potongan['email'], FILTER_VALIDATE_EMAIL)) {
                    $email = \Config\Services::email();
                    $email->setTo($potongan['email']);
                    $email->setSubject("ALERT: Kegagalan Pemotongan Gaji Payroll Koperasi BPS");
                    $email->setMessage($this->renderHtmlGagal($potongan));
                    @$email->send();
                }
                $gagal_count++;
            }
        }

        if ($this->db->transStatus() === FALSE) {
            $this->db->transRollback();
            return redirect()->to($url_kembali)->with('error', 'Gagal memproses validasi database serentak.');
        }

        $this->db->transCommit();
        return redirect()->to($url_kembali)->with('success', "Validasi serentak selesai dieksekusi! Berhasil diproses: <strong>{$sukses_count} Pegawai</strong>, Gagal didebit: <strong>{$gagal_count} Pegawai</strong>.");
    }

    /**
     * 4. TEMPLATE EMAIL LAYOUT: HTML INVOICE AUTO-DEBIT SUKSES
     */
    private function renderHtmlSukses($potongan, $data_invoice) {
        $msg = "<div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #e2e8f0; border-radius: 12px;'>
            <div style='background-color: #10b981; color: white; padding: 20px; text-align: center; border-radius: 8px 8px 0 0;'>
                <h3 style='margin: 0;'>INVOICE AUTO-DEBIT PAYROLL BERHASIL</h3>
                <p style='margin: 5px 0 0 0; font-size: 12px; opacity: 0.9;'>Otoritas Bendahara Gaji Kantor BPS Sumsel</p>
            </div>
            <div style='padding: 20px; color: #334155;'>
                <p>Halo, <strong>" . esc($potongan['nama_anggota']) . "</strong></p>
                <p>Proses pemotongan gaji bulanan Anda telah **BERHASIL** dieksekusi dengan nominal **Rp " . number_format($potongan['jumlah_potongan'], 0, ',', '.') . "**.</p>
                <hr style='border: none; border-top: 1px solid #e2e8f0; margin: 20px 0;'>
                <h5 style='color: #0b1a30; margin-bottom: 10px;'>ALOKASI DATA MUTASI PINJAMAN:</h5>";
                foreach ($data_invoice as $inv) {
                    $msg .= "<div style='background-color: #f8fafc; padding: 12px; border: 1px solid #e2e8f0; border-left: 4px solid #10b981; border-radius: 6px; margin-bottom: 10px;'>
                        <table style='width: 100%; font-size: 13px;'>
                            <tr><td><strong>ID Kontrak Pinjaman</strong></td><td style='text-align: right;'>#KONTRAK-" . $inv['id_pinjaman'] . "</td></tr>
                            <tr><td>Tenor Putaran</td><td style='text-align: right;'>Bulan Ke-" . $inv['angsuran_ke'] . "</td></tr>
                            <tr><td>Nominal Terbayar</td><td style='text-align: right; color: #10b981; font-weight: bold;'>Rp " . number_format($inv['nominal'], 0, ',', '.') . "</td></tr>
                            <tr><td><strong>Sisa Hutang Buku</strong></td><td style='text-align: right; color: #ef4444; font-weight: bold;'>Rp " . number_format($inv['sisa'], 0, ',', '.') . "</td></tr>
                        </table>
                    </div>";
                }
        $msg .= "</div></div>";
        return $msg;
    }

    /**
     * 5. TEMPLATE EMAIL LAYOUT: HTML WARNING PAYMENT DEBIT GAGAL
     */
    private function renderHtmlGagal($potongan) {
        $nama_anggota = esc($potongan['nama_anggota']);
        $nominal      = number_format($potongan['jumlah_potongan'], 0, ',', '.');
        $bulan_ini    = date('F Y');

        return "
        <div style='font-family: \"Helvetica Neue\", Helvetica, Arial, sans-serif; max-width: 600px; margin: 0 auto; border: 1px solid #e2e8f0; border-radius: 12px; overflow: hidden; background-color: #ffffff; box-shadow: 0 4px 10px rgba(0,0,0,0.05);'>
            <div style='background-color: #dc2626; padding: 30px 20px; text-align: center; border-bottom: 3px solid #b91c1c;'>
                <div style='font-size: 38px; margin-bottom: 12px; line-height: 1;'>⚠️</div>
                <h2 style='margin: 0; color: #ffffff; font-size: 20px; letter-spacing: 0.5px;'>GAGAL DEBIT </h2>
                <p style='margin: 6px 0 0 0; color: #fca5a5; font-size: 13px; font-weight: 500;'>Otoritas Bendahara Gaji Kantor BPS Sumsel</p>
            </div>
            <div style='padding: 30px 25px; color: #334155;'>
                <p style='margin-top: 0; font-size: 15px;'>Yth. Bapak/Ibu <strong>{$nama_anggota}</strong>,</p>
                <p style='line-height: 1.6; font-size: 15px;'>Melalui pemberitahuan ini, kami menginformasikan bahwa sistem <strong>tidak dapat memproses</strong> pemotongan gaji bulanan Anda untuk angsuran koperasi pada periode <strong>{$bulan_ini}</strong>.</p>
                <div style='background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 18px; margin: 25px 0;'>
                    <h4 style='margin: 0 0 12px 0; color: #0f172a; font-size: 13px; text-transform: uppercase; letter-spacing: 0.5px; border-bottom: 1px solid #e2e8f0; padding-bottom: 8px;'>Rincian Mutasi Ditolak</h4>
                    <table style='width: 100%; font-size: 14px; border-collapse: collapse;'>
                        <tr>
                            <td style='padding: 6px 0; color: #64748b; width: 45%;'>Nominal Tagihan:</td>
                            <td style='padding: 6px 0; font-weight: bold; color: #dc2626; text-align: right;'>Rp {$nominal}</td>
                        </tr>
                        <tr>
                            <td style='padding: 6px 0; color: #64748b; vertical-align: top;'>Alasan :</td>
                            <td style='padding: 6px 0; font-weight: 600; text-align: right; color: #475569;'>Sisa <i>Take Home Pay</i><br>tidak memenuhi syarat</td>
                        </tr>
                    </table>
                </div>
                <div style='background-color: #fef2f2; border: 1px solid #fecaca; border-left: 5px solid #dc2626; border-radius: 6px; padding: 18px;'>
                    <h4 style='margin: 0 0 8px 0; color: #991b1b; font-size: 14px;'>🚨 TINDAKAN YANG DIPERLUKAN SEGERA</h4>
                    <p style='margin: 0; color: #7f1d1d; font-size: 13.5px; line-height: 1.5;'>
                        Untuk mencegah pencatatan tunggakan atau denda pada buku pinjaman Anda, mohon <strong>segera melakukan setoran angsuran secara mandiri (Tunai / Transfer Bank)</strong> dengan menghubungi Bendahara Koperasi BPS Sumsel.
                    </p>
                </div>
            </div>
            <div style='background-color: #f1f5f9; padding: 20px; text-align: center; border-top: 1px solid #e2e8f0;'>
                <p style='margin: 0; font-size: 12px; color: #64748b; font-weight: bold;'>Sistem Informasi Koperasi BPS Sumsel</p>
                <p style='margin: 5px 0 0 0; font-size: 11px; color: #94a3b8;'>Email otomatis dari sistem. Mohon tidak membalas pesan ini.</p>
            </div>
        </div>";
    }

/**
     * 6. ENGINE EXPORT SPREADSHEET EXCEL ANTREAN MUTASI PAYROLL (INFO BANK DI BAWAH)
     */
    public function export_excel() 
    {
        $fields = $this->db->getFieldNames('potongan_gaji');
        $kolom_status = in_array('status_potongan', $fields) ? 'status_potongan' : (in_array('status_verifikasi', $fields) ? 'status_verifikasi' : 'status');
        $kolom_nominal = in_array('jumlah_potongan', $fields) ? 'jumlah_potongan' : 'total_potongan';

        $list = $this->db->table('potongan_gaji')
                    ->select("potongan_gaji.*, anggota.nama_anggota, potongan_gaji.{$kolom_nominal} as total_potongan")
                    ->join('anggota', 'anggota.id_anggota = potongan_gaji.id_anggota')
                    ->whereIn("potongan_gaji.{$kolom_status}", ['diajukan', 'menunggu', 'pending'])
                    ->get()->getResultArray();

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setShowGridLines(true);

        $sheet->setCellValue('A1', 'DAFTAR ANTREAN POTONG GAJI PAYROLL PEGAWAI BPS');
        $sheet->setCellValue('A2', 'PERIODE TRANSAKSI : ' . strtoupper(date('F Y')));
        $sheet->mergeCells('A1:D1'); $sheet->mergeCells('A2:D2');

        $sheet->getStyle('A1:A2')->applyFromArray([
            'font' => ['bold' => true, 'size' => 12, 'name' => 'Arial'],
            'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER]
        ]);

        // Headers asli tetap 4 kolom
        $sheet->setCellValue('A4', 'No')->setCellValue('B4', 'Nama Anggota / Pegawai')->setCellValue('C4', 'Periode')->setCellValue('D4', 'Nominal Potongan');
        $sheet->getStyle('A4:D4')->applyFromArray([
            'font' => ['bold' => true], 'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
            'borders' => ['allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM]]
        ]);

        $row_idx = 5; $no = 1;
        foreach ($list as $r) {
            $sheet->setCellValue('A' . $row_idx, $no)
                  ->setCellValue('B' . $row_idx, $r['nama_anggota'])
                  ->setCellValue('C' . $row_idx, date('F Y'))
                  ->setCellValue('D' . $row_idx, (float)$r['total_potongan']);
            
            $sheet->getStyle('D' . $row_idx)->getNumberFormat()->setFormatCode('#,##0');
            $sheet->getStyle('A' . $row_idx . ':D' . $row_idx)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            $row_idx++; $no++;
        }

        // 🟢 INFORMASI TAMBAHAN BANK DI BAGIAN BAWAH TABEL EXCEL
        $row_idx += 2; // Beri jarak 2 baris kosong di bawah tabel
        $sheet->setCellValue('B' . $row_idx, '* INFORMASI REKENING TUJUAN REKAPITULASI PENGIRIMAN:');
        $sheet->getStyle('B' . $row_idx)->getFont()->setBold(true)->setSize(10)->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('2563eb'));
        
        $row_idx++;
        $sheet->setCellValue('B' . $row_idx, '1. BANK BRI : 0214-01-002345-50-1 (A.n.Koperasi BPS Sumsel)');
        $sheet->getStyle('B' . $row_idx)->getFont()->setSize(9)->setItalic(true);
        
        $row_idx++;
        $sheet->setCellValue('B' . $row_idx, '2. BANK BSI : 7142059381 (A.n. Koperasi BPS Sumsel)');
        $sheet->getStyle('B' . $row_idx)->getFont()->setSize(9)->setItalic(true);

        foreach (range('A', 'D') as $col) { $sheet->getColumnDimension($col)->setAutoSize(true); }

        $filename = 'Antrean_Payroll_BPS_' . date('Ymd_His') . '.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }

    /**
     * 7. ENGINE EXPORT PDF MANIFES DOKUMEN CETAK UTUT (INFO BANK DI BAWAH)
     */
    public function export_pdf() 
    {
        $fields = $this->db->getFieldNames('potongan_gaji');
        $kolom_status = in_array('status_potongan', $fields) ? 'status_potongan' : (in_array('status_verifikasi', $fields) ? 'status_verifikasi' : 'status');
        $kolom_nominal = in_array('jumlah_potongan', $fields) ? 'jumlah_potongan' : 'total_potongan';

        $list = $this->db->table('potongan_gaji')
                    ->select("potongan_gaji.*, anggota.nama_anggota, potongan_gaji.{$kolom_nominal} as total_potongan")
                    ->join('anggota', 'anggota.id_anggota = potongan_gaji.id_anggota')
                    ->whereIn("potongan_gaji.{$kolom_status}", ['diajukan', 'menunggu', 'pending'])
                    ->get()->getResultArray();

        $html = "
        <h3 style='text-align:center; font-family:Arial; margin-bottom:5px;'>KOPERASI PEGAWAI KANTOR BPS SUMSEL</h3>
        <p style='text-align:center; font-family:Arial; font-size:12px; margin-top:0; border-bottom:2px solid #000; padding-bottom:10px;'>Daftar Manifes Antrean Potongan Gaji Bulanan - Periode " . date('F Y') . "</p>
        <table border='1' cellspacing='0' cellpadding='8' style='width:100%; font-family:Arial; font-size:13px; border-collapse:collapse;'>
            <tr style='background-color:#f2f2f2;'>
                <th width='10%'>No</th>
                <th>Nama Anggota Pegawai</th>
                <th width='25%'>Periode Rekap</th>
                <th width='25%'>Nominal Potongan</th>
            </tr>";
        
        $no = 1; $total_global = 0;
        foreach($list as $r) {
            $html .= "<tr>
                <td style='text-align:center;'>{$no}</td>
                <td>" . esc($r['nama_anggota']) . "</td>
                <td style='text-align:center;'>" . date('F Y') . "</td>
                <td style='text-align:right;'>Rp " . number_format($r['total_potongan'], 0, ',', '.') . "</td>
            </tr>";
            $total_global += (float)$r['total_potongan']; $no++;
        }
        
        $html .= "<tr style='background-color:#f9f9f9; font-weight:bold;'>
            <td colspan='3' style='text-align:right;'>TOTAL ANTREAN DEBIT BULAN INI:</td>
            <td style='text-align:right; color:#2563eb;'>Rp " . number_format($total_global, 0, ',', '.') . "</td>
        </tr></table>";

        // 🟢 INFORMASI TAMBAHAN BANK DI BAGIAN BAWAH HALAMAN PDF
        $html .= "
        <div style='margin-top: 30px; font-family:Arial; font-size:11px; color:#475569; line-height:1.6;'>
            <p style='margin-bottom:5px; font-weight:bold; color:#0f172a;'>* INFORMASI REKENING TUJUAN REKAPITULASI PENGIRIMAN:</p>
            <table style='width:100%; font-size:11px;'>
                <tr><td width='20'>•</td><td><b>BANK BRI</b> : 0214-01-002345-50-1 (A.n. Koperasi BPS Sumsel)</td></tr>
                <tr><td>•</td><td><b>BANK BSI</b> : 7142059381 (A.n. Koperasi BPS Sumsel)</td></tr>
            </table>
        </div>";

        $dompdf = new \Dompdf\Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $dompdf->stream('Manifes_Payroll_Kantor_BPS_' . date('Ymd') . '.pdf', ['Attachment' => 1]);
        exit;
    }
}