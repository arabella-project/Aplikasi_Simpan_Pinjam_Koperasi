<?= $this->extend('layout/main'); ?>
<?= $this->section('content'); ?>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>
    :root {
        --bps-blue: #00a2e9;
        --bps-green: #4aa135;
        --bps-orange: #f48221;
        --bps-blue-dark: #0082c8;
        --bps-green-dark: #3b822a;
        --bps-orange-dark: #d85d15;
        --text-dark: #1e293b;
        --text-muted: #64748b;
        --bg-light: #f8fafc;
    }

    body {
        background-color: #f4f7fe;
        overflow-x: hidden;
    }

    .dashboard-container {
        padding: 1rem 1.25rem;
        width: 100%;
        max-width: 100%;
    }

    .tracking-tight { 
        letter-spacing: -0.5px; 
    }
    
    .card-premium-stats { 
        border: 1px solid #e2e8f0; 
        border-radius: 12px; 
        background: #ffffff; 
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .card-premium-stats:hover { 
        transform: translateY(-2px); 
        box-shadow: 0 8px 16px -5px rgba(0, 162, 233, 0.08);
    }

    .icon-premium-box { 
        width: 40px; 
        height: 40px; 
        display: flex; 
        align-items: center; 
        justify-content: center; 
        border-radius: 50%; 
        font-size: 1.2rem;
    }

    .card-highlight-bri { border-top: 4px solid var(--bps-blue) !important; }
    .card-highlight-bsi { border-top: 4px solid var(--bps-green) !important; }
    .card-highlight-total { border-top: 4px solid var(--bps-orange) !important; }

    .pulse-badge {
        display: inline-block;
        width: 6px;
        height: 6px;
        background-color: var(--bps-orange);
        border-radius: 50%;
        animation: pulseGlow 2s infinite;
    }

    @keyframes pulseGlow {
        0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(244, 130, 33, 0.5); }
        70% { transform: scale(1); box-shadow: 0 0 0 4px rgba(244, 130, 33, 0); }
        100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(244, 130, 33, 0); }
    }

    .premium-table-card {
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        background: #ffffff;
    }

    .premium-table-card .card-header {
        background-color: #ffffff;
        padding: 12px 18px;
        border-bottom: 1px solid #f1f5f9;
    }

    .table-premium-theme thead th {
        background-color: #f8fafc;
        color: #64748b;
        font-weight: 600;
        font-size: 0.7rem;
        letter-spacing: 0.5px;
        padding: 10px 16px;
        border-bottom: 1px solid #e2e8f0;
    }

    .table-premium-theme tbody td {
        padding: 10px 16px;
        border-bottom: 1px solid #f1f5f9;
        font-size: 0.8rem;
        color: #334155;
    }

    .table-premium-theme tbody tr:last-child td { 
        border-bottom: none; 
    }

    .btn-premium-review {
        background-color: #f8fafc;
        color: var(--bps-blue);
        font-weight: 600;
        font-size: 0.75rem;
        border: 1px solid #e2e8f0;
        padding: 6px 14px;
        border-radius: 8px;
        transition: all 0.2s;
    }

    .btn-premium-review:hover {
        background-color: var(--bps-blue);
        color: #ffffff;
        border-color: var(--bps-blue);
    }
    
    .avatar-text {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        font-size: 0.75rem;
        background-color: #ffedd5;
        color: var(--bps-orange-dark);
    }

    .compact-queue-item {
        padding: 6px 10px !important;
        margin-bottom: 2px;
    }
</style>

