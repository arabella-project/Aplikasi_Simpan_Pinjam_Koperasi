<?= $this->extend('layout/main'); ?>
<?= $this->section('content'); ?>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
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

    /* Struktur Header Tabel */
    .table-premium-header th {
        font-weight: 700 !important;
        color: var(--text-dark) !important;
        text-transform: uppercase;
        background-color: #f8fafc !important;
        border-bottom: 2px solid #e2e8f0 !important;
        border-top: none !important;
        padding: 16px 12px;
        font-size: 0.75rem;
        letter-spacing: 0.8px;
    }

    /* Body Tabel Mutasi */
    .table-premium-body td {
        border-bottom: 1px solid #f1f5f9;
        padding: 16px 12px;
        vertical-align: middle;
        font-size: 0.9rem;
        color: #334155;
    }

    .table-hover tbody tr:hover {
        background-color: #f8fafc;
    }

    /* Custom Borders Identitas untuk Pemisah Tabel Multi-Kontrak */
    .border-card-bps-blue { border-top: 4px solid var(--bps-blue) !important; }
    .border-card-bps-green { border-top: 4px solid var(--bps-green) !important; }

    /* Desain Modal Pemulihan / Koreksi Data */
    .modal-bps-clean .modal-content {
        border-radius: 24px !important;
        border: none !important;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.15) !important;
        position: relative;
        overflow: hidden;
    }
    .modal-bps-clean .modal-content::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 6px;
        background: linear-gradient(90deg, var(--bps-blue) 0%, var(--bps-orange) 50%, var(--bps-green) 100%);
    }
    .modal-bps-clean .modal-header {
        border-bottom: none !important;
        padding: 28px 28px 10px 28px !important;
    }
    .modal-bps-clean .form-label {
        font-size: 11px !important;
        font-weight: 700 !important;
        color: var(--text-muted) !important;
        text-transform: uppercase !important;
        letter-spacing: 0.5px !important;
        margin-bottom: 6px !important;
        display: block;
    }
    .modal-bps-clean .form-control, 
    .modal-bps-clean .form-select {
        border-radius: 12px !important;
        padding: 11px 16px !important;
        border: 1.5px solid #d1d5db !important;
        background-color: #f8fafc !important;
        color: #1e293b !important;
        font-size: 0.92rem !important;
    }
    .modal-bps-clean .form-control:focus, 
    .modal-bps-clean .form-select:focus {
        background-color: #ffffff !important;
        border-color: var(--bps-blue) !important;
        box-shadow: 0 0 0 4px rgba(0, 162, 233, 0.15) !important;
        outline: none;
    }
    
    .btn-submit-koreksi {
        width: 100%;
        padding: 12px;
        border-radius: 12px;
        font-weight: 600;
        color: white;
        border: none;
        transition: all 0.2s ease;
    }
    .btn-submit-koreksi:hover {
        background-color: var(--bps-blue-dark) !important;
        transform: translateY(-1px);
    }
</style>

