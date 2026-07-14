<?= $this->extend('layout/main'); ?>
<?= $this->section('content'); ?>

<style>
    /* Premium FinTech Layout Customization Aligned with BPS */
    :root {
        --bps-blue: #00a2e9;
        --bps-green: #4aa135;
        --bps-orange: #f48221;
        --bps-blue-dark: #0082c8;
        --text-dark: #1e293b;
        --text-muted: #64748b;
    }

    .card-premium-form {
        border: 1px solid #e2e8f0;
        border-radius: 24px;
        background: #ffffff;
        position: relative;
        overflow: hidden;
    }
    
    /* Top Accent Line (Identity Branding) */
    .card-premium-form::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0; height: 5px;
        background: linear-gradient(90deg, var(--bps-blue) 0%, var(--bps-orange) 50%, var(--bps-green) 100%);
    }

    .form-premium-label {
        font-size: 11px !important;
        font-weight: 700 !important;
        color: var(--text-muted) !important;
        text-transform: uppercase !important;
        letter-spacing: 0.5px;
        margin-bottom: 6px;
        display: block;
    }

    .form-premium-control, .form-premium-select {
        border-radius: 12px !important;
        padding: 11px 16px !important;
        border: 1.5px solid #d1d5db !important;
        color: #1e293b !important;
        background-color: #f8fafc;
        font-size: 0.92rem;
        transition: all 0.2s ease;
    }
    
    .form-premium-control:focus, .form-premium-select:focus {
        background-color: #ffffff;
        border-color: var(--bps-blue) !important;
        box-shadow: 0 0 0 4px rgba(0, 162, 233, 0.15) !important;
        outline: none;
    }

    .btn-bps-blue {
        background-color: var(--bps-blue);
        color: #ffffff;
        border: none;
        transition: all 0.25s ease;
    }
    .btn-bps-blue:hover {
        background-color: var(--bps-blue-dark);
        color: #ffffff;
        transform: translateY(-1px);
        box-shadow: 0 5px 15px rgba(0, 162, 233, 0.3);
    }
</style>

<div class="container-fluid py-4 px-4">
    
    <div class="mb-4">
        <a href="<?= base_url('bendahara/pengajuan'); ?>" class="btn btn-sm btn-outline-secondary px-3 rounded-pill fw-semibold">
            <i class="bi bi-arrow-left me-1"></i> Kembali ke Matriks SAW
        </a>
    </div>

    <div class="card card-premium-form border-0 shadow-sm">
        <div class="card-body p-4">
            <div class="d-flex align-items-center gap-2 mb-4">
                <div class="p-2 rounded-3" style="background-color: rgba(244, 130, 33, 0.08); color: var(--bps-orange);"><i class="bi bi-file-earmark-medical-fill fs-5"></i></div>
                <h5 class="fw-bold mb-0 text-dark tracking-tight">Form Input Kriteria Kelayakan</h5>
            </div>
            
            <form action="<?= base_url('bendahara/pengajuan/simpan') ?>" method="post">
                <?= csrf_field() ?>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-premium-label">Nama Anggota Pemohon</label>
                        <select name="id_anggota" class="form-premium-select form-select" required>
                            <option value="">-- Cari & Pilih Nama Anggota --</option>
                            <?php if(!empty($anggota)): foreach($anggota as $a): ?>
                                <option value="<?= $a['id_anggota'] ?>"><?= esc($a['nama_anggota']) ?></option>
                            <?php endforeach; endif; ?>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-premium-label">Jumlah Dana Diajukan (Rp)</label>
                        <input type="number" name="jumlah_diajukan" class="form-premium-control form-control" placeholder="0" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-premium-label">C1: Persetujuan Pasangan</label>
                        <select name="c1" class="form-premium-select form-select">
                            <option value="1">Disetujui (1)</option>
                            <option value="0">Tidak Disetujui (0)</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-premium-label">C2: Rencana Hidup (RHB)</label>
                        <select name="c2" class="form-premium-select form-select">
                            <option value="5">Sangat Baik (5)</option>
                            <option value="4">Baik (4)</option>
                            <option value="3">Cukup (3)</option>
                            <option value="1">Tidak Ada (1)</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-premium-label">C3: Gaji Per Bulan</label>
                        <select name="c3" class="form-premium-select form-select">
                            <option value="5">> 5 Juta (5)</option>
                            <option value="3">3 - 3.9 Juta (3)</option>
                            <option value="1">< 2 Juta (1)</option>
                        </select>
                    </div>
                    <div class="col-12 mt-4">
                        <button type="submit" class="btn btn-bps-blue w-100 py-3 fw-bold rounded-3">
                            <i class="bi bi-calculator me-1"></i> SIMPAN & PROSES ANALISIS SAW
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>