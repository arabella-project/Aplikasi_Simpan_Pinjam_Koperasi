<?= $this->extend('layout/main_bendahara_kantor'); ?>
<?= $this->section('content'); ?>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

<style>
    /* BPS Official Color Palette & Clean Card UI Configuration */
    :root {
        --bps-blue: #0099ec;
        --bps-blue-dark: #0077b6;
        --bps-green: #70b443;
        --bps-green-dark: #55922f;
        --bps-orange: #f39200;
        --bps-orange-dark: #d67a00;
        --text-dark: #1e293b;
        --text-muted: #64748b;
    }

    .tracking-tight { letter-spacing: -0.5px; }
    
    /* 1. MODERATOR CARD STYLE WITH ACCENT GLOWS */
    .card-koperasi-style {
        border: 1px solid #e2e8f0;
        border-radius: 20px;
        background-color: #ffffff !important;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        padding: 24px !important;
        position: relative;
    }
    
    /* Efek hover dinamis menyesuaikan warna masing-masing indikator box */
    .card-glow-blue:hover {
        transform: translateY(-4px);
        border-color: rgba(0, 153, 236, 0.3);
        box-shadow: 0 12px 24px rgba(0, 153, 236, 0.08) !important;
    }
    .card-glow-green:hover {
        transform: translateY(-4px);
        border-color: rgba(112, 180, 67, 0.3);
        box-shadow: 0 12px 24px rgba(112, 180, 67, 0.08) !important;
    }
    .card-glow-orange:hover {
        transform: translateY(-4px);
        border-color: rgba(243, 146, 0, 0.3);
        box-shadow: 0 12px 24px rgba(243, 146, 0, 0.08) !important;
    }

    .text-stat-label {
        font-size: 0.85rem;
        font-weight: 600;
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .text-stat-number {
        font-size: 2rem;
        font-weight: 800;
        color: var(--text-dark);
        line-height: 1.1;
    }

    .icon-circle-box {
        width: 52px;
        height: 52px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 14px; /* Mengubah lingkaran menjadi smooth square agar lebih modern */
        font-size: 1.4rem;
        transition: transform 0.3s ease;
    }
    
    .card-koperasi-style:hover .icon-circle-box {
        transform: scale(1.1) rotate(5deg);
    }

    .icon-box-blue {
        background-color: rgba(0, 153, 236, 0.08) !important;
        color: var(--bps-blue) !important;
    }
    .icon-box-green {
        background-color: rgba(112, 180, 67, 0.08) !important;
        color: var(--bps-green) !important;
    }
    .icon-box-orange {
        background-color: rgba(243, 146, 0, 0.08) !important;
        color: var(--bps-orange) !important;
    }

    /* Live Queue Dot Pulse Animation */
    .status-pulse-dot {
        width: 8px;
        height: 8px;
        background-color: var(--bps-blue);
        border-radius: 50%;
        display: inline-block;
        margin-right: 6px;
        animation: pulseEffect 2s infinite;
    }

    @keyframes pulseEffect {
        0% { transform: scale(0.9); box-shadow: 0 0 0 0 rgba(0, 153, 236, 0.4); }
        70% { transform: scale(1); box-shadow: 0 0 0 6px rgba(0, 153, 236, 0); }
        100% { transform: scale(0.9); box-shadow: 0 0 0 0 rgba(0, 153, 236, 0); }
    }

    /* 2. BANNER PORTAL UTAMA GRADIENT PREMIUM */
    .hero-premium-banner {
        background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%) !important; 
        border: 1px solid #bae6fd !important;
        border-radius: 24px;
        position: relative;
        overflow: hidden;
    }
    
    /* Dekorasi Lingkaran Abstrak Khas Dashboard Premium */
    .hero-premium-banner::before {
        content: '';
        position: absolute;
        width: 300px;
        height: 300px;
        background: radial-gradient(circle, rgba(0,153,236,0.1) 0%, transparent 70%);
        top: -100px;
        right: -50px;
        border-radius: 50%;
        pointer-events: none;
    }

    .btn-premium-action {
        background-color: var(--bps-blue) !important;
        color: #ffffff !important;
        border: none;
        font-weight: 600;
        padding: 12px 28px !important;
        border-radius: 12px !important;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 4px 12px rgba(0, 153, 236, 0.15);
    }
    
    .btn-premium-action:hover {
        background-color: var(--bps-blue-dark) !important;
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(0, 153, 236, 0.3);
    }
</style>

