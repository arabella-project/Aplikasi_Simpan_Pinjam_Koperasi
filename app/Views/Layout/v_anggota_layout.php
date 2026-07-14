<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Koperasi BPS' ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        body { background-color: #f6f9fc; font-family: 'Plus Jakarta Sans', sans-serif; color: #334155; overflow-x: hidden; }
        .wrapper { display: flex; align-items: stretch; min-height: 100vh; }
        #sidebar { min-width: 260px; max-width: 260px; background-color: #0b1a30; color: #fff; transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); display: flex; flex-direction: column; box-shadow: 4px 0 24px rgba(11, 26, 48, 0.08); }
        .sidebar-brand { padding: 32px 24px; border-bottom: 1px solid rgba(255, 255, 255, 0.04); background-color: #081426; }
        .sidebar-logo-text { font-size: 1.15rem; font-weight: 700; color: #ffffff; letter-spacing: 0.7px; }
        .sidebar-sub-logo { font-size: 0.78rem; color: #94a3b8; font-weight: 500; }
        .icon-logo-wrapper { width: 42px; height: 42px; background: linear-gradient(135deg, #0066ff 0%, #0052cc 100%); border-radius: 12px; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 12px rgba(0, 102, 255, 0.2); transition: transform 0.3s ease; }
        .sidebar-brand:hover .icon-logo-wrapper { transform: scale(1.05); }
        .sidebar-menu { padding: 24px 0; flex-grow: 1; }
        .sidebar-menu ul { list-style: none; padding: 0; margin: 0; }
        .sidebar-menu ul li { margin: 6px 18px; position: relative; }
        .sidebar-menu ul li a { padding: 12px 18px; font-size: 0.9rem; font-weight: 500; color: #94a3b8; text-decoration: none; display: flex; align-items: center; transition: all 0.25s ease-in-out; border-radius: 12px; }
        .sidebar-menu ul li a i { font-size: 1.25rem; margin-right: 14px; transition: transform 0.25s ease; }
        .sidebar-menu ul li a:hover { color: #ffffff; background: rgba(255, 255, 255, 0.03); }
        .sidebar-menu ul li a:hover i { transform: translateX(3px); }
        .sidebar-menu ul li.active a { color: #ffffff; background: linear-gradient(135deg, #0066ff 0%, #0052cc 100%); font-weight: 600; box-shadow: 0 4px 14px rgba(0, 102, 255, 0.3); }
        .sidebar-menu ul li.active a i { color: #38bdf8; }
        .sidebar-footer { padding: 20px 24px; background: #081426; border-top: 1px solid rgba(255, 255, 255, 0.04); }
        .btn-logout-sidebar { width: 100%; padding: 11px; border-radius: 12px; background: rgba(239, 68, 68, 0.06); border: 1px solid rgba(239, 68, 68, 0.15); color: #f87171; font-weight: 600; font-size: 0.88rem; display: flex; align-items: center; justify-content: center; gap: 10px; transition: all 0.2s ease; cursor: pointer; }
        .btn-logout-sidebar:hover { background: #ef4444; color: #ffffff; box-shadow: 0 4px 12px rgba(239, 68, 68, 0.2); border-color: #ef4444; }
        #content { width: 100%; display: flex; flex-direction: column; min-height: 100vh; }
        .top-navbar { background: #ffffff; padding: 16px 40px; box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02), 0 4px 12px rgba(13, 27, 42, 0.01); display: flex; justify-content: space-between; align-items: center; z-index: 10; }
        .navbar-profile-left { display: flex; align-items: center; gap: 16px; }
        .nav-avatar-box { width: 46px; height: 46px; background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%); color: #2563eb; display: flex; align-items: center; justify-content: center; border-radius: 14px; font-size: 1.4rem; border: 1px solid #bfdbfe; }
        .user-welcome-title { font-size: 1.05rem; font-weight: 700; color: #0f172a; margin-bottom: 2px; }
        .user-welcome-sub { font-size: 0.78rem; color: #64748b; font-weight: 500; }
        .nav-action-btn { width: 42px; height: 42px; background: #ffffff; border-radius: 12px; border: 1px solid #e2e8f0; display: flex; align-items: center; justify-content: center; position: relative; cursor: pointer; transition: all 0.2s ease; color: #64748b; }
        .nav-session-card { background: #ffffff; padding: 6px 14px; border-radius: 12px; border: 1px solid #e2e8f0; display: flex; align-items: center; gap: 10px; }
        .main-body-container { padding: 40px; flex-grow: 1; }
    </style>
</head>

<body>
    <div class="wrapper">
        <nav id="sidebar">
            <div class="sidebar-brand">
                <div class="d-flex align-items-center gap-3">
                    <div class="icon-logo-wrapper text-white">
                        <i class="bi bi-building-fill fs-5"></i>
                    </div>
                    <div>
                        <div class="sidebar-logo-text">KOPERASI BPS</div>
                        <div class="sidebar-sub-logo"></div>
                    </div>
                </div>
            </div>

            <div class="sidebar-menu">
                <ul>
                    <li class="<?= url_is('anggota/dashbord') ? 'active' : '' ?>">
                        <a href="<?= base_url('anggota/dashbord') ?>"><i class="bi bi-grid-1x2-fill"></i> Dashboard</a>
                    </li>
                    <li class="<?= url_is('anggota/simpanan*') ? 'active' : '' ?>">
                        <a href="<?= base_url('anggota/simpanan') ?>"><i class="bi bi-wallet2"></i> Simpanan Saya</a>
                    </li>
                    <li class="<?= url_is('anggota/pinjaman*') ? 'active' : '' ?>">
                        <a href="<?= base_url('anggota/pinjaman') ?>"><i class="bi bi-credit-card-2-front-fill"></i> Pinjaman Saya</a>
                    </li>
                    <li class="<?= url_is('anggota/pengajuan*') ? 'active' : '' ?>">
                        <a href="<?= base_url('anggota/pengajuan') ?>"><i class="bi bi-file-earmark-check-fill"></i> Ajukan Pinjaman Baru</a>
                    </li>
                    <li class="<?= url_is('anggota/penarikan*') ? 'active' : '' ?>">
                        <a href="<?= base_url('anggota/penarikan') ?>"><i class="bi bi-wallet2"></i> Tarik Simpanan</a>
                    </li>
                    <li class="<?= url_is('anggota/profil*') ? 'active' : '' ?>">
                        <a href="<?= base_url('anggota/profil') ?>"><i class="bi bi-person-bounding-box"></i> Pengaturan Akun</a>
                    </li>
                </ul>
            </div>

            <div class="sidebar-footer">
                <a href="javascript:void(0)" class="btn-logout-sidebar text-decoration-none logout-trigger">
                    <i class="bi bi-box-arrow-left fs-5"></i> Keluar Sistem
                </a>
            </div>
        </nav>

        <div id="content">
            <div class="top-navbar">
                <div class="navbar-profile-left">
                    <div class="nav-avatar-box"><i class="bi bi-person-fill"></i></div>
                    <div>
                        <div class="d-flex align-items-center gap-2">
                            <span class="user-welcome-title">Selamat Datang, <?= session()->get('nama_lengkap') ?></span>
                            <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill fw-bold" style="font-size: 0.65rem; padding: 3px 10px;">Anggota Aktif</span>
                        </div>
                    </div>
                </div>
                <div class="d-flex align-items-center gap-3">
                    <div class="nav-action-btn"><i class="bi bi-bell-fill fs-5"></i></div>
                    <div class="nav-session-card">
                        <span class="fw-bold text-dark" style="font-size: 0.78rem;"><?= date('d M Y, H:i') ?> WIB</span>
                    </div>
                </div>
            </div>

            <div class="main-body-container">
                <?= $this->renderSection('content') ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const btn = document.querySelector('.logout-trigger');
        if(btn) {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                Swal.fire({
                    title: 'Konfirmasi Keluar',
                    text: 'Apakah Anda yakin ingin keluar dari sistem?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#ef4444',
                    cancelButtonColor: '#cbd5e1',
                    confirmButtonText: 'Ya, Keluar',
                    cancelButtonText: 'Batal',
                    borderRadius: '16px'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = "<?= base_url('logout') ?>";
                    }
                });
            });
        }
    });
    </script>
</body>
</html>