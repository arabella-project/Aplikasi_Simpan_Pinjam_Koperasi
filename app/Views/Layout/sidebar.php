<nav id="sidebar">
    <!-- Header Logo -->
    <div class="sidebar-header">
        <div class="logo-wrapper">
            <div class="logo-icon">
                <i class="bi bi-intersect text-white"></i>
            </div>
            <div>
                <h6 class="mb-0 fw-bold text-white">KOPERASI BPS</h6>
                <small class="text-muted" style="font-size: 10px;">Admin Panel v1.0</small>
            </div>
        </div>
    </div>

    <!-- Navigation Menu -->
    <div class="nav-group">
        <div class="nav-label">Main Menu</div>
        
        <a href="<?= base_url('bendahara/dashbord') ?>" class="nav-link <?= (url_is('bendahara/dashbord*')) ? 'active' : '' ?>">
            <i class="bi bi-columns-gap"></i>
            <span>Dashboard</span>
        </a>

        <a href="<?= base_url('bendahara/simpanan') ?>" class="nav-link <?= (url_is('bendahara/simpanan*')) ? 'active' : '' ?>">
            <i class="bi bi-wallet2"></i>
            <span>Data Simpanan</span>
        </a>

        <a href="<?= base_url('bendahara/pinjaman') ?>" class="nav-link <?= (url_is('bendahara/pinjaman*')) ? 'active' : '' ?>">
            <i class="bi bi-cash-stack"></i>
            <span>Data Pinjaman</span>
        </a>

        <a href="<?= base_url('bendahara/pengeluaran') ?>" class="nav-link <?= (url_is('bendahara/pengeluaran*')) ? 'active' : '' ?>">
            <i class="bi bi-cash-stack"></i>
            <span>Data Pengeluaran</span>
        </a>

        <div class="nav-label mt-4">Analisis & Laporan</div>

        <a href="<?= base_url('bendahara/pengajuan') ?>" class="nav-link <?= (url_is('bendahara/pengajuan*')) ? 'active' : '' ?>">
            <i class="bi bi-clipboard2-check"></i>
            <span>Analisis Pengajuan Pinjaman</span>
        </a>

        <a href="<?= base_url('bendahara/konfirmasi_penarikan') ?>" class="nav-link <?= (url_is('bendahara/konfirmasi_penarikan*')) ? 'active' : '' ?>">
            <i class="bi bi-clipboard2-check"></i>
            <span>Konfirmasi Penarikan</span>
        </a>

        <a href="<?= base_url('bendahara/neraca') ?>" class="nav-link <?= (url_is('bendahara/neraca*')) ? 'active' : '' ?>">
            <i class="bi bi-bar-chart-line"></i>
            <span>Laporan Neraca</span>
        </a>

        <a href="<?= base_url('bendahara/potongan') ?>" class="nav-link <?= (url_is('bendahara/tabelpengajuanpotongan*')) ? 'active' : '' ?>">
            <i class="bi bi-scissors"></i>
            <span>Potongan Gaji</span>
        </a>

        <a href="<?= base_url('bendahara/akun') ?>" class="nav-link <?= (url_is('bendahara/akun*')) ? 'active' : '' ?>">
             <i class="bi bi-people-fill"></i>
             <span>Kelola Akun Anggota</span>
        </a>
    </div>

    <!-- Sidebar Footer -->
    <div class="sidebar-footer">
        <a href="javascript:void(0)" class="logout-btn logout-trigger">
            <i class="bi bi-box-arrow-right"></i>
            <span>Keluar Sistem</span>
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
                text: 'Apakah Anda yakin ingin keluar dari sistem?',
                icon: 'warning',
                showCancelButton: true,
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