<div class="dashboard-container">
    
    
    <div class="row g-3 mb-3">
        <div class="col-12 col-md-4">
            <div class="card card-premium-stats card-highlight-bri shadow-sm p-3 h-100">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <small class="text-muted text-uppercase tracking-wider fw-semibold" style="font-size: 0.65rem;">Saldo Kas BRI</small>
                        <h5 class="fw-bold mb-1 mt-1 tracking-tight" style="font-size: 1.4rem; color: var(--text-dark);">Rp <?= number_format($saldo_bri, 0, ',', '.'); ?></h5>
                    </div>
                    <div class="icon-premium-box" style="background-color: rgba(0, 162, 233, 0.06); color: var(--bps-blue);">
                        <i class="bi bi-bank"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-4">
            <div class="card card-premium-stats card-highlight-bsi shadow-sm p-3 h-100">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <small class="text-muted text-uppercase tracking-wider fw-semibold" style="font-size: 0.65rem;">Saldo Kas BSI</small>
                        <h5 class="fw-bold mb-1 mt-1 tracking-tight" style="font-size: 1.4rem; color: var(--text-dark);">Rp <?= number_format($saldo_bsi, 0, ',', '.'); ?></h5>
                    </div>
                    <div class="icon-premium-box" style="background-color: rgba(74, 161, 53, 0.06); color: var(--bps-green);">
                        <i class="bi bi-building-bank"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-4">
            <div class="card card-premium-stats card-highlight-total shadow-sm p-3 h-100">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <small class="text-muted text-uppercase tracking-wider fw-semibold" style="font-size: 0.65rem;">Total Simpanan Kas</small>
                        <h5 class="fw-bold mb-1 mt-1 tracking-tight" style="font-size: 1.4rem; color: var(--text-dark);">Rp <?= number_format($total_simpanan, 0, ',', '.'); ?></h5>
                    </div>
                    <div class="icon-premium-box" style="background-color: rgba(244, 130, 33, 0.06); color: var(--bps-orange);">
                        <i class="bi bi-wallet2"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-3">
        <div class="col-12 col-xl-8">
            <div class="card premium-table-card border-0 shadow-sm overflow-hidden h-100">
                <div class="card-header d-flex justify-content-between align-items-center bg-white py-2">
                    <div class="d-flex align-items-center gap-2">
                        <div class="p-1.5 rounded-3" style="background-color: rgba(0, 162, 233, 0.06); color: var(--bps-blue);">
                            <i class="bi bi-file-earmark-text fs-6"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-0 text-dark" style="font-size: 0.85rem;">Antrean Pengajuan Pinjaman Baru</h6>
                            <small class="text-muted" style="font-size: 0.68rem;">Berkas masuk yang belum dievaluasi matriks keputusan.</small>
                        </div>
                    </div>
                    <span class="badge bg-success-subtle text-success border-0 px-2 py-1.5 rounded-pill fw-semibold" style="background-color: #e8f5e9; font-size: 0.65rem;">Active Queue</span>
                </div>

                <div class="table-responsive">
                    <table class="table table-premium-theme align-middle mb-0">
                        <thead>
                            <tr class="text-uppercase">
                                <th class="ps-3" width="50">No</th>
                                <th width="100">Tgl Masuk</th>
                                <th>Profil Anggota Koperasi</th>
                                <th width="180">Jumlah Dana Diajukan</th>
                                <th class="text-center" width="120">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($list_pengajuan)): ?>
                                <?php $no = 1; foreach ($list_pengajuan as $lp): ?>
                                    <tr>
                                        <td class="ps-3 fw-bold text-secondary"><?= $no++; ?></td>
                                        <td class="fw-medium text-secondary" style="font-size: 0.8rem;">
                                            <?= date('d/m/Y', strtotime($lp['tgl_pengajuan'])) ?>
                                        </td>
                                        <td>
                                            <div class="fw-bold text-dark" style="font-size: 0.82rem;"><?= esc($lp['nama_anggota']) ?></div>
                                            <div class="text-muted" style="font-size: 0.68rem;">
                                                ID: <span style="color: var(--bps-blue);">#<?= $lp['id_anggota'] ?></span>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="fw-bold text-dark" style="font-size: 0.82rem;">Rp <?= number_format($lp['jumlah_diajukan'], 0, ',', '.') ?></span>
                                        </td>
                                        <td class="text-center">
                                            <a href="<?= base_url('bendahara/pengajuan/review/' . $lp['id_pengajuan']) ?>" class="btn btn-premium-review shadow-sm py-1 px-2">
                                                Periksa
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-muted small">
                                        <div class="py-2">
                                            <i class="bi bi-file-earmark-check text-primary" style="font-size: 2.2rem;"></i>
                                            <p class="mt-2 text-dark fw-bold mb-0" style="font-size: 0.8rem;">Tidak ada antrean pengajuan baru.</p>
                                        </div>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <div class="card-footer bg-white border-0 py-2 px-3 mt-auto d-flex justify-content-between align-items-center border-top">
                    <small class="text-muted" style="font-size: 0.68rem;">
                        <i class="bi bi-shield-check text-success me-1"></i> Live server synchronization.
                    </small>
                    <a href="<?= base_url('bendahara/pengajuan') ?>" class="small fw-bold text-decoration-none d-flex align-items-center gap-1" style="color: var(--bps-blue); font-size: 0.72rem;">
                        Analisis SAW <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>

        <div class="col-12 col-xl-4">
    <div class="card premium-table-card border-0 shadow-sm p-3 h-100 d-flex flex-column justify-content-between">
        <div>
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="badge rounded-pill px-2 py-1 fw-bold text-uppercase d-inline-flex align-items-center gap-1" style="background-color: #fff7ed; color: var(--bps-orange-dark); font-size: 0.65rem; border: 1px solid #ffedd5;">
                    <span class="pulse-badge"></span> Perlu Validasi
                </span>
                <div class="text-muted"><i class="bi bi-envelope-paper"></i></div>
            </div>
            
            <div class="mb-2">
                <h2 class="fw-bold text-dark mb-0 tracking-tight" style="font-weight: 800; font-size: 1.8rem;"><?= count($list_penarikan); ?></h2>
                <h6 class="text-dark fw-bold mb-0" style="font-size: 0.8rem;">Permohonan Penarikan Dana</h6>
            </div>

            <div class="d-flex flex-column gap-1">
                <?php if (!empty($list_penarikan)): ?>
                    <?php foreach ($list_penarikan as $p): $initials = strtoupper(substr($p['nama_anggota'], 0, 2)); ?>
                        <a href="<?= base_url('bendahara/fitur/buka_penarikan/' . $p['id_penarikan']) ?>" class="text-decoration-none d-block">
                            <div class="d-flex align-items-center justify-content-between compact-queue-item rounded-2 p-2" style="background-color: #fafafa; transition: background 0.2s;">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="avatar-text" style="width:30px; height:30px; background:#ffedd5; color:#ea580c; border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:10px; font-weight:700;"><?= $initials ?></div>
                                    <div>
                                        <div class="fw-bold text-dark" style="font-size: 0.78rem;"><?= esc($p['nama_anggota']) ?></div>
                                        <div class="text-muted" style="font-size: 0.65rem;">Baru saja <i class="bi bi-box-arrow-up-right text-muted ms-1" style="font-size:8px;"></i></div>
                                    </div>
                                </div>
                                <div class="text-end">
                                    <div class="fw-bold text-dark" style="font-size: 0.78rem;">
                                        Rp <?= number_format($p['jumlah_ditarik'] ?? 0, 0, ',', '.') ?>
                                    </div>
                                </div>
                            </div>
                        </a>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="text-center py-5 text-muted small" style="font-size: 0.75rem;">
                        <i class="bi bi-shield-check text-success fs-3 d-block mb-2"></i>Tidak ada antrean penarikan.
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="mt-3 w-100">
            <a href="<?= base_url('koperasi/konfirmasi_penarikan') ?>" class="btn w-100 text-center fw-bold font-monospace p-2 rounded-3 text-warning border border-warning bg-warning bg-opacity-10 shadow-sm" style="font-size: 0.82rem; border-style: dashed !important; text-decoration: none; display: block;">
                Proses Pencairan <i class="bi bi-arrow-right"></i>
            </a>
        </div>
    </div>
