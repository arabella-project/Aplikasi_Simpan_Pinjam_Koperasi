<nav id="sidebar">
    <div class="sidebar-header">
        <div class="logo-wrapper">
            <div class="logo-icon"><i class="bi bi-shield-check text-white"></i></div>
            <div>
                <h6 class="mb-0 fw-bold text-white" style="letter-spacing: 0.5px;">KOPERASI BPS</h6>
                <small class="text-muted" style="font-size: 10px;">Ketua Panel v1.0</small>
            </div>
        </div>
    </div>
    <div class="nav-group">
        <div class="nav-label">Main Menu</div>
        <a href="<?= base_url('ketua/dashboard') ?>" class="nav-link <?= (url_is('ketua/dashboard*')) ? 'active' : '' ?>">
            <i class="bi bi-grid-1x2-fill"></i> <span>Dashboard</span>
        </a>
        
        <div class="nav-label mt-4">Otorisasi Berkas</div>
        <a href="<?= base_url('ketua/pengajuan') ?>" class="nav-link <?= (url_is('ketua/pengajuan*')) ? 'active' : '' ?>">
            <i class="bi bi-clipboard2-check-fill"></i> <span>Persetujuan Pinjaman</span>
        </a>
        <a href="<?= base_url('ketua/penarikan') ?>" class="nav-link <?= (url_is('ketua/penarikan*')) ? 'active' : '' ?>">
            <i class="bi bi-wallet2"></i> <span>Persetujuan Penarikan</span>
        </a>
    </div>
    <div class="sidebar-footer">
        <a href="javascript:void(0)" class="logout-btn logout-trigger">
            <i class="bi bi-box-arrow-right"></i> <span>Keluar Sistem</span>
        </a>
    </div>
</nav>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const btn = document.querySelector('.logout-trigger');
    if(btn) {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            Swal.fire({
                title: 'Konfirmasi Keluar', 
                text: 'Apakah Anda yakin ingin keluar dari sistem pimpinan?', 
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