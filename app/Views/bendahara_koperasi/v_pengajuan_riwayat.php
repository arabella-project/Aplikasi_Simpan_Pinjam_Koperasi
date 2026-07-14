<?= $this->extend('layout/main'); ?>
<?= $this->section('content'); ?>

<style>
    /* Premium FinTech Layout Customization Aligned with BPS */
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

    .tracking-tight { letter-spacing: -0.5px; }

    /* Tombol Kustom FinTech BPS */
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

    .btn-bps-green {
        background-color: var(--bps-green);
        color: #ffffff;
        border: none;
        transition: all 0.25s ease;
    }
    .btn-bps-green:hover {
        background-color: var(--bps-green-dark);
        color: #ffffff;
        transform: translateY(-1px);
        box-shadow: 0 5px 15px rgba(74, 161, 53, 0.3);
    }

    /* Header Tabel Terintegrasi Resmi BPS */
    .table-premium-header th {
        font-weight: 700 !important;
        color: var(--text-dark) !important;
        text-transform: uppercase;
        text-align: center;
        background-color: #f8fafc !important;
        border-bottom: 2px solid var(--bps-blue) !important;
        border-top: none !important;
        padding: 18px 12px;
        font-size: 0.75rem;
        letter-spacing: 0.8px;
    }

    /* Isi Baris Tabel Minimalis */
    .table-premium-body td {
        border-bottom: 1px solid #f1f5f9;
        padding: 16px 12px;
        vertical-align: middle;
        text-align: center;
        font-size: 0.9rem;
        color: #334155;
    }
    
    .table-premium-body td.text-start { text-align: left !important; }
    .table-premium-body td.text-end { text-align: right !important; }
    .table-hover tbody tr:hover { background-color: #f8fafc; }

    /* Desain Pop-up Modal Modern */
    .modal-transfer-clean .modal-content {
        border-radius: 24px !important;
        border: none !important;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.12) !important;
        background-color: #ffffff !important;
        position: relative;
        overflow: hidden;
    }

    /* Top Strip Tiga Warna Representasi Logo BPS pada Modal */
    .modal-transfer-clean .modal-content::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 6px;
        background: linear-gradient(90deg, var(--bps-blue) 0%, var(--bps-orange) 50%, var(--bps-green) 100%);
    }

    .modal-transfer-clean .modal-header {
        border-bottom: none !important;
        padding: 28px 28px 10px 28px !important;
    }

    .modal-transfer-clean .form-label {
        font-size: 11px !important;
        font-weight: 700 !important;
        color: var(--text-muted) !important;
        text-transform: uppercase !important;
        letter-spacing: 0.5px;
        margin-bottom: 6px;
        display: block;
    }

    .modal-transfer-clean .form-control, 
    .modal-transfer-clean .form-select {
        border-radius: 12px !important;
        padding: 11px 16px !important;
        border: 1.5px solid #d1d5db !important;
        color: #1e293b !important;
        background-color: #f8fafc;
        font-size: 0.92rem;
        transition: all 0.2s ease;
    }

    .modal-transfer-clean .form-control:focus, 
    .modal-transfer-clean .form-select:focus {
        background-color: #ffffff;
        border-color: var(--bps-blue) !important;
        box-shadow: 0 0 0 4px rgba(0, 162, 233, 0.15) !important;
        outline: none;
    }
</style>

