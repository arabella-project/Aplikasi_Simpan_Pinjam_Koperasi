<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Persetujuan Pinjaman | Ketua Koperasi' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <style>
        :root { 
            --bps-blue: #00a2e9; 
            --bps-blue-gradient: linear-gradient(135deg, #00a2e9 0%, #0082c8 100%);
            --bps-green: #4aa135;
            --bps-orange: #f48221;
            
            /* SINKRONISASI WARNA SIDEBAR (DEEP NAVY DARK) */
            --sidebar-bg: #0b1329;       /* Latar gelap pekat sesuai gambar */
            --sidebar-active: #3b82f6;   /* Biru cerah menyala untuk menu aktif */
            --bg-light: #f4f7fe;
            --text-dark: #1e293b; 
        }
        html, body { height: 100vh; width: 100vw; margin: 0; padding: 0; overflow: hidden; background-color: var(--bg-light); font-family: 'Inter', sans-serif; -webkit-font-smoothing: antialiased; }
        .wrapper { display: flex; width: 100vw; height: 100vh; align-items: stretch; }
        
        /* PANEL NAVIGATION SIDEBAR GELAP */
        #sidebar { 
            min-width: 260px; 
            max-width: 260px; 
            background: var(--sidebar-bg); 
            color: #fff; 
            display: flex; 
            flex-direction: column; 
            justify-content: space-between; 
            z-index: 100; 
            padding: 24px 16px;
        }
        .sidebar-header { padding: 10px 8px 24px 8px; }
        .logo-wrapper { display: flex; align-items: center; gap: 14px; }
        .logo-icon { width: 42px; height: 42px; background: #3b82f6; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.3rem; box-shadow: 0 0 15px rgba(59, 130, 246, 0.65); }
        
        .nav-group-container { display: flex; flex-direction: column; flex-grow: 1; }
        .nav-group { overflow-y: auto; }
        .nav-label { font-size: 10px; text-transform: uppercase; letter-spacing: 1.5px; color: #475569; font-weight: 700; padding-left: 12px; margin: 24px 0 12px 0; }
        .nav-link { display: flex; align-items: center; gap: 14px; color: #94a3b8; text-decoration: none; padding: 12px 14px; border-radius: 12px; font-size: 0.9rem; font-weight: 500; transition: all 0.2s; margin-bottom: 6px; }
        .nav-link:hover { background: rgba(255, 255, 255, 0.02); color: #cbd5e1; }
        
        /* Menu aktif dengan efek bersinar */
        .nav-link.active { background: var(--sidebar-active); color: #fff; font-weight: 600; box-shadow: 0 4px 20px rgba(59, 130, 246, 0.45); } 
        .nav-link i { font-size: 1.15rem; }
        
        /* WORKSPACE CONTENT LAYOUT */
        #content { flex-grow: 1; height: 100vh; padding: 24px 30px; display: flex; flex-direction: column; overflow: hidden; }
        .scrollable-table-wrapper { flex: 1; overflow-y: auto; overflow-x: auto; max-height: calc(100vh - 240px); border-radius: 16px; background: #ffffff; border: 1px solid rgba(226, 232, 240, 0.8); box-shadow: 0 4px 18px rgba(30, 41, 59, 0.015); }
        .table-premium th { position: sticky !important; top: 0 !important; z-index: 10; background-color: #f8fafc !important; color: #475569 !important; font-weight: 700; font-size: 0.72rem; text-transform: uppercase; letter-spacing: 0.8px; padding: 14px 16px; border-bottom: 2px solid #e2e8f0; }
        .table-premium td { padding: 14px 16px; font-size: 0.88rem; color: #334155; border-bottom: 1px solid #f1f5f9; }
        .table-premium tbody tr:hover { background-color: #f8fafc; }
        .badge-premium-pill { padding: 6px 14px; font-size: 0.68rem; font-weight: 700; letter-spacing: 0.5px; border-radius: 30px; text-transform: uppercase; }
        .nav-tabs-premium .nav-link { border: none; color: #64748b; font-weight: 500; padding: 10px 20px; font-size: 0.9rem; border-radius: 8px 8px 0 0; }
        .nav-tabs-premium .nav-link.active { color: var(--bps-blue); border-bottom: 3px solid var(--bps-blue); font-weight: 600; background: transparent; }
    </style>
</head>
<body>

<div class="wrapper">
    <nav id="sidebar">
        <div class="nav-group-container">
            <div class="sidebar-header">
                <div class="logo-wrapper">
                    <div class="logo-icon">
                        <i class="bi bi-stack text-white"></i>
                    </div>
                    <div>
                        <h6 class="mb-0 fw-bold text-white" style="letter-spacing: 0.8px; font-size: 0.95rem;">KOPERASI BPS</h6>
                        <small class="text-muted" style="font-size: 10px; opacity: 0.4; display: block; margin-top: 2px;">Admin Panel v1.0</small>
                    </div>
                </div>
            </div>
            
            <div class="nav-group">
                <div class="nav-label">Main Menu</div>
                <a href="<?= base_url('ketua/dashboard') ?>" class="nav-link"><i class="bi bi-grid-1x2-fill"></i><span>Dashboard</span></a>
                
                <div class="nav-label">Otorisasi Berkas</div>
                <a href="<?= base_url('ketua/pengajuan') ?>" class="nav-link active"><i class="bi bi-clipboard2-check-fill"></i><span>Persetujuan Pinjaman</span></a>
                <a href="<?= base_url('ketua/penarikan') ?>" class="nav-link"><i class="bi bi-wallet2"></i><span>Persetujuan Penarikan</span></a>
            </div>
        </div>
        
        <div style="padding: 0 12px; margin-top: auto;">
            <a href="#" class="nav-link logout-trigger" style="color: #f87171; background: rgba(248, 113, 113, 0.05); margin-bottom: 0;">
                <i class="bi bi-box-arrow-right"></i><span>Keluar Sistem</span>
            </a>
        </div>
    </nav>

    <div id="content">
        <div class="mb-3">
            <h4 class="fw-bold text-dark tracking-tight mb-1">Validasi Pengajuan Pinjaman</h4>
        </div>

        <?php if (session()->getFlashdata('success')) : ?>
            <div class="alert alert-success border-0 small rounded-3 bg-success bg-opacity-10 text-success p-3 mb-3 d-flex align-items-center gap-2">
                <i class="bi bi-check-circle-fill"></i> <span><?= session()->getFlashdata('success') ?></span>
            </div>
        <?php endif; ?>

        <ul class="nav nav-tabs nav-tabs-premium mb-3" id="pinjamanTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="antrean-p-tab" data-bs-toggle="tab" data-bs-target="#antrean-p-pane" type="button" role="tab"><i class="bi bi-hourglass-split me-2"></i>Berkas Pending</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="riwayat-p-tab" data-bs-toggle="tab" data-bs-target="#riwayat-p-pane" type="button" role="tab"><i class="bi bi-clock-history me-2"></i>Riwayat Keputusan</button>
            </li>
        </ul>

        <div class="tab-content" id="pinjamanTabContent">
            <div class="tab-pane fade show active" id="antrean-p-pane" role="tabpanel">
                <div class="scrollable-table-wrapper shadow-sm">
                    <table class="table align-middle mb-0 table-premium">
                        <thead>
                            <tr>
                                <th width="65" class="text-center">Rank</th>
                                <th>Nama Pemohon</th>
                                <th class="text-center" width="250">Track Record Pembayaran</th>
                                <th class="text-end" width="160">Nominal Dana</th>
                                <th class="text-center" width="150">Skor Kelayakan</th>
                                <th class="text-center" width="140">Rekomendasi</th>
                                <th class="text-center" width="160">Progress Board</th>
                                <th width="180" class="text-center">Keputusan Anda</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $rank = 1; $hasPending = false;
                            if (!empty($antrean)) : foreach ($antrean as $row) : 
                                if ($row['status_pengajuan'] !== 'proses') continue;
                                $hasPending = true;

                                $tunggakan = (int)($row['tunggakan_bulan_riil'] ?? 0);
                                if ($tunggakan === 0) {
                                    $tStyle = 'border: 1.5px solid #16a34a !important; color: #16a34a !important; background-color: #f0fdf4; font-weight: 600; padding: 5px 16px; border-radius: 50px; font-size: 0.78rem; display: inline-block;';
                                    $tLabel = "Sangat Lancar";
                                } elseif ($tunggakan <= 3) {
                                    $tStyle = 'border: 1.5px solid #ea580c !important; color: #ea580c !important; background-color: #fff7ed; font-weight: 600; padding: 5px 16px; border-radius: 50px; font-size: 0.78rem; display: inline-block;';
                                    $tLabel = "Meragukan ({$tunggakan} Bln)";
                                } else {
                                    $tStyle = 'border: 1.5px solid #dc2626 !important; color: #dc2626 !important; background-color: #fef2f2; font-weight: 600; padding: 5px 16px; border-radius: 50px; font-size: 0.78rem; display: inline-block;';
                                    $tLabel = "Macet ({$tunggakan} Bln)";
                                }

                                $nilai = (float)$row['nilai_saw'];
                                if ($nilai >= 16.0) { 
                                    $bClass = 'border: 1.5px solid #16a34a !important; color: #16a34a !important; background-color: #f0fdf4; padding: 5px 14px; border-radius: 50px; font-size: 0.75rem;'; 
                                    $lbl = "SANGAT LAYAK"; 
                                } elseif ($nilai >= 11.0) { 
                                    $bClass = 'border: 1.5px solid #2563eb !important; color: #2563eb !important; background-color: #f0f9ff; padding: 5px 14px; border-radius: 50px; font-size: 0.75rem;'; 
                                    $lbl = "LAYAK"; 
                                } else { 
                                    $bClass = 'border: 1.5px solid #dc2626 !important; color: #dc2626 !important; background-color: #fef2f2; padding: 5px 14px; border-radius: 50px; font-size: 0.75rem;'; 
                                    $lbl = "TIDAK LAYAK"; 
                                }
                            ?>
                            <tr>
                                <td class="text-center font-monospace fw-bold text-secondary">#<?= $rank++ ?></td>
                                <td>
                                    <div class="fw-bold text-dark" style="font-size: 0.88rem;"><?= esc($row['nama_anggota']) ?></div>
                                    <small class="text-muted font-monospace" style="font-size: 11px;">Tenor: <?= esc($row['tenor_bulan']) ?> Bln</small>
                                </td>
                                <td>
                                    <span style="<?= $tStyle ?>"><?= $tLabel ?></span>
                                </td>
                                <td class="text-end fw-bold font-monospace text-dark">Rp <?= number_format($row['jumlah_diajukan'], 0, ',', '.') ?></td>
                                <td class="text-center text-primary font-monospace fw-bold" style="font-size: 0.95rem;"><?= number_format($nilai, 2) ?></td>
                                <td class="text-center">
                                    <span class="badge fw-bold" style="<?= $bClass ?>"><?= $lbl ?></span>
                                </td>
                                <td class="text-center">
                                    <div class="progress" style="height: 6px; border-radius: 10px; background-color: #e2e8f0;">
                                        <div class="progress-bar bg-success" style="width: <?= ($row['vote_setuju']/3)*100 ?>%"></div>
                                        <div class="progress-bar bg-danger" style="width: <?= ($row['vote_tolak']/3)*100 ?>%"></div>
                                    </div>
                                    <small class="text-muted font-monospace d-block mt-1" style="font-size:10px;">Setuju: <?= $row['vote_setuju'] ?> | Tolak: <?= $row['vote_tolak'] ?></small>
                                </td>
                                <td class="text-center">
                                    <?php if ($row['user_sudah_vote']) : ?>
                                        <span class="badge bg-secondary bg-opacity-10 text-secondary border p-2 rounded-3 small fw-bold"><i class="bi bi-lock-fill me-1"></i> Terbaca (Voted)</span>
                                    <?php else : ?>
                                        <div class="d-flex gap-2 justify-content-center">
                                            <button type="button" class="btn btn-sm btn-success px-2.5 rounded-3 fw-bold" style="font-size:0.8rem;" onclick="konfirmasiVeto('<?= $row['id_pengajuan'] ?>', 'setuju', '<?= esc($row['nama_anggota']) ?>')">Setuju</button>
                                            <button type="button" class="btn btn-sm btn-danger px-2.5 rounded-3 fw-bold" style="font-size:0.8rem;" onclick="konfirmasiVeto('<?= $row['id_pengajuan'] ?>', 'tolak', '<?= esc($row['nama_anggota']) ?>')">Tolak</button>
                                        </div>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; endif; ?>
                            <?php if (!$hasPending) : ?>
                            <tr><td colspan="8" class="text-center py-5 text-muted small"><i class="bi bi-folder-x fs-3 d-block mb-2 text-secondary"></i>Tidak ada antrean pengajuan baru berjalan.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
              
            <div class="tab-pane fade" id="riwayat-p-pane" role="tabpanel">
                <div class="scrollable-table-wrapper shadow-sm">
                    <table class="table align-middle mb-0 table-premium">
                        <thead>
                            <tr>
                                <th width="60" class="text-center">No</th>
                                <th>Nama Pemohon</th>
                                <th class="text-center" width="250">Track Record Pembayaran</th>
                                <th class="text-end" width="160">Nominal Dana</th>
                                <th class="text-center" width="150">Skor Kelayakan</th>
                                <th class="text-center" width="150">Hasil Final Vote</th>
                                <th width="160" class="text-center">Status Akhir</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $no = 1; $hasHistory = false;
                            if (!empty($antrean)) : foreach ($antrean as $row) : 
                                if ($row['status_pengajuan'] === 'proses') continue;
                                $hasHistory = true;

                                $tunggakan = (int)($row['tunggakan_bulan_riil'] ?? 0);
                                if ($tunggakan === 0) {
                                    $tStyle = 'border: 1.5px solid #16a34a !important; color: #16a34a !important; background-color: #f0fdf4; font-weight: 600; padding: 5px 16px; border-radius: 50px; font-size: 0.78rem; display: inline-block;';
                                    $tLabel = "Sangat Lancar";
                                } elseif ($tunggakan <= 3) {
                                    $tStyle = 'border: 1.5px solid #ea580c !important; color: #ea580c !important; background-color: #fff7ed; font-weight: 600; padding: 5px 16px; border-radius: 50px; font-size: 0.78rem; display: inline-block;';
                                    $tLabel = "Meragukan ({$tunggakan} Bln)";
                                } else {
                                    $tStyle = 'border: 1.5px solid #dc2626 !important; color: #dc2626 !important; background-color: #fef2f2; font-weight: 600; padding: 5px 16px; border-radius: 50px; font-size: 0.78rem; display: inline-block;';
                                    $tLabel = "Macet ({$tunggakan} Bln)";
                                }
                            ?>
                            <tr>
                                <td class="text-center font-monospace text-secondary"><?= $no++ ?></td>
                                <td>
                                    <div class="fw-bold text-dark" style="font-size: 0.88rem;"><?= esc($row['nama_anggota']) ?></div>
                                    <small class="text-muted font-monospace" style="font-size: 11px;">ID Anggota: #<?= esc($row['id_anggota']) ?></small>
                                </td>
                                <td>
                                    <span style="<?= $tStyle ?>"><?= $tLabel ?></span>
                                </td>
                                <td class="text-end fw-bold font-monospace text-dark">Rp <?= number_format($row['jumlah_diajukan'], 0, ',', '.') ?></td>
                                <td class="text-center font-monospace fw-bold text-primary" style="font-size: 0.95rem;"><?= number_format((float)$row['nilai_saw'], 2) ?></td>
                                <td class="text-center font-monospace small text-muted">
                                    Setuju (<?= $row['vote_setuju'] ?>) — Tolak (<?= $row['vote_tolak'] ?>)
                                </td>
                                <td class="text-center">
                                    <?php if (in_array($row['status_pengajuan'], ['disetujui', 'transfer'])) : ?>
                                        <span class="badge bg-success text-success bg-opacity-10 border border-success px-3 py-2 rounded-3 small fw-bold text-uppercase"><i class="bi bi-shield-fill-check me-1"></i> Disetujui</span>
                                    <?php else : ?>
                                        <span class="badge bg-danger text-danger bg-opacity-10 border border-danger px-3 py-2 rounded-3 small fw-bold text-uppercase"><i class="bi bi-shield-fill-x me-1"></i> Ditolak</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; endif; ?>
                            <?php if (!$hasHistory) : ?>
                            <tr><td colspan="7" class="text-center py-5 text-muted small"><i class="bi bi-clock-history fs-3 d-block mb-2 text-secondary"></i>Belum ada data riwayat keputusan final pengajuan pinjaman.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<form id="formVetoKetua" method="POST" style="display: none;"><?= csrf_field() ?></form>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
function konfirmasiVeto(id, aksi, nama) {
    const form = document.getElementById('formVetoKetua');
    const warnaBtn = (aksi === 'setuju') ? '#3b82f6' : '#ef4444';

    Swal.fire({
        title: 'Kirim Vote Otorisasi?',
        html: `Apakah Anda yakin memilih <strong>${aksi.toUpperCase()}</strong> untuk pinjaman <strong>${nama}</strong>?`,
        icon: 'question', showCancelButton: true,
        confirmButtonText: 'Kirim Vote', cancelButtonText: 'Batal',
        confirmButtonColor: warnaBtn, cancelButtonColor: '#cbd5e1', customClass: { popup: 'rounded-4' }
    }).then((result) => {
        if (result.isConfirmed) {
            form.setAttribute('action', `<?= base_url('ketua/pengajuan/vote_pinjaman') ?>/${id}/${aksi}`);
            form.submit();
        }
    });
}

document.addEventListener('DOMContentLoaded', function() {
    const btn = document.querySelector('.logout-trigger');
    if(btn) {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            Swal.fire({
                title: 'Konfirmasi Keluar', text: 'Apakah Anda yakin ingin keluar?', icon: 'warning',
                showCancelButton: true, confirmButtonColor: '#ef4444', cancelButtonColor: '#cbd5e1',
                confirmButtonText: 'Ya, Keluar', cancelButtonText: 'Batal', customClass: { popup: 'rounded-4' }
            }).then((result) => {
                if (result.isConfirmed) { window.location.href = "<?= base_url('logout') ?>"; }
            });
        });
    }
});
</script>
</body>
</html>