</div>

    <div class="row g-3">
        <div class="col-12 col-md-4">
            <div class="card premium-table-card border-0 shadow-sm p-3 h-100">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h6 class="fw-bold text-dark mb-0" style="font-size: 0.85rem;">Ringkasan Keuangan</h6>
                </div>
                <div class="d-flex align-items-center justify-content-center my-1" style="height: 130px;">
                    <canvas id="donutChart"></canvas>
                </div>
                <div class="mt-2" style="font-size: 0.72rem;">
                    <div class="d-flex justify-content-between mb-1">
                        <span><i class="bi bi-circle-fill text-primary me-1"></i> Simpanan</span>
                        <span class="fw-bold"><?= $persen_simpanan ?>%</span>
                    </div>
                    <div class="d-flex justify-content-between mb-1">
                        <span><i class="bi bi-circle-fill text-success me-1"></i> Pinjaman</span>
                        <span class="fw-bold"><?= $persen_pinjaman ?>%</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span><i class="bi bi-circle-fill text-warning me-1"></i> Pengeluaran</span>
                        <span class="fw-bold"><?= $persen_pengeluaran ?>%</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-8">
            <div class="card premium-table-card border-0 shadow-sm p-3 h-100">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h6 class="fw-bold text-dark mb-0" style="font-size: 0.85rem;">Grafik Arus Kas</h6>
                </div>
                <div style="height: 185px; width: 100%;">
                    <canvas id="lineChart"></canvas>
                </div>
            </div>
        </div>
    </div>

</div>

<script>
    // 1. Donut Chart Component (Menggunakan data real-time dari Controller PHP)
    const ctxDonut = document.getElementById('donutChart').getContext('2d');
    new Chart(ctxDonut, {
        type: 'doughnut',
        data: {
            labels: ['Simpanan', 'Pinjaman', 'Pengeluaran'],
            datasets: [{
                data: [
                    <?= $persen_simpanan ?>, 
                    <?= $persen_pinjaman ?>, 
                    <?= $persen_pengeluaran ?>
                ],
                backgroundColor: ['#00a2e9', '#4aa135', '#f48221'],
                borderWidth: 2
            }]
        },
        options: {
            plugins: { legend: { display: false } },
            cutout: '70%',
            maintainAspectRatio: false
        }
    });

    // 2. Line Chart Component
    const ctxLine = document.getElementById('lineChart').getContext('2d');
    new Chart(ctxLine, {
        type: 'line',
        data: {
            labels: <?= $chart_months; ?>, 
            datasets: [
                {
                    label: 'Pemasukan',
                    data: <?= $chart_pemasukan; ?>, 
                    borderColor: '#00a2e9',
                    backgroundColor: '#00a2e9',
                    tension: 0.4,
                    borderWidth: 2,
                    pointRadius: 3
                },
                {
                    label: 'Pengeluaran',
                    data: <?= $chart_pengeluaran; ?>, 
                    borderColor: '#4aa135',
                    backgroundColor: '#4aa135',
                    tension: 0.4,
                    borderWidth: 2,
                    pointRadius: 3
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { position: 'bottom', labels: { boxWidth: 12, font: { size: 10 } } } },
            scales: {
                y: { grid: { display: true, color: '#f1f5f9' }, ticks: { font: { size: 9 } } },
                x: { grid: { display: false }, ticks: { font: { size: 9 } } }
            }
        }
    });
</script>

<?= $this->endSection(); ?>