<div class="container-fluid py-4 px-4">
    
    <!-- Top Action Bar (Tombol Sejajar Rapi di Kanan) -->
    <div class="row align-items-center mb-4 g-3">
        <div class="col-12 col-md-6">
            <h4 class="fw-bold text-dark tracking-tight mb-0"><i class="bi bi-clock-history me-1" style="color: var(--bps-blue);"></i>Riwayat Pengajuan</h4>
        </div>
        <div class="col-12 col-md-6 ms-auto d-flex justify-content-start justify-content-md-end">
            <a href="<?= base_url('bendahara/pengajuan') ?>" class="btn btn-bps-blue rounded-pill px-4 fw-semibold small">
                <i class="bi bi-arrow-left me-1"></i> Kembali ke Antrean SAW
            </a>
        </div>
    </div>

    <!-- Alert Notifikasi Flashdata -->
    <?php if (session()->getFlashdata('success')) : ?>
        <div class="alert alert-success border-0 shadow-sm mb-4 small rounded-3 bg-success bg-opacity-10 text-success d-flex align-items-center gap-2">
            <i class="bi bi-check-circle-fill"></i> <span><?= session()->getFlashdata('success') ?></span>
        </div>
    <?php endif; ?>

    <!-- Main Data Table Card -->
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-premium-header">
                    <tr>
                        <th class="text-center" width="100">ID</th>
                        <th class="text-start">Nama Anggota Koperasi</th>
                        <th class="text-end" width="220">Jumlah Pengajuan</th>
                        <th class="text-center" width="200">Status Akhir Berkas</th>
                        <th class="text-center" width="180">Aksi Pencairan</th>
                    </tr>
                </thead>
                <tbody class="table-premium-body">
                    <?php if(!empty($riwayat)): foreach($riwayat as $r): 
                        $status_raw = trim(strtolower($r['status_pengajuan']));
                    ?>
                    <tr>
                        <td class="text-center text-secondary font-monospace fw-medium">#<?= $r['id_pengajuan'] ?></td>
                        <td class="text-start">
                            <div class="fw-bold text-dark mb-0.5"><?= esc($r['nama_anggota']) ?></div>
                            <span class="badge bg-light text-secondary border px-2 py-1 font-monospace" style="font-size: 0.68rem; border-radius: 6px;">Rek: <?= esc($r['nama_bank']) ?> - <?= esc($r['nomor_rekening']) ?></span>
                        </td>
                        <td class="text-end fw-bold font-monospace" style="color: var(--text-dark);">Rp <?= number_format($r['jumlah_diajukan'], 0, ',', '.') ?></td>
                        <td class="text-center">
                            <?php if ($status_raw == 'transfer') : ?>
                                <span class="badge border px-3 py-1.5 rounded-pill fw-bold text-uppercase" style="font-size: 0.72rem; border-color: var(--bps-green) !important; color: var(--bps-green-dark) !important; background-color: #f0fdf4;">
                                    <i class="bi bi-cash-stack me-1"></i> 4. Dana Ditransfer
                                </span>
                            <?php elseif ($status_raw == 'disetujui') : ?>
                                <span class="badge border px-3 py-1.5 rounded-pill fw-bold text-uppercase" style="font-size: 0.72rem; border-color: var(--bps-blue) !important; color: var(--bps-blue-dark) !important; background-color: #f0f9ff;">
                                    <i class="bi bi-check2-circle me-1"></i> 3. Disetujui
                                </span>
                            <?php else : ?>
                                <span class="badge border px-3 py-1.5 rounded-pill fw-bold text-uppercase" style="font-size: 0.72rem; border-color: var(--bps-orange) !important; color: var(--bps-orange-dark) !important; background-color: #fff7ed;">
                                    <i class="bi bi-x-circle me-1"></i> Ditolak
                                </span>
                            <?php endif; ?>
                        </td>
                        <td class="text-center">
                            <?php if ($status_raw == 'disetujui') : ?>
                                <button type="button" class="btn btn-bps-green btn-sm fw-bold px-3 py-1.5 rounded-3 shadow-sm text-white" style="font-size: 0.78rem;" data-bs-toggle="modal" data-bs-target="#modalTransfer<?= $r['id_pengajuan'] ?>">
                                    <i class="bi bi-send-fill me-1"></i> Cairkan Dana
                                </button>
                            <?php else : ?>
                                <span class="text-muted small italic fw-medium">- Selesai -</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; else: ?>
                    <tr><td colspan="5" class="text-center py-5 text-muted small">Belum ada rekam log historis pengajuan.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- MODAL POP-UP MANDAT PENCAIRAN DANA -->
<?php if(!empty($riwayat)): foreach($riwayat as $p): if(trim(strtolower($p['status_pengajuan'])) == 'disetujui'): ?>
    <div class="modal fade modal-transfer-clean" id="modalTransfer<?= $p['id_pengajuan'] ?>" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="max-width: 440px;">
            <form action="<?= base_url('bendahara/pengajuan/transfer_pencairan') ?>" method="post" class="modal-content">
                <?= csrf_field() ?>
                <input type="hidden" name="id_pengajuan" value="<?= $p['id_pengajuan'] ?>">
                <input type="hidden" name="id_anggota" value="<?= $p['id_anggota'] ?>">
                <input type="hidden" name="bank_tujuan" value="<?= $p['nama_bank'] ?>">

                <div class="modal-header">
                    <h5 class="modal-title fw-bold text-dark"><i class="bi bi-bank2 me-2" style="color: var(--bps-blue);"></i>Mandat Pencairan Dana</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4 pt-0">
                    <div class="p-3 rounded-3 mb-3 border bg-light">
                        <small class="form-label text-muted mb-1">Anggota Penerima</small>
                        <span class="fw-bold text-dark d-block fs-6"><?= esc($p['nama_anggota']) ?></span>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label font-weight-bold" style="color: var(--bps-green-dark);">Jumlah Plafon yang Ditransfer (Rp)</label>
                        <input type="number" name="plafon_real" class="form-control fw-bold fs-5" style="color: var(--bps-green-dark);" value="<?= (int)($p['jumlah_diajukan'] ?? 0) ?>" required>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-6">
                            <label class="form-label">Nama Bank Mitra</label>
                            <input type="text" class="form-control bg-light text-dark fw-bold" value="<?= esc($p['nama_bank']) ?>" disabled>
                        </div>
                        <div class="col-6">
                            <label class="form-label">Nomor Rekening</label>
                            <input type="text" class="form-control bg-light text-dark fw-bold" value="<?= esc($p['nomor_rekening']) ?>" disabled>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label class="form-label">Biaya Administrasi Bank Transfer (Rp)</label>
                        <input type="number" name="biaya_admin" class="form-control fw-bold fs-5" style="color: var(--bps-orange-dark);" placeholder="Contoh: 6500 (Bisa Kosong)">
                    </div>
                    
                    <button type="submit" class="btn-simpan-full btn btn-bps-green w-100 py-2.5 fw-bold rounded-3 shadow">
                        <i class="bi bi-send-check-fill me-1"></i> TRANSFER SELESAI
                    </button>
                </div>
            </form>
        </div>
    </div>
<?php endif; endforeach; endif; ?>

<?= $this->endSection(); ?>