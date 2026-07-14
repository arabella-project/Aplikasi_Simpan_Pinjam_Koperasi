<?= $this->extend('layout/main'); ?>
<?= $this->section('content'); ?>

<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4 d-print-none">
        <div>
            <h4 class="fw-bold text-dark mb-1"><i class="bi bi-file-earmark-bar-graph text-primary me-2"></i>Laporan Neraca Lajur Keuangan</h4>
            <p class="text-muted small mb-0">Pembukuan Terbuka Tahun Buku: <span class="fw-bold"><?= $neraca['tahun'] ?></span></p>
            <div class="col-12 col-md-6 ms-auto d-flex justify-content-start justify-content-md-end gap-2 d-print-none">
    <a href="<?= base_url('bendahara/neraca/export_excel_neraca') ?>" class="btn btn-success rounded-pill px-4 fw-semibold small d-inline-flex align-items-center shadow-sm">
        <i class="bi bi-file-earmark-excel-fill me-2"></i> Ekspor Laporan Excel
    </a>

    <button onclick="window.print()" class="btn btn-primary rounded-pill px-4 shadow-sm fw-bold">
        <i class="bi bi-printer-fill me-2"></i> Cetak / Ekspor PDF
    </button>
</div>

    <div class="card border-0 shadow-sm p-4 p-md-5 bg-white rounded-4" id="printableArea">
        <div class="text-center mb-4">
            <h3 class="fw-bold text-dark mb-1">KOPERASI PEGAWAI BPS PROVINSI SUMATERA SELATAN</h3>
            <p class="mb-1 text-secondary fw-semibold">Neraca Posisi Keuangan Riil Seluruh Transaksi</p>
            <p class="text-muted small mb-0">Konsolidasi Transaksi Periode s/d Tanggal: <span class="fw-bold"><?= $tanggal_cetak ?></span></p>
            <hr class="mt-4 mb-4" style="border: 2px solid #000; opacity: 1;">
        </div>

        <div class="row g-5">
            <div class="col-md-6 border-end">
                <div class="d-flex align-items-center justify-content-between border-bottom pb-2 mb-3">
                    <h6 class="fw-bold text-primary mb-0">AKTIVA (ASET LANCAR)</h6>
                    <span class="badge bg-primary bg-opacity-10 text-primary small">Debet</span>
                </div>
                <table class="table table-sm table-borderless align-middle">
                    <tbody>
                        <tr>
                            <td class="py-2 text-secondary ps-2">Kas Tunai (kas_pinjaman)</td>
                            <td class="text-end fw-bold text-dark">Rp <?= number_format($neraca['kas_pinjaman'], 0, ',', '.'); ?></td>
                        </tr>
                        <tr>
                            <td class="py-2 text-secondary ps-2">Saldo Rekening Bank (bank)</td>
                            <td class="text-end fw-bold text-dark">Rp <?= number_format($neraca['bank'], 0, ',', '.'); ?></td>
                        </tr>
                        <tr>
                            <td class="py-2 text-secondary ps-2">Piutang Simpan Pinjam Anggota (piutang_simpan_pinjam)</td>
                            <td class="text-end fw-bold text-dark">Rp <?= number_format($neraca['piutang_simpan_pinjam'], 0, ',', '.'); ?></td>
                        </tr>
                        <tr class="table-light" style="border-top: 2px solid #cbd5e1;">
                            <th class="py-2.5 ps-2 text-dark">TOTAL AKTIVA LANCAR</th>
                            <th class="text-end text-primary fs-5">Rp <?= number_format($neraca['total_aktiva_lancar'], 0, ',', '.'); ?></th>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="col-md-6">
                <div class="d-flex align-items-center justify-content-between border-bottom pb-2 mb-3">
                    <h6 class="fw-bold text-danger mb-0">PASIVA (KEWAJIBAN & EKUITAS)</h6>
                    <span class="badge bg-danger bg-opacity-10 text-danger small">Kredit</span>
                </div>
                <table class="table table-sm table-borderless align-middle">
                    <tbody>
                        <tr>
                            <td class="py-2 text-secondary ps-2">Simpanan Pokok Anggota (simpanan_pokok_total)</td>
                            <td class="text-end fw-bold text-dark">Rp <?= number_format($neraca['simpanan_pokok_total'], 0, ',', '.'); ?></td>
                        </tr>
                        <tr>
                            <td class="py-2 text-secondary ps-2">Simpanan Wajib Anggota (simpanan_wajib_total)</td>
                            <td class="text-end fw-bold text-dark">Rp <?= number_format($neraca['simpanan_wajib_total'], 0, ',', '.'); ?></td>
                        </tr>
                        <tr>
                            <td class="py-2 text-secondary ps-2">Simpanan Sukarela Mandiri</td>
                            <td class="text-end fw-bold text-dark">Rp <?= number_format($neraca['simpanan_sukarela'], 0, ',', '.'); ?></td>
                        </tr>
                        <tr>
                            <td class="py-2 text-secondary ps-2">Alokasi Cadangan Dana Sosial (dana_sosial)</td>
                            <td class="text-end fw-bold text-dark">Rp <?= number_format($neraca['dana_sosial'], 0, ',', '.'); ?></td>
                        </tr>
                        <tr>
                            <td class="py-2 text-muted small italic ps-2">Sisa Hasil Usaha (SHU) Berjalan Bersih</td>
                            <td class="text-end text-success fw-bold">Rp <?= number_format($neraca['shu_berjalan'] - $neraca['dana_sosial'], 0, ',', '.'); ?></td>
                        </tr>
                        <tr class="table-light" style="border-top: 2px solid #cbd5e1;">
                            <th class="py-2.5 ps-2 text-dark">TOTAL PASIVA</th>
                            <th class="text-end text-danger fs-5">Rp <?= number_format($neraca['total_pasiva'], 0, ',', '.'); ?></th>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="alert alert-success border-0 rounded-3 text-center py-2 fw-bold small mt-4 mb-0">
            <i class="bi bi-shield-check-fill me-2 fs-6"></i> NERACA DINYATAKAN SEIMBANG (AKTIVA = PASIVA)
        </div>

        <div class="row mt-5 pt-4 text-center d-none d-print-flex">
            <div class="col-4">
                <p class="mb-5">Ketua Koperasi BPS Sumsel,</p>
                <br>
                <p class="fw-bold text-dark">............................................</p>
            </div>
            <div class="col-4"></div>
            <div class="col-4">
                <p class="mb-5">Palembang, <?= date('d F Y') ?><br>Bendahara Koperasi,</p>
                <br>
                <p class="fw-bold text-dark">Bendahara Koperasi BPS</p>
            </div>
        </div>
    </div>
</div>

<style>
@media print {
    body { background-color: #ffffff !important; color: #000000 !important; }
    .sidebar, .navbar, .d-print-none, .btn, .alert { display: none !important; }
    .container-fluid { width: 100% !important; padding: 0 !important; margin: 0 !important; }
    .card { box-shadow: none !important; border: none !important; padding: 0 !important; }
    .table-light { background-color: #f1f5f9 !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
    .text-primary, .text-danger, .text-success { color: #000000 !important; }
}
</style>

<?= $this->endSection(); ?>