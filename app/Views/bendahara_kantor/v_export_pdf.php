<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar Pengajuan Pemotongan Gaji PDF</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 11pt; color: #1e293b; line-height: 1.4; }
        .header-box { border-bottom: 2px solid #cbd5e1; padding-bottom: 8px; margin-bottom: 20px; }
        .title { font-size: 13pt; font-weight: bold; margin: 0; text-transform: uppercase; }
        .subtitle { font-size: 10pt; font-style: italic; color: #64748b; margin: 4px 0 0 0; }
        
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th { background-color: #f1f5f9; border: 1px solid #cbd5e1; padding: 8px; text-align: left; font-size: 10pt; }
        td { border: 1px solid #e2e8f0; padding: 8px; font-size: 10pt; }
        
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .fw-bold { font-weight: bold; }
        
        .box-rekening { margin-top: 35px; border-left: 4px solid #0066ff; padding-left: 12px; }
        .box-title { font-weight: bold; color: #0066ff; font-size: 11pt; margin-bottom: 8px; }
        .table-info { width: auto; margin-top: 5px; }
        .table-info td { border: none; padding: 3px 0; font-size: 10.5pt; }
    </style>
</head>
<body>

    <div class="header-box">
        <div class="title"><?= $title ?></div>
        <div class="subtitle">Periode Rekap Berkas: <?= $periode ?></div>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 8%;" class="text-center">No</th>
                <th style="width: 42%;">Nama Lengkap Pegawai</th>
                <th style="width: 20%;">Kategori Transaksi</th>
                <th style="width: 15%;" class="text-right">Nominal (Rp)</th>
                <th style="width: 15%;" class="text-center">Tanggal</th>
            </tr>
        </thead>
        <tbody>
            <?php $no = 1; foreach ($list_data as $row): ?>
            <tr>
                <td class="text-center"><?= $no++ ?></td>
                <td><?= esc($row['nama_anggota']) ?></td>
                <td>Potong Gaji Anggota kpperasi (BPS)</td>
                <td class="text-right"><?= number_format((float)($row['total_potongan'] ?? $row['jumlah_potongan']), 0, ',', '.') ?></td>
                <td class="text-center"><?= date('d-m-Y', strtotime($row['tgl_pengajuan'])) ?></td>
            </tr>
            <?php endforeach; ?>
            <tr class="fw-bold" style="background-color: #f8fafc;">
                <td colspan="3" class="text-center" style="border-top: 2px solid #cbd5e1;">TOTAL AJUAN DANA KOLEKTIF</td>
                <td class="text-right" style="border-top: 2px solid #cbd5e1;"><?= number_format($totalDanaKolektif, 0, ',', '.') ?></td>
                <td class="text-center" style="border-top: 2px solid #cbd5e1;">-</td>
            </tr>
        </tbody>
    </table>

    <div class="box-rekening">
        <div class="box-title">INFORMASI REKENING TUJUAN TRANSFER (KOPERASI BPS):</div>
        <table class="table-info">
            <tr>
                <td style="width: 180px; color: #475569;">Nama Bank Tujuan</td>
                <td class="fw-bold">: BANK BRI</td>
            </tr>
            <tr>
                <td style="color: #475569;">Nomor Rekening Koperasi</td>
                <td class="fw-bold">: 012345678912345</td>
            </tr>
            <tr>
                <td style="color: #475569;">Atas Nama Rekening</td>
                <td class="fw-bold">: KOPERASI PEGAWAI RI BPS SUMATERA SELATAN</td>
            </tr>
        </table>
    </div>

</body>
</html>