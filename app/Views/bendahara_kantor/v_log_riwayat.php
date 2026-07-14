<?= $this->extend('layout/main_bendahara_kantor'); ?>
<?= $this->section('content'); ?>

<style>
    .tracking-tight { letter-spacing: -0.5px; }
    .table-premium-header th {
        font-weight: 800 !important; color: #0b1a30 !important; text-transform: uppercase; text-align: center;
        background-color: #f8fafc !important; border-bottom: 2px solid #0b1a30 !important; padding: 16px 12px; font-size: 0.8rem;
    }
    .table-premium-body td { border-bottom: 1px solid #e2e8f0; padding: 14px 12px; vertical-align: middle; text-align: center; font-size: 0.88rem; color: #334155; }
    .table-premium-body td.text-start { text-align: left !important; }
    .table-premium-body td.text-end { text-align: right !important; }
    
    .card-stat-box { border-radius: 16px; border: 1px solid #e2e8f0; background: #ffffff; transition: all 0.2s ease; }
    .card-stat-box:hover { transform: translateY(-2px); box-shadow: 0 10px 20px rgba(0,0,0,0.03); }
</style>

<div class="container-fluid py-4 px-4">
    <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3" style="border-color: #e2e8f0 !important;">
        <div>
            <span class="text-uppercase tracking-widest text-primary fw-bold" style="font-size: 0.72rem;">Lembar Rekapitulasi Pembukuan</span>
            <h4 class="fw-bold text-dark tracking-tight mb-0 mt-1">Log Riwayat Potongan Gaji Bulanan</h4>
            <p class="text-muted small mb-0">Halaman khusus pemantauan hasil verifikasi payroll gaji pegawai BPS Sumsel secara berkala.</p>
        </div>
        
        <form action="<?= base_url('kantor/dashbord/riwayat') ?>" method="get" class="d-flex align-items-center gap-2">
            <input type="month" name="bulan" class="form-control form-control-sm rounded-3" value="<?= $bulan_pilihan ?>" onchange="this.form.submit()">
            <a href="<?= base_url('kantor/dashbord/tabel') ?>" class="btn btn-sm btn-light border rounded-3 fw-bold px-3">
                <i class="bi bi-arrow-left"></i> Kembali ke Antrean
            </a>
        </form>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card-stat-box p-3 border-start border-4 border-success">
                <small class="text-uppercase fw-bold text-muted d-block" style="font-size: 0.68rem;">Debit Berhasil (Uang)</small>
                <h5 class="fw-bold text-success mb-0 mt-1">Rp <?= number_format($summary['sukses_nominal'], 0, ',', '.') ?></h5>
                <small class="text-muted font-monospace" style="font-size: 0.72rem;"><?= $summary['sukses_count'] ?> Transaksi</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card-stat-box p-3 border-start border-4 border-danger">
                <small class="text-uppercase fw-bold text-muted d-block" style="font-size: 0.68rem;">Debit Gagal / Retur (Uang)</small>
                <h5 class="fw-bold text-danger mb-0 mt-1">Rp <?= number_format($summary['gagal_nominal'], 0, ',', '.') ?></h5>
                <small class="text-muted font-monospace" style="font-size: 0.72rem;"><?= $summary['gagal_count'] ?> Anggota</small>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-header bg-white border-0 py-3 px-4">
            <h6 class="fw-bold text-dark tracking-tight mb-0"><i class="bi bi-journal-text text-primary me-1.5"></i> Arsip Buku Eksekusi Bulanan</h6>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-premium-header">
                    <tr>
                        <th width="50">No</th>
                        <th class="text-start">Pegawai / Pemilik Rekening</th>
                        <th>Metode Eksekusi</th>
                        <th>Sumber Validasi Kas</th>
                        <th class="text-end">Nominal Pemotongan</th>
                        <th>Tanggal Eksekusi Kantor</th>
                        <th>Status Akhir Audit</th>
                    </tr>
                </thead>
                <tbody class="table-premium-body">
                    <?php if (!empty($log)) : $no = 1; foreach ($log as $row) : ?>
                        <tr>
                            <td class="fw-semibold text-secondary"><?= $no++ ?></td>
                            <td class="text-start">
                                <div class="fw-bold text-dark"><?= esc($row['nama_anggota']) ?></div>
                                <small class="text-muted font-monospace" style="font-size: 0.72rem;"><?= esc($row['email']) ?: 'Email belum diisi' ?></small>
                            </td>
                            <td><span class="badge bg-light text-dark border px-2.5 py-1.5 rounded-pill">Auto-Debit Payroll</span></td>
                            <td class="text-secondary fw-semibold">Bank Gaji Kantor BPS</td>
                            <td class="text-end fw-bold text-dark" style="padding-right: 25px;">
                                Rp <?= number_format($row['jumlah_potongan'], 0, ',', '.') ?>
                            </td>
                            <td class="text-secondary font-monospace" style="font-size: 0.8rem;">
                                <?= (!empty($row['tgl_eksekusi'])) ? date('d M Y H:i', strtotime($row['tgl_eksekusi'])) . ' WIB' : '<span class="text-muted italic">Tidak tercatat</span>' ?>
                            </td>
                            <td>
                                <?php if (in_array(strtolower($row['status_verifikasi']), ['berhasil', 'diterima'])) : ?>
                                    <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-3 py-1.5 rounded-pill fw-bold text-uppercase" style="font-size: 0.68rem;">
                                        <i class="bi bi-check-circle-fill me-1"></i> Sukses Terpotong
                                    </span>
                                <?php else : ?>
                                    <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 px-3 py-1.5 rounded-pill fw-bold text-uppercase" style="font-size: 0.68rem;">
                                        <i class="bi bi-exclamation-triangle-fill me-1"></i> Gagal / Ditagih Mandiri
                                    </span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; else : ?>
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted small">
                                Belum ada riwayat transaksi log payroll untuk periode bulan <strong><?= $bulan_pilihan ?></strong>.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>