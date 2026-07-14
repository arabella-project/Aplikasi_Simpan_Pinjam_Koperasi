<?= $this->extend('layout/main'); ?>
<?= $this->section('content'); ?>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
    /* --- INTEGRATED FINTECH STYLE CORE --- */
    :root {
        --bps-blue: #00a2e9;
        --bps-green: #4aa135;
        --bps-orange: #f48221;
        --bps-blue-dark: #0082c8;
        --bps-green-dark: #3b822a;
        --bps-orange-dark: #d85d15;
        --text-dark: #1e293b;
        --text-muted: #64748b;
    }

    body {
        background-color: #f4f7fe;
    }

    .tracking-tight { letter-spacing: -0.5px; }

    /* --- PREMIUM TABLES STYLING --- */
    .table-premium-header th {
        font-weight: 600 !important;
        color: #64748b !important;
        text-transform: uppercase;
        background-color: #f8fafc !important;
        border-bottom: 1px solid #e2e8f0 !important;
        padding: 14px 16px;
        font-size: 0.72rem;
        letter-spacing: 0.5px;
    }

    .table-premium-body td {
        border-bottom: 1px solid #f1f5f9;
        padding: 14px 16px;
        vertical-align: middle;
        font-size: 0.85rem;
        color: #334155;
    }

    .table-hover tbody tr:hover { 
        background-color: #f8fafc; 
    }

    /* --- DYNAMIC AVATARS --- */
    .avatar-circle {
        width: 34px;
        height: 34px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 0.78rem;
        color: #ffffff;
    }
    .avatar-1 { background-color: #00a2e9; }
    .avatar-2 { background-color: #4aa135; }
    .avatar-3 { background-color: #f48221; }
    .avatar-4 { background-color: #7c3aed; }
    .avatar-5 { background-color: #ec4899; }

    .btn-pencairan-premium {
        background-color: var(--bps-green);
        color: #ffffff;
        border: none;
        box-shadow: 0 4px 12px rgba(74, 161, 53, 0.2);
        transition: all 0.2s ease;
    }
    .btn-pencairan-premium:hover {
        background-color: #3b822a; 
        color: #ffffff;
        transform: translateY(-1px);
        box-shadow: 0 6px 16px rgba(74, 161, 53, 0.3);
    }
    .btn-pencairan-premium:active {
        transform: translateY(0);
    }
</style>

<div class="container-fluid py-3 px-4">
    
<div class="row align-items-center mb-4 g-3">
    <div class="col-12 col-md-6">
        <span class="text-uppercase tracking-widest fw-bold mb-1 d-block" style="font-size: 0.68rem; color: var(--bps-blue);">Sistem Pengambilan Keputusan (DSS)</span>
        <h4 class="fw-bold text-dark tracking-tight mb-1">Analisis Kelayakan Pinjaman</h4>
        </div>
    <div class="col-12 col-md-6 ms-auto d-flex justify-content-start justify-content-md-end align-items-center gap-2">
        
    <span class="badge rounded-3 px-3 py-2.5 fw-bold border text-dark" style="background-color: #ffffff; font-size: 0.82rem; border-color: #e2e8f0 !important; box-shadow: 0 2px 6px rgba(0,0,0,0.02);">
            <i class="bi bi-info-circle me-1" style="color: var(--bps-blue);"></i> Total Antrean: <?= count($pengajuan ?? []) ?> Berkas
        </span>
        <a href="<?= base_url('bendahara/pengajuan/riwayat') ?>" class="btn btn-pencairan-premium rounded-3 py-2 px-3 fw-bold small d-inline-flex align-items-center" style="font-size: 0.82rem;">
            <i class="bi bi-cash-coin me-2"></i> Proses Pencairan Dana
        </a>
        
    </div>
</div>

    <?php if (session()->getFlashdata('success')) : ?>
        <div class="alert alert-success border-0 shadow-sm mb-4 small rounded-3 bg-success bg-opacity-10 text-success d-flex align-items-center gap-2">
            <i class="bi bi-check-circle-fill"></i> <span><?= session()->getFlashdata('success') ?></span>
        </div>
    <?php endif; ?>

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
        <div class="card-header bg-white border-0 py-3 px-4">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div class="d-flex align-items-center gap-2">
                    <div class="p-2 rounded-3" style="background-color: rgba(0, 162, 233, 0.08); color: var(--bps-blue);">
                        <i class="bi bi-cpu-fill fs-5"></i>
                    </div>
                    <h6 class="fw-bold mb-0 text-dark tracking-tight">Pemeringkatan Prioritas Kelayakan</h6>
                </div>
                <div>
                    <input type="text" id="cariNama" class="form-control form-control-sm border rounded-3 px-3 py-1.5" style="font-size: 0.82rem; width: 240px;" placeholder="Cari nama anggota...">
                </div>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-premium-header">
                    <tr>
                        <th class="text-center" width="70">Rank</th>
                        <th class="text-start">Informasi Anggota & Pengajuan</th>
                        <th class="text-center" width="150">Tenor Waktu</th>
                        <th class="text-center" width="150">Nilai Kelayakan </th>
                        <th class="text-center" width="220">Track Record Pembayaran</th>
                        <th class="text-center" width="180">Rekomendasi</th>
                        <th class="text-center" width="220">Status</th>
                    </tr>
                </thead>
                <tbody class="table-premium-body" id="bodyTabelNama">
                    <?php if(!empty($pengajuan)): $rank = 1; $colorIndex = 1; foreach($pengajuan as $p): 
                        $initials = strtoupper(substr($p['nama_anggota'], 0, 2));
                        $nilai = (float)$p['nilai_saw'];
                        $tunggakan_riil = (int)($p['tunggakan_bulan_riil'] ?? 0);
                    ?>
                    <tr>
                        <td class="text-center font-monospace fw-bold" style="color: var(--bps-blue-dark); font-size: 1rem;">#<?= $rank++ ?></td>
                        <td class="text-start">
                            <div class="d-flex align-items-center gap-3">
                                <div class="avatar-circle avatar-<?= $colorIndex; ?>"><?= $initials ?></div>
                                <div>
                                    <div class="fw-bold text-dark mb-0.5" style="font-size: 0.88rem;"><?= esc($p['nama_anggota']) ?></div>
                                    <span class="badge bg-light text-secondary border px-2 py-0.5 font-monospace mb-1" style="font-size: 0.65rem; border-radius: 4px;">
                                        Bank: <?= esc($p['nama_bank']) ?> — <?= esc($p['nomor_rekening']) ?>
                                    </span>
                                    <div class="small text-muted mt-0.5" style="font-size: 0.78rem;">Permohonan Kredit: <span class="fw-bold text-dark">Rp <?= number_format($p['jumlah_diajukan'], 0, ',', '.') ?></span></div>
                                </div>
                            </div>
                        </td>
                        
                        <td class="text-center fw-semibold text-secondary font-monospace" style="font-size: 0.9rem;">
                            <?= esc($p['tenor_bulan']) ?> Bulan
                        </td>
                        
                        <td class="text-center">
                            <h4 class="fw-bold mb-0 font-monospace" style="color: var(--bps-blue); font-size: 1.35rem;"><?= number_format($nilai, 2) ?></h4>
                        </td>
                        
                        <td class="text-center">
                            <?php if ($tunggakan_riil == 0) : ?>
                                <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-15 px-3 py-1.5 rounded-pill small fw-bold" style="font-size: 0.72rem;">
                                    <i class="bi bi-shield-check-fill me-1"></i> Sangat Lancar
                                </span>
                            <?php elseif ($tunggakan_riil <= 3) : ?>
                                <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-15 px-3 py-1.5 rounded-pill small fw-bold" style="font-size: 0.72rem;">
                                    <i class="bi bi-info-circle-fill me-1"></i> Telat (<?= $tunggakan_riil ?> bln)
                                </span>
                            <?php elseif ($tunggakan_riil <= 11) : ?>
                                <span class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-15 px-3 py-1.5 rounded-pill small fw-bold" style="font-size: 0.72rem;">
                                    <i class="bi bi-exclamation-triangle-fill me-1"></i> Menunggak (<?= $tunggakan_riil ?> bln)
                                </span>
                            <?php else : ?>
                                <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-15 px-3 py-1.5 rounded-pill small fw-bold" style="font-size: 0.72rem;">
                                    <i class="bi bi-exclamation-circle-fill me-1"></i> Belum Mengangsur (<?= $tunggakan_riil ?> bln)
                                </span>
                            <?php endif; ?>
                        </td>
                        
                        <td class="text-center">
                            <?php 
                                if ($tunggakan_riil >= 12) {
                                    $badgeClass = 'border-color: #ef4444 !important; color: #b91c1c !important; background-color: #fef2f2;';
                                    $labelText  = "Kurang Layak (Macet)";
                                } elseif ($nilai >= 16.0) {
                                    $badgeClass = 'border-color: var(--bps-green) !important; color: #15803d !important; background-color: #f0fdf4;';
                                    $labelText  = "Sangat Layak";
                                } elseif ($nilai >= 11.0) {
                                    $badgeClass = 'border-color: var(--bps-blue) !important; color: #1d4ed8 !important; background-color: #f0f9ff;';
                                    $labelText  = "Layak";
                                } elseif ($nilai >= 6.0) {
                                    $badgeClass = 'border-color: var(--bps-orange) !important; color: #b45309 !important; background-color: #fff7ed;';
                                    $labelText  = "Kurang Layak";
                                } else {
                                    $badgeClass = 'border-color: #ef4444 !important; color: #b91c1c !important; background-color: #fef2f2;';
                                    $labelText  = "Tidak Layak";
                                }
                            ?>
                            <span class="badge border px-3 py-1.5 rounded-pill fw-bold text-uppercase" style="font-size: 0.7rem; <?= $badgeClass ?>">
                                <?= $labelText ?>
                            </span>
                        </td>
                        
                        <td class="text-center">
                            <?php if ($p['status_pengajuan'] === 'proses') : ?>
                                <span class="badge bg-warning text-warning bg-opacity-10 border border-warning px-3 py-2 rounded-3 small fw-bold text-uppercase d-inline-flex align-items-center gap-1">
                                    <i class="bi bi-hourglass-split"></i> Menunggu Validasi
                                </span>
                            <?php else : ?>
                                <?php if ($p['status_pengajuan'] === 'disetujui') : ?>
                                    <span class="badge bg-success text-success bg-opacity-10 border border-success px-3 py-2 rounded-3 small fw-bold text-uppercase d-inline-flex align-items-center gap-1">
                                        <i class="bi bi-check-circle-fill"></i> Disetujui Pimpinan
                                    </span>
                                <?php elseif ($p['status_pengajuan'] === 'transfer') : ?>
                                    <span class="badge bg-info text-info bg-opacity-10 border border-info px-3 py-2 rounded-3 small fw-bold text-uppercase d-inline-flex align-items-center gap-1">
                                        <i class="bi bi-cash-stack"></i> Selesai Cair
                                    </span>
                                <?php else : ?>
                                    <span class="badge bg-danger text-danger bg-opacity-10 border border-danger px-3 py-2 rounded-3 small fw-bold text-uppercase d-inline-flex align-items-center gap-1">
                                        <i class="bi bi-x-circle-fill"></i> Ditolak Pimpinan
                                    </span>
                                <?php endif; ?>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php 
                        $colorIndex = ($colorIndex % 5) + 1;
                        endforeach; else: 
                    ?>
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted small">
                            <i class="bi bi-folder-x d-block fs-2 mb-2 text-secondary"></i>
                            Tidak ada antrean berkas pengajuan pinjaman baru berstatus <b>Proses Evaluasi</b>.
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const inputCari = document.getElementById('cariNama');
        const bodyTabel = document.getElementById('bodyTabelNama');
        
        if (inputCari && bodyTabel) {
            const barisTabel = bodyTabel.getElementsByTagName('tr');

            inputCari.addEventListener('keyup', function(e) {
                const teksPencarian = e.target.value.toLowerCase();
                
                for (let i = 0; i < barisTabel.length; i++) {
                    const kolomNama = barisTabel[i].getElementsByTagName('td')[1];
                    
                    if (kolomNama) {
                        const teksNama = kolomNama.textContent || kolomNama.innerText;
                        
                        if (teksNama.toLowerCase().indexOf(teksPencarian) > -1) {
                            barisTabel[i].style.display = "";
                        } else {
                            barisTabel[i].style.display = "none";
                        }
                    }
                }
            });
        }
    }); 
</script>

<?= $this->endSection(); ?>