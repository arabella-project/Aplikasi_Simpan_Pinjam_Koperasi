<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Dashboard Pimpinan Koperasi' ?></title>
    
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
            --sidebar-bg: #0b1329;       
            --sidebar-active: #3b82f6;   
            --bg-body: #f4f7fe;
        }

        html, body { height: 100vh; width: 100vw; margin: 0; padding: 0; overflow: hidden; background-color: var(--bg-body); font-family: 'Inter', sans-serif; -webkit-font-smoothing: antialiased; }
        .wrapper { display: flex; width: 100vw; height: 100vh; align-items: stretch; }
        
        #sidebar { min-width: 260px; max-width: 260px; background: var(--sidebar-bg); color: #fff; display: flex; flex-direction: column; justify-content: space-between; z-index: 100; padding: 24px 16px; }
        .sidebar-header { padding: 10px 8px 24px 8px; }
        .logo-wrapper { display: flex; align-items: center; gap: 14px; }
        .logo-icon { width: 42px; height: 42px; background: #3b82f6; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.3rem; box-shadow: 0 0 15px rgba(59, 130, 246, 0.65); }
        
        .nav-group-container { display: flex; flex-direction: column; flex-grow: 1; }
        .nav-group { overflow-y: auto; }
        .nav-label { font-size: 10px; text-transform: uppercase; letter-spacing: 1.5px; color: #475569; font-weight: 700; padding-left: 12px; margin: 24px 0 12px 0; }
        .nav-link { display: flex; align-items: center; gap: 14px; color: #94a3b8; text-decoration: none; padding: 12px 14px; border-radius: 12px; font-size: 0.9rem; font-weight: 500; transition: all 0.2s; margin-bottom: 6px; }
        .nav-link:hover { background: rgba(255, 255, 255, 0.02); color: #cbd5e1; }
        
        .nav-link.active { background: var(--sidebar-active); color: #fff; font-weight: 600; box-shadow: 0 4px 20px rgba(59, 130, 246, 0.45); } 
        .nav-link i { font-size: 1.15rem; }
        
        #content { flex-grow: 1; height: 100vh; padding: 24px 30px; display: flex; flex-direction: column; overflow: hidden; }
        .dashboard-card { border: 1px solid #e2e8f0; border-radius: 16px; background: #ffffff; padding: 20px 24px; box-shadow: 0 4px 18px rgba(30, 41, 59, 0.02); }
        .metric-title { font-size: 0.68rem; font-weight: 700; text-transform: uppercase; color: #64748b; letter-spacing: 0.5px; }
        .metric-value { font-size: 1.5rem; font-weight: 700; margin-top: 4px; margin-bottom: 0; color: #1e293b; }
        
        .circle-badge-icon { width: 42px; height: 42px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.2rem; }
        .icon-blue { background-color: #e0f2fe; color: #00a2e9; }
        .icon-green { background-color: #f0fdf4; color: #4aa135; }
        .icon-orange { background-color: #fff7ed; color: #f48221; }
        
        .row-bottom-workspace { flex: 1; min-height: 0; }
        .scrollbox-left-table { flex: 1; overflow-y: auto; max-height: calc(100vh - 395px); }
        .scrollbox-right-list { flex: 1; overflow-y: auto; max-height: calc(100vh - 395px); padding-right: 4px; }
        
        .table-premium th { font-weight: 700; color: #475569; font-size: 0.72rem; text-transform: uppercase; letter-spacing: 0.8px; padding: 14px 16px; background-color: #f8fafc; border-bottom: 2px solid #e2e8f0; position: sticky; top: 0; z-index: 2; }
        .table-premium td { padding: 14px 16px; font-size: 0.85rem; color: #1e293b; border-bottom: 1px solid #f1f5f9; }
        .table-premium tbody tr:hover { background-color: #f8fafc; }
        
        .avatar-mini { width: 34px; height: 34px; border-radius: 50%; display: flex; align-items: center; justify-content: center; background: #e0f2fe; color: #0369a1; font-size: 11px; font-weight: 700; }
        .badge-premium-pill { padding: 6px 14px; font-size: 0.68rem; font-weight: 700; letter-spacing: 0.5px; border-radius: 30px; text-transform: uppercase; }
        .text-primary-hover:hover { color: var(--sidebar-active) !important; }
    </style>
</head>
<body>

<div class="wrapper">
    <nav id="sidebar">
        <div class="nav-group-container">
            <div class="sidebar-header">
                <div class="logo-wrapper">
                    <div class="logo-icon"><i class="bi bi-stack text-white"></i></div>
                    <div>
                        <h6 class="mb-0 fw-bold text-white" style="letter-spacing: 0.8px; font-size: 0.95rem;">KOPERASI BPS</h6>
                        <small class="text-muted" style="font-size: 10px; opacity: 0.4; display: block; margin-top: 2px;">Admin Panel v1.0</small>
                    </div>
                </div>
            </div>
            
            <div class="nav-group">
                <div class="nav-label">Main Menu</div>
                <a href="<?= base_url('ketua/dashboard') ?>" class="nav-link active"><i class="bi bi-grid-1x2-fill"></i><span>Dashboard</span></a>
                
                <div class="nav-label">Otorisasi Berkas</div>
                <a href="<?= base_url('ketua/pengajuan') ?>" class="nav-link"><i class="bi bi-clipboard2-check-fill"></i><span>Persetujuan Pinjaman</span></a>
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
        <div class="d-flex justify-content-between align-items-center mb-4 bg-white p-3 rounded-4 border shadow-sm">
            <div>
                <h5 class="fw-bold text-dark mb-0" style="font-size: 1.05rem;">
                    Selamat Datang, <?= esc(session()->get('nama_lengkap') ?? 'Pimpinan Koperasi') ?>
                </h5>
            </div>
            <div class="d-flex align-items-center gap-3">
                <span class="badge bg-light text-secondary border p-2.5 rounded-3 font-monospace" style="font-size: 11px; font-weight: 500;">
                    <i class="bi bi-calendar3 me-2 text-primary"></i> Juni 2026
                </span>
                <div class="avatar-mini bg-primary bg-opacity-10 text-primary fw-bold">
                    <?= strtoupper(substr(session()->get('nama_lengkap') ?? 'P', 0, 2)) ?>
                </div>
            </div>
        </div>

        <div class="row g-3 mb-4">
            <div class="col-12 col-md-4">
                <div class="dashboard-card d-flex align-items-center justify-content-between">
                    <div>
                        <span class="metric-title">Total Simpanan Pokok</span>
                        <div class="metric-value">Rp <?= number_format($total_simpanan, 0, ',', '.') ?></div>
                    </div>
                    <div class="circle-badge-icon icon-blue"><i class="bi bi-bank"></i></div>
                </div>
            </div>
            <div class="col-12 col-md-4">
                <div class="dashboard-card d-flex align-items-center justify-content-between">
                    <div>
                        <span class="metric-title">Angsuran Bulan Ini</span>
                        <div class="metric-value">Rp <?= number_format($angsuran_bulan_ini, 0, ',', '.') ?></div>
                    </div>
                    <div class="circle-badge-icon icon-green"><i class="bi bi-graph-up-arrow"></i></div>
                </div>
            </div>
            <div class="col-12 col-md-4">
                <div class="dashboard-card d-flex align-items-center justify-content-between">
                    <div>
                        <span class="metric-title">Anggota Koperasi</span>
                        <div class="metric-value"><?= $total_anggota ?> Orang</div>
                    </div>
                    <div class="circle-badge-icon icon-orange"><i class="bi bi-people-fill"></i></div>
                </div>
            </div>
        </div>

        <div class="row g-3 row-bottom-workspace flex-grow-1">
            <div class="col-12 col-xl-8 h-100 d-flex flex-column">
                <div class="card p-0 border shadow-sm rounded-4 overflow-hidden h-100 d-flex flex-column justify-content-between bg-white">
                    <div class="d-flex flex-column" style="min-height: 0; flex: 1;">
                        <div class="p-3 bg-white d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center gap-2">
                                <div class="icon-blue circle-badge-icon" style="width:32px; height:32px; font-size:0.95rem; border-radius:8px;"><i class="bi bi-file-earmark-text"></i></div>
                                <div>
                                    <h6 class="fw-bold text-dark mb-0" style="font-size: 0.88rem;">Antrean Validasi Pengajuan Pinjaman Anggota</h6>
                                </div>
                            </div>
                            <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-2.5 py-1 fw-bold" style="font-size: 0.65rem;">Active Queue</span>
                        </div>
                        
                        <div class="table-responsive scrollbox-left-table border-top">
                            <table class="table table-hover align-middle mb-0 table-premium">
                                <thead>
                                    <tr>
                                        <th class="text-center" width="55">Rank</th>
                                        <th>Nama Anggota</th>
                                        <th class="text-center" width="100">Tenor</th>
                                        <th class="text-center" width="110">Skor Kelayakan</th>
                                        <th class="text-end" width="150">Dana Diajukan</th>
                                        <th class="text-center" width="140">Rekomendasi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if(!empty($list_antrean_pinjaman)) : $rank = 1; foreach($list_antrean_pinjaman as $lp) : 
                                        $nilai = (float)$lp['nilai_saw'];
                                        $tunggakan = (int)($lp['tunggakan_bulan_riil'] ?? 0);
                                        if ($tunggakan >= 12) { $badge = "bg-danger text-danger bg-opacity-10 border-danger-subtle"; $label = "Kurang Layak (Macet)"; }
                                        elseif ($nilai >= 16.0) { $badge = "bg-success text-success bg-opacity-10 border-success-subtle"; $label = "Sangat Layak"; }
                                        elseif ($nilai >= 12.0) { $badge = "bg-primary text-primary bg-opacity-10 border-primary-subtle"; $label = "Layak"; }
                                        else { $badge = "bg-danger text-danger bg-opacity-10 border-danger-subtle"; $label = "Kurang Layak"; }
                                    ?>
                                    <tr>
                                        <td class="text-center font-monospace fw-bold text-secondary"><?= $rank++ ?></td>
                                        <td>
                                            <!-- 🟢 FIX: Diarahkan ke rute 'fitur/buka_pinjaman' yang sah -->
                                            <a href="<?= base_url('ketua/fitur/buka_pinjaman/' . $lp['id_pengajuan']) ?>" class="text-decoration-none text-dark d-block">
                                                <div class="fw-bold text-dark text-primary-hover" style="font-size: 0.85rem;"><?= esc($lp['nama_anggota']) ?> <i class="bi bi-box-arrow-up-right small text-muted ms-1" style="font-size: 10px;"></i></div>
                                            </a>
                                            <small class="text-primary font-monospace fw-semibold" style="font-size:11px;">ID: #<?= esc($lp['id_anggota']) ?></small>
                                        </td>
                                        <td class="text-center fw-semibold text-secondary font-monospace"><?= esc($lp['tenor_bulan']) ?> Bln</td>
                                        <td class="text-center text-primary font-monospace fw-bold"><?= number_format($nilai, 2) ?></td>
                                        <td class="text-end fw-bold font-monospace text-dark">Rp <?= number_format($lp['jumlah_diajukan'], 0, ',', '.') ?></td>
                                        <td class="text-center"><span class="badge border badge-premium-pill <?= $badge ?>"><?= $label ?></span></td>
                                    </tr>
                                    <?php endforeach; else : ?>
                                    <tr><td colspan="6" class="text-center py-5 text-muted small"><i class="bi bi-check2-circle fs-2 d-block text-success mb-2"></i>Semua antrean berkas pengajuan pinjaman selesai ditinjau.</td></tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="p-2 border-top text-end bg-light">
                        <!-- 🟢 FIX: Diarahkan ke rute 'fitur/buka_semua_pinjaman' yang sah -->
                        <a href="<?= base_url('ketua/fitur/buka_semua_pinjaman') ?>" class="text-decoration-none fw-bold small text-primary font-monospace p-2 d-inline-block" style="font-size:11px;">Validasi Pengajuan Sekarang <i class="bi bi-arrow-right"></i></a>
                    </div>
                </div>
            </div>

            <div class="col-12 col-xl-4 h-100 d-flex flex-column">
                <div class="card p-3 border shadow-sm rounded-4 overflow-hidden h-100 d-flex flex-column justify-content-between bg-white">
                    <div class="d-flex flex-column" style="min-height: 0; flex: 1;">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div>
                                <span class="badge bg-warning bg-opacity-10 text-warning border border-warning-subtle px-2.5 py-0.5 rounded-pill fw-bold text-uppercase font-monospace mb-2" style="font-size: 0.58rem;">● Perlu Validasi</span>
                                <div class="d-flex align-items-baseline gap-2">
                                    <h2 class="fw-bold text-dark mb-0 font-monospace" style="font-size: 1.8rem;"><?= !empty($list_antrean_penarikan) ? count($list_antrean_penarikan) : 0 ?></h2>
                                    <h6 class="fw-bold text-secondary mb-0" style="font-size: 0.82rem;">Permohonan Penarikan</h6>
                                </div>
                            </div>
                            <div class="text-muted"><i class="bi bi-envelope-paper fs-4 text-secondary"></i></div>
                        </div>
                        
                        <div class="scrollbox-right-list mt-2">
                            <div class="d-flex flex-column gap-2">
                                <?php if(!empty($list_antrean_penarikan)) : foreach($list_antrean_penarikan as $lt) : 
                                    $id_penarikan_val = $lt['id_penarikan'] ?? ($lt['id_pengajuan_penarikan'] ?? 0);
                                    $init = strtoupper(substr($lt['nama_anggota'] ?? 'U', 0, 2));
                                ?>
                                <!-- 🟢 FIX: Diarahkan ke rute 'fitur/buka_penarikan' yang sah -->
                                <a href="<?= base_url('ketua/fitur/buka_penarikan/' . $id_penarikan_val) ?>" class="text-decoration-none d-block">
                                    <div class="p-2 border border-light-subtle rounded-3 d-flex align-items-center justify-content-between bg-light bg-opacity-50 text-dark">
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="avatar-mini font-monospace"><?= $init ?></div>
                                            <div>
                                                <div class="fw-bold text-primary-hover" style="font-size:0.8rem;"><?= esc($lt['nama_anggota']) ?> <i class="bi bi-box-arrow-up-right small text-muted" style="font-size: 8px;"></i></div>
                                                <small class="text-muted" style="font-size:10px;">Menunggu Keputusan</small>
                                            </div>
                                        </div>
                                        <div class="fw-bold font-monospace" style="font-size:0.82rem;">Rp <?= number_format($lt['jumlah_ditarik'] ?? 0, 0, ',', '.') ?></div>
                                    </div>
                                </a>
                                <?php endforeach; else : ?>
                                <div class="text-center text-muted small py-5"><i class="bi bi-shield-check text-success fs-3 d-block mb-2"></i>Tidak ada antrean validasi penarikan.</div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-2 w-100">
                        <!-- 🟢 FIX: Diarahkan ke rute 'fitur/buka_semua_penarikan' yang sah -->
                        <a href="<?= base_url('ketua/fitur/buka_semua_penarikan') ?>" class="btn w-100 text-center fw-bold font-monospace p-2 rounded-3 text-warning border border-warning bg-warning bg-opacity-10 shadow-sm" style="font-size: 0.82rem; border-style: dashed !important;">
                            Validasi Penarikan Sekarang <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const btn = document.querySelector('.logout-trigger');
    if(btn) {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            Swal.fire({
                title: 'Konfirmasi Keluar', text: 'Apakah Anda yakin ingin keluar dari sistem pimpinan?', icon: 'warning',
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