<div class="container-fluid py-4 px-4">
    
    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center mb-4 border-bottom pb-3" style="border-color: #e2e8f0 !important;">
        <div class="mb-2 mb-sm-0">
            <span class="text-uppercase tracking-widest fw-bold d-block" style="font-size: 0.72rem; color: var(--bps-blue); letter-spacing: 1px;">Sistem Informasi Eksekutif BPS</span>
            <h4 class="fw-bold text-dark tracking-tight mb-0 mt-1">Statistik Otoritas BPS</h4>
            <p class="text-muted small mb-0">Manajemen indikator keuangan potong gaji bulanan pegawai secara terpadu.</p>
        </div>
        <div class="d-flex align-items-center gap-3 w-100 w-sm-auto justify-content-sm-end">
            <div class="text-end d-none d-lg-block font-monospace me-1 small fw-bold text-secondary bg-white border rounded-3 px-3 py-2 shadow-sm">
                <i class="bi bi-clock-fill me-1" style="color: var(--bps-blue);"></i><span id="realtime-clock"><?= date('d M Y - H:i:s') ?></span>
            </div>
            <div class="text-end">
                <span class="badge bg-white text-dark shadow-sm px-3 py-2.5 rounded-3 border fw-semibold" style="font-size: 0.82rem; color: #334155 !important; border-color: #e2e8f0 !important;">
                    <i class="bi bi-person-badge-fill me-2" style="color: var(--bps-blue);"></i>Bendahara: <?= esc($nama_lengkap) ?>
                </span>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        
        <div class="col-xl-4 col-md-6">
            <div class="card card-koperasi-style card-glow-blue shadow-sm">
                <div class="card-body p-0 d-flex align-items-center justify-content-between">
                    <div>
                        <p class="text-stat-label mb-1">Antrean Validasi</p>
                        <h2 class="text-stat-number mb-1 font-monospace text-dark">
                            <?= $stats['total_menunggu'] ?> <span style="font-size: 0.9rem; font-family: sans-serif; font-weight: 600; color: var(--text-muted);">Berkas</span>
                        </h2>
                        <small class="text-muted d-flex align-items-center" style="font-size: 0.75rem;">
                            <span class="status-pulse-dot"></span> Butuh validasi segera
                        </small>
                    </div>
                    <div class="icon-circle-box icon-box-blue">
                        <i class="bi bi-hourglass-split"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6">
            <div class="card card-koperasi-style card-glow-green shadow-sm">
                <div class="card-body p-0 d-flex align-items-center justify-content-between">
                    <div>
                        <p class="text-stat-label mb-1">Berhasil Debit</p>
                        <h2 class="text-stat-number mb-1 font-monospace" style="color: var(--bps-green-dark);">
                            <?= $stats['total_berhasil'] ?> <span style="font-size: 0.9rem; font-family: sans-serif; font-weight: 600; color: var(--text-muted);">Pegawai</span>
                        </h2>
                        <small class="text-muted d-block" style="font-size: 0.75rem;"><i class="bi bi-check2-circle text-success me-1"></i> Telah dipotong dari payroll</small>
                    </div>
                    <div class="icon-circle-box icon-box-green">
                        <i class="bi bi-cash-stack"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6">
            <div class="card card-koperasi-style card-glow-orange shadow-sm">
                <div class="card-body p-0 d-flex align-items-center justify-content-between">
                    <div>
                        <p class="text-stat-label mb-1">Gagal Debit</p>
                        <h2 class="text-stat-number mb-1 font-monospace" style="color: var(--bps-orange-dark);">
                            <?= $stats['total_ditolak'] ?> <span style="font-size: 0.9rem; font-family: sans-serif; font-weight: 600; color: var(--text-muted);">Log</span>
                        </h2>
                        <small class="text-muted d-block" style="font-size: 0.75rem;"><i class="bi bi-exclamation-triangle text-warning me-1"></i> Take home pay tidak cukup</small>
                    </div>
                    <div class="icon-circle-box icon-box-orange">
                        <i class="bi bi-x-octagon"></i>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <div class="card hero-premium-banner border-0 shadow-sm p-4 p-md-5">
        <div class="row align-items-center">
            <div class="col-lg-8" style="position: relative; z-index: 3;">
                <div class="mb-2">
                    <span class="badge rounded-pill px-3 py-1.5 text-uppercase fw-bold" style="font-size: 0.65rem; letter-spacing: 0.5px; background-color: #ffffff; color: var(--bps-blue-dark) !important; border: 1px solid #bae6fd;">
                        <i class="bi bi-shield-lock-fill me-1" style="color: var(--bps-blue);"></i> Ruang Kerja Validasi Valid
                    </span>
                </div>
                <h4 class="fw-bold tracking-tight mb-2 text-dark" style="font-size: 1.45rem;">Portal Utama Otoritas Sinkronisasi Payroll BPS</h4>
            </div>
            <div class="col-lg-4 text-lg-end mt-4 mt-lg-0" style="position: relative; z-index: 3;">
                <a href="<?= base_url('kantor/dashbord/tabel') ?>" class="btn btn-premium-action shadow-sm d-inline-flex align-items-center gap-2">
                    Mulai Verifikasi Data <i class="bi bi-arrow-right-short fs-5"></i>
                </a>
            </div>
        </div>
    </div>

</div>

<script>
    function updateClock() {
        const now = new Date();
        const options = { 
            day: '2-digit', 
            month: 'short', 
            year: 'numeric', 
            hour: '2-digit', 
            minute: '2-digit', 
            second: '2-digit',
            hour12: false
        };
        document.getElementById('realtime-clock').textContent = now.toLocaleDateString('id-ID', options).replace(/\./g, ':');
    }
    updateClock();
    setInterval(updateClock, 1000);
</script>

<?= $this->endSection(); ?>