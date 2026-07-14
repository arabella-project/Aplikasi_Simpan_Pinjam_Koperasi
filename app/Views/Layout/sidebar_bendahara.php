<div class="d-flex flex-column flex-shrink-0 p-3 text-white" id="sidebar" style="width: 260px; height: 100vh; background-color: #111827; position: fixed; top: 0; left: 0; z-index: 1000; padding: 24px 16px !important;">
    
    <div class="d-flex align-items-center mb-4 me-md-auto text-white text-decoration-none border-bottom pb-3 w-100" style="border-color: rgba(255,255,255,0.05) !important;">
        <div class="rounded-3 text-white me-3 d-flex align-items-center justify-content-center" style="background-color: #3b82f6; width: 42px; height: 42px; font-size: 1.3rem; box-shadow: 0 0 15px rgba(59, 130, 246, 0.65);">
            <i class="bi bi-stack text-white"></i>
        </div>
        <div class="d-flex flex-column">
            <h6 class="mb-0 fw-bold text-white tracking-tight" style="letter-spacing: 0.8px; font-size: 0.95rem;">KOPERASI BPS</h6>
            <small class="text-muted" style="font-size: 10px; opacity: 0.4; display: block; margin-top: 2px;">Otoritas Kantor v1.0</small>
        </div>
    </div>

    <div class="text-uppercase fw-bold mb-2 ps-2" style="font-size: 10px; color: #475569; letter-spacing: 1.5px;">Main Menu</div>

    <ul class="nav flex-column mb-auto gap-1 w-100">
        <li class="nav-item">
            <a href="<?= base_url('kantor/dashbord') ?>" class="nav-link <?= url_is('kantor/dashbord') ? 'active-premium-menu' : 'inactive-premium-menu' ?>">
                <i class="bi bi-speedometer2"></i>
                <span>Dashboard Validasi</span>
            </a>
        </li>
        
        <li>
            <a href="<?= base_url('kantor/dashbord/tabel') ?>" class="nav-link <?= url_is('kantor/dashbord/tabel*') ? 'active-premium-menu' : 'inactive-premium-menu' ?>">
                <i class="bi bi-shield-check"></i>
                <span>Verifikasi Potong Gaji</span>
            </a>
        </li>
        
        <div class="text-uppercase fw-bold mb-2 mt-4 ps-2" style="font-size: 10px; color: #475569; letter-spacing: 1.5px;">Arsip & Laporan</div>
        
        <li>
            <a href="<?= base_url('kantor/dashbord/riwayat') ?>" class="nav-link <?= url_is('kantor/dashbord/riwayat*') ? 'active-premium-menu' : 'inactive-premium-menu' ?>">
                <i class="bi bi-clock-history"></i> 
                <span>Log Riwayat</span>
            </a>
        </li> 
    </ul>

    <div style="padding: 0 12px; margin-top: auto; border-top: 1px solid rgba(255, 255, 255, 0.05); padding-top: 20px;">
        <a href="javascript:void(0)" class="nav-link logout-trigger" style="color: #f87171; background: rgba(248, 113, 113, 0.05); margin-bottom: 0; border-radius: 12px; display: flex; align-items: center; gap: 14px; padding: 12px 14px; text-decoration: none; font-size: 0.9rem; font-weight: 500; transition: all 0.2s ease;">
            <i class="bi bi-box-arrow-right" style="font-size: 1.15rem;"></i>
            <span>Keluar Sistem</span>
        </a>
    </div>
</div>

<style>
    /* 🟢 SINKRONISASI BASE THEME SIDEBAR FINTECH PREMIUM GELAP */
    .inactive-premium-menu {
        display: flex; 
        align-items: center; 
        gap: 14px; 
        color: #9ca3af !important; 
        text-decoration: none; 
        padding: 12px; 
        border-radius: 12px; 
        font-size: 0.88rem; 
        font-weight: 500; 
        transition: all 0.2s ease; 
        margin-bottom: 4px;
    }
    .inactive-premium-menu:hover {
        background: rgba(255, 255, 255, 0.03); 
        color: #ffffff !important;
    }
    
    /* 🟢 GAYA MENU AKTIF DENGAN EFEK BERSINAR MENYALA (GLOWING BLUE ACTIVE) */
    .active-premium-menu { 
        display: flex; 
        align-items: center; 
        gap: 14px; 
        background: #2563eb !important; 
        color: #ffffff !important; 
        padding: 12px; 
        border-radius: 12px; 
        font-size: 0.88rem; 
        font-weight: 600; 
        box-shadow: 0 4px 20px rgba(37, 99, 235, 0.45); /* Efek pendaran biru */
        margin-bottom: 4px;
        text-decoration: none;
    }
    .active-premium-menu i, .inactive-premium-menu i { 
        font-size: 1.15rem; 
    }
    
    .logout-trigger:hover {
        background-color: rgba(239, 68, 68, 0.08) !important;
        color: #ef4444 !important;
    }
    
    body { padding-left: 260px !important; }
</style>

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
                customClass: { popup: 'rounded-4' }
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "<?= base_url('logout') ?>";
                }
            });
        });
    }
});
</script>