<div class="container-fluid py-4 px-4">
    
    <div class="mb-4">
        <a href="<?= base_url('bendahara/pinjaman'); ?>" class="btn btn-navigasi-kembali rounded-pill px-3 py-2 fw-bold d-inline-flex align-items-center" style="background-color: #ffffff; color: var(--text-dark); border: 1.5px solid #e2e8f0; box-shadow: 0 2px 6px rgba(0,0,0,0.04); font-size: 0.82rem; transition: all 0.2s ease;">
            <i class="bi bi-arrow-left-circle-fill me-2 fs-6" style="color: var(--bps-blue);"></i> Kembali ke List Utama
        </a>
    </div>

    <div class="mb-4 dashboard-title-space">
        <h4 class="fw-bold text-dark tracking-tight mb-1"> Histori Angsuran: <?= esc($anggota['nama_anggota']); ?></h4>
        <span class="badge bg-light text-secondary border px-2.5 py-1.5 font-monospace" style="font-size: 0.72rem; border-radius: 6px;">ID Anggota Terdaftar: #<?= $anggota['id_anggota'] ?></span>
    </div>

    <?php if (session()->getFlashdata('success')) : ?>
        <div class="alert alert-success border-0 shadow-sm mb-4 small rounded-3 bg-success bg-opacity-10 text-success d-flex align-items-center gap-2">
            <i class="bi bi-check-circle-fill"></i> <span><?= session()->getFlashdata('success') ?></span>
        </div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')) : ?>
        <div class="alert alert-danger border-0 shadow-sm mb-4 small rounded-3 bg-danger bg-opacity-10 text-danger d-flex align-items-center gap-2">
            <i class="bi bi-exclamation-triangle-fill"></i> <span><?= session()->getFlashdata('error') ?></span>
        </div>
    <?php endif; ?>

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden border-card-bps-blue mb-4">
        <div class="card-header bg-white border-0 py-3 px-4 d-flex justify-content-between align-items-center mb-0">
            <div class="d-flex align-items-center gap-2">
                <div class="p-2 rounded-3" style="background-color: rgba(0, 162, 233, 0.08); color: var(--bps-blue);"><i class="bi bi-1-circle-fill fs-5"></i></div>
                <h6 class="fw-bold mb-0 text-dark tracking-tight">TABEL HISTORI PINJAMAN KE-1</h6>
            </div>
            <?php if($pinjaman1): ?>
                <span class="badge px-3 py-2 rounded-pill font-monospace fw-bold" style="background-color: #fff7ed; color: var(--bps-orange-dark); border: 1px solid #ffedd5; font-size: 0.82rem;">
                    Sisa Hutang: Rp <?= number_format($pinjaman1['sisa_hutang'], 0, ',', '.'); ?>
                </span>
            <?php else: ?>
                <span class="badge bg-light text-muted border px-3 py-2 rounded-pill">Tidak Ada Kontrak</span>
            <?php endif; ?>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-premium-header">
                    <tr>
                        <th class="ps-4 py-3" width="160">Tgl Bayar</th>
                        <th class="text-center" width="140">Angsuran Ke</th>
                        <th class="text-end" width="220">Nominal Pembayaran</th>
                        <th class="text-center" width="180">Metode Pembayaran</th>
                        <th class="text-start">Sumber / Bank Kas</th>
                        <th class="text-center" width="140">Opsi Tindakan</th>
                    </tr>
                </thead>
                <tbody class="table-premium-body">
                    <?php if (!empty($histori1)) : foreach ($histori1 as $h) : ?>
                    <tr>
                        <td class="ps-4 fw-medium text-secondary">
                            <i class="bi bi-calendar3 me-2 small" style="color: var(--bps-blue);"></i><?= date('d/m/Y', strtotime($h['tgl_bayar'])); ?>
                        </td>
                        <td class="text-center"><span class="badge bg-light text-dark border px-2.5 py-1.5 rounded-pill font-monospace" style="font-size:0.72rem;">Ke-<?= $h['angsuran_ke']; ?></span></td>
                        <td class="text-end fw-bold font-monospace" style="color: var(--bps-green-dark);">Rp <?= number_format($h['jumlah_bayar'], 0, ',', '.'); ?></td>
                        <td class="text-center"><span class="badge bg-light border text-secondary px-3 py-1.5 rounded-pill text-uppercase" style="font-size: 0.72rem; border-color: #cbd5e1 !important;"><?= $h['metode_pembayaran']; ?></span></td>
                        <td class="small text-dark fw-semibold text-start"><?= $h['bank']; ?></td>
                        <td class="text-center">
                            <div class="d-flex justify-content-center gap-1">
                                <button type="button" class="btn btn-sm btn-light border rounded-circle" style="width:32px; height:32px; padding:0;" data-bs-toggle="modal" data-bs-target="#modalUbahTrans<?= $h['id_pembayaran'] ?>" title="Ubah"><i class="bi bi-pencil-square text-warning"></i></button>
                                <button type="button" class="btn btn-sm btn-light border rounded-circle btn-delete-trigger" style="width:32px; height:32px; padding:0;" data-url="<?= base_url('bendahara/pinjaman/delete_angsuran/' . $h['id_pembayaran']) ?>" title="Hapus"><i class="bi bi-trash text-danger"></i></button>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; else : ?>
                        <tr><td colspan="6" class="text-center py-5 text-muted small">Belum ada rekam angsuran pada kontrak pinjaman pertama ini.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <?php if (!empty($pinjaman2)) : ?>
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden border-card-bps-green mb-5">
            <div class="card-header bg-white border-0 py-3 px-4 d-flex justify-content-between align-items-center mb-0">
                <div class="d-flex align-items-center gap-2">
                    <div class="p-2 rounded-3" style="background-color: rgba(74, 161, 53, 0.08); color: var(--bps-green);"><i class="bi bi-2-circle-fill fs-5"></i></div>
                    <h6 class="fw-bold mb-0 text-dark tracking-tight">TABEL HISTORI PINJAMAN KE-2</h6>
                </div>
                <span class="badge px-3 py-2 rounded-pill font-monospace fw-bold" style="background-color: #fff7ed; color: var(--bps-orange-dark); border: 1px solid #ffedd5; font-size: 0.82rem;">
                    Sisa Hutang: Rp <?= number_format($pinjaman2['sisa_hutang'], 0, ',', '.'); ?>
                </span>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-premium-header">
                        <tr>
                            <th class="ps-4 py-3" width="160">Tgl Bayar</th>
                            <th class="text-center" width="140">Angsuran Ke</th>
                            <th class="text-end" width="220">Nominal Pembayaran</th>
                            <th class="text-center" width="180">Metode Pembayaran</th>
                            <th class="text-start">Sumber / Bank Kas</th>
                            <th class="text-center" width="140">Opsi Tindakan</th>
                        </tr>
                    </thead>
                    <tbody class="table-premium-body">
                        <?php if (!empty($histori2)) : foreach ($histori2 as $h2) : ?>
                        <tr>
                            <td class="ps-4 fw-medium text-secondary">
                                <i class="bi bi-calendar3 me-2 small" style="color: var(--bps-green);"></i><?= date('d/m/Y', strtotime($h2['tgl_bayar'])); ?>
                            </td>
                            <td class="text-center"><span class="badge bg-light text-dark border px-2.5 py-1.5 rounded-pill font-monospace" style="font-size:0.72rem;">Ke-<?= $h2['angsuran_ke']; ?></span></td>
                            <td class="text-end fw-bold font-monospace" style="color: var(--bps-green-dark);">Rp <?= number_format($h2['jumlah_bayar'], 0, ',', '.'); ?></td>
                            <td class="text-center"><span class="badge bg-light border text-secondary px-3 py-1.5 rounded-pill text-uppercase" style="font-size: 0.72rem; border-color: #cbd5e1 !important;"><?= $h2['metode_pembayaran']; ?></span></td>
                            <td class="small text-dark fw-semibold text-start"><?= $h2['bank']; ?></td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-1">
                                    <button type="button" class="btn btn-sm btn-light border rounded-circle" style="width:32px; height:32px; padding:0;" data-bs-toggle="modal" data-bs-target="#modalUbahTrans<?= $h2['id_pembayaran'] ?>" title="Ubah"><i class="bi bi-pencil-square text-warning"></i></button>
                                    <button type="button" class="btn btn-sm btn-light border rounded-circle btn-delete-trigger" style="width:32px; height:32px; padding:0;" data-url="<?= base_url('bendahara/pinjaman/delete_angsuran/' . $h2['id_pembayaran']) ?>" title="Hapus"><i class="bi bi-trash text-danger"></i></button>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; else : ?>
                            <tr><td colspan="6" class="text-center py-5 text-muted small">Belum ada rekam angsuran pada kontrak pinjaman kedua (Multi-Pinjaman) ini.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php 
    $gabungHistori = array_merge($histori1 ?? [], $histori2 ?? []);
    foreach($gabungHistori as $gh):
?>
<div class="modal fade modal-bps-clean" id="modalUbahTrans<?= $gh['id_pembayaran'] ?>" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 420px;">
        <form action="<?= base_url('bendahara/pinjaman/update_angsuran') ?>" method="post" class="modal-content">
            <?= csrf_field() ?>
            <input type="hidden" name="id_pembayaran" value="<?= $gh['id_pembayaran'] ?>">
            <div class="modal-header">
                <h5 class="modal-title fw-bold text-dark mb-0"><i class="bi bi-pencil-square text-warning me-2"></i>Perbaiki Transaksi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4 pt-2">
                <div class="mb-3">
                    <label class="form-label">Tanggal Pembayaran</label>
                    <input type="date" name="tgl_bayar" class="form-control" value="<?= $gh['tgl_bayar'] ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Nominal Setoran Terkoreksi (Rp)</label>
                    <input type="number" name="jumlah_bayar" class="form-control fw-bold" style="color: var(--bps-green-dark); font-size:1.1rem;" value="<?= (int)$gh['jumlah_bayar'] ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Metode Pembayaran</label>
                    <select name="metode" class="form-select" required>
                        <option value="Potong Gaji" <?= $gh['metode_pembayaran'] == 'Potong Gaji' ? 'selected' : '' ?>>Potong Gaji</option>
                        <option value="Transfer" <?= $gh['metode_pembayaran'] == 'Transfer' ? 'selected' : '' ?>>Transfer</option>
                        <option value="Tunai" <?= $gh['metode_pembayaran'] == 'Tunai' ? 'selected' : '' ?>>Tunai</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label class="form-label">Tujuan Bank Kas</label>
                    <input type="text" name="bank" class="form-control" value="<?= esc($gh['bank']) ?>" required>
                </div>
                <button type="submit" class="btn btn-submit-koreksi" style="background: var(--bps-blue) !important;">Simpan Koreksi Data</button>
            </div>
        </form>
    </div>
</div>
<?php endforeach; ?>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const deleteButtons = document.querySelectorAll('.btn-delete-trigger');
        
        deleteButtons.forEach(button => {
            button.addEventListener('click', function (e) {
                e.preventDefault();
                const targetUrl = this.getAttribute('data-url');
                
                Swal.fire({
                    title: 'Hapus Transaksi?',
                    text: "Sistem akan menghapus rekam log angsuran ini dan mengembalikan sisa saldo hutang anggota.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#f48221', 
                    cancelButtonColor: '#94a3b8',  
                    confirmButtonText: 'Ya, Hapus Transaksi',
                    cancelButtonText: 'Batal',
                    customClass: {
                        popup: 'rounded-4'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = targetUrl;
                    }
                });
            });
        });
    });
</script>

<?= $this->endSection(); ?>