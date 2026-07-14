<?= $this->extend('layout/main'); ?>
<?= $this->section('content'); ?>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
    :root {
        --bps-blue: #00a2e9;
        --bps-green: #4aa135;
        --bps-orange: #f48221;
        --bps-purple: #7c3aed;
        --bps-red: #ef4444;
        --bps-blue-dark: #0082c8;
        --bps-green-dark: #3b822a;
        --bps-orange-dark: #d85d15;
        --text-dark: #1e293b;
        --text-muted: #64748b;
    }

    body {
        background-color: #f4f7fe;
    }

    .tracking-tight { letter-spacing: -0.5px; }

    .card-metric-summary {
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        background: #ffffff;
        transition: all 0.25s ease;
    }
    .card-metric-summary:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 20px -5px rgba(0, 0, 0, 0.05);
    }
    .metric-icon-circle {
        width: 42px;
        height: 42px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
    }

    .table-premium-header th {
        font-weight: 600 !important;
        color: #64748b !important;
        text-transform: uppercase;
        background-color: #f8fafc !important;
        border-bottom: 1px solid #e2e8f0 !important;
        padding: 14px 16px;
        font-size: 0.72rem;
        letter-spacing: 0.5px;
    }

    .table-premium-body td {
        border-bottom: 1px solid #f1f5f9;
        padding: 14px 16px;
        vertical-align: middle;
        font-size: 0.85rem;
        color: #334155;
    }

    .btn-bps-blue {
        background-color: var(--bps-blue);
        color: #ffffff;
        border: none;
        transition: all 0.2s ease;
    }
    .btn-bps-blue:hover {
        background-color: var(--bps-blue-dark);
        color: #ffffff;
        box-shadow: 0 4px 12px rgba(0, 162, 233, 0.2);
    }

    .modal-premium-clean .modal-content {
        border-radius: 20px !important;
        border: none !important;
        position: relative;
        overflow: hidden;
    }
    .modal-premium-clean .modal-content::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0; height: 5px;
        background: linear-gradient(90deg, var(--bps-blue) 0%, var(--bps-orange) 50%, var(--bps-green) 100%);
    }
    .modal-premium-clean .modal-header { border-bottom: none !important; padding: 24px 24px 8px 24px !important; }
    .modal-premium-clean .form-label {
        font-size: 11px !important;
        font-weight: 700 !important;
        color: var(--text-muted) !important;
        text-transform: uppercase !important;
        letter-spacing: 0.5px;
        margin-bottom: 5px;
    }
    .modal-premium-clean .form-control, 
    .modal-premium-clean .form-select {
        border-radius: 10px !important;
        padding: 10px 14px !important;
        border: 1.5px solid #d1d5db !important;
        background-color: #f8fafc;
        font-size: 0.9rem;
    }
    .modal-premium-clean .form-control:focus, 
    .modal-premium-clean .form-select:focus {
        background-color: #ffffff;
        border-color: var(--bps-blue) !important;
        box-shadow: 0 0 0 4px rgba(0, 162, 233, 0.12) !important;
    }
    .modal-premium-clean .btn-simpan-full {
        background: var(--bps-blue) !important;
        color: white !important;
        border: none !important;
        padding: 11px !important;
        border-radius: 10px !important;
        font-weight: 600 !important;
        width: 100% !important;
    }
    .modal-premium-clean .btn-simpan-full:hover { background: var(--bps-blue-dark) !important; }
</style>

<div class="container-fluid py-3 px-4">
    
    <div class="row align-items-center mb-4 g-3">
        <div class="col-12 col-md-6">
            <span class="text-uppercase tracking-widest fw-bold mb-1 d-block" style="font-size: 0.68rem; color: var(--bps-blue);">Arus Kas Keluar</span>
            <h4 class="fw-bold text-dark tracking-tight mb-1">Daftar Pengeluaran Koperasi</h4>
            </div>
        <div class="col-12 col-md-6 ms-auto d-flex justify-content-start justify-content-md-end gap-2 flex-wrap">
            <a href="<?= base_url('bendahara/pengeluaran/export_excel_pengeluaran') ?>" class="btn btn-success rounded-3 py-2 px-3 fw-bold small d-inline-flex align-items-center" style="font-size: 0.82rem;">
                <i class="bi bi-file-earmark-spreadsheet me-2"></i> Ekspor Laporan Excel
            </a>
            <button class="btn btn-bps-blue rounded-3 py-2 px-3 fw-bold small" style="font-size: 0.82rem;" data-bs-toggle="modal" data-bs-target="#modalTambahPengeluaran">
                <i class="bi bi-plus-lg me-2"></i> Tambah Pengeluaran
            </button>
        </div>
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

    <div class="row g-3 mb-4">
        <div class="col-12 col-sm-6 col-md-3">
            <div class="card card-metric-summary p-3 shadow-sm h-100">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-uppercase fw-bold text-muted" style="font-size: 0.65rem; letter-spacing: 0.5px;">Total Pengeluaran</small>
                        <h5 class="fw-bold text-dark tracking-tight mb-0 mt-1" style="font-size: 1.15rem;">Rp <?= number_format($total_pengeluaran, 0, ',', '.'); ?></h5>
                    </div>
                    <div class="metric-icon-circle" style="background-color: rgba(244, 130, 33, 0.08); color: var(--bps-orange);">
                        <i class="bi bi-arrow-up-right-circle-fill"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-3">
            <div class="card card-metric-summary p-3 shadow-sm h-100">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-uppercase fw-bold text-muted" style="font-size: 0.65rem; letter-spacing: 0.5px;">Kas BRI</small>
                        <h5 class="fw-bold text-dark tracking-tight mb-0 mt-1" style="font-size: 1.15rem;">Rp <?= number_format($beban_bri, 0, ',', '.'); ?></h5>
                    </div>
                    <div class="metric-icon-circle" style="background-color: rgba(0, 162, 233, 0.08); color: var(--bps-blue);">
                        <i class="bi bi-bank"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-3">
            <div class="card card-metric-summary p-3 shadow-sm h-100">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-uppercase fw-bold text-muted" style="font-size: 0.65rem; letter-spacing: 0.5px;">Beban Kas BSI</small>
                        <h5 class="fw-bold text-dark tracking-tight mb-0 mt-1" style="font-size: 1.15rem;">Rp <?= number_format($beban_bsi, 0, ',', '.'); ?></h5>
                    </div>
                    <div class="metric-icon-circle" style="background-color: rgba(74, 161, 53, 0.08); color: var(--bps-green);">
                        <i class="bi bi-building-bank"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-3">
            <div class="card card-metric-summary p-3 shadow-sm h-100">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-uppercase fw-bold text-muted" style="font-size: 0.65rem; letter-spacing: 0.5px;">Beban Tunai Brankas</small>
                        <h5 class="fw-bold text-dark tracking-tight mb-0 mt-1" style="font-size: 1.15rem;">Rp <?= number_format($beban_tunai, 0, ',', '.'); ?></h5>
                    </div>
                    <div class="metric-icon-circle" style="background-color: rgba(124, 58, 237, 0.08); color: var(--bps-purple);">
                        <i class="bi bi-wallet2"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-header bg-white border-0 py-3 px-4">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                <h6 class="fw-bold text-dark mb-0 d-flex align-items-center gap-2"><i class="bi bi-journal-text text-primary"></i> Buku Jurnal Pengeluaran Kas</h6>
                <input type="text" id="cariNama" class="form-control form-control-sm border rounded-3 px-3 py-1.5" style="font-size: 0.82rem; width: 240px;" placeholder="Cari keterangan operasional...">
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-premium-header">
                    <tr>
                        <th class="text-center" width="60">NO</th>
                        <th class="text-center" width="140">TANGGAL</th>
                        <th class="text-start">KETERANGAN OPERASIONAL</th>
                        <th class="text-center" width="160">KATEGORI</th>
                        <th class="text-center" width="160">REKENING SUMBER</th>
                        <th class="text-end" width="200">JUMLAH BEBAN</th>
                        <th class="text-center" width="120">AKSI</th>
                    </tr>
                </thead>
                <tbody class="table-premium-body" id="bodyTabelNama">
                    <?php if (!empty($pengeluaran)) : $no = 1; ?>
                        <?php foreach ($pengeluaran as $row) : ?>
                        <tr>
                            <td class="text-center text-secondary fw-bold"><?= $no++; ?></td>
                            <td class="text-center text-secondary fw-medium">
                                <span style="font-size: 0.82rem;"><i class="bi bi-calendar3 me-1.5" style="color: var(--bps-blue);"></i><?= date('d/m/Y', strtotime($row['tgl_pengeluaran'])); ?></span>
                            </td>
                            <td class="fw-bold text-dark text-start"><?= esc($row['keterangan']); ?></td>
                            <td class="text-center">
                                <span class="badge rounded-pill text-primary bg-primary bg-opacity-10 py-1.5 px-3 fw-bold text-uppercase" style="font-size: 0.68rem; border: 1px solid rgba(0,162,233,0.15)">
                                    <?= esc($row['kategori']); ?>
                                </span>
                            </td>
                            <td class="fw-semibold text-dark text-center">
                                <span class="badge bg-light text-dark border px-2.5 py-1.5" style="font-size: 0.72rem; border-radius: 6px;"><?= esc($row['bank']); ?></span>
                            </td>
                            <td class="text-end fw-bold font-monospace" style="color: var(--bps-orange-dark); font-size: 0.92rem;">
                                Rp <?= number_format($row['jumlah'], 0, ',', '.'); ?>
                            </td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-1">
                                    <button class="btn btn-sm btn-light border rounded-circle" style="width:30px; height:30px; padding:0;" data-bs-toggle="modal" data-bs-target="#modalEditPengeluaran<?= $row['id_pengeluaran']; ?>">
                                        <i class="bi bi-pencil-square text-warning"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-light border rounded-circle btn-delete-trigger" style="width:30px; height:30px; padding:0;" data-url="<?= base_url('bendahara/pengeluaran/delete/'.$row['id_pengeluaran']); ?>">
                                        <i class="bi bi-trash text-danger"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr><td colspan="7" class="text-center py-5 text-muted small">Belum ada catatan pengeluaran operasional yang terekam di sistem.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade modal-premium-clean" id="modalTambahPengeluaran" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 440px;">
        <form action="<?= base_url('bendahara/pengeluaran/store'); ?>" method="post" class="modal-content">
            <?= csrf_field() ?>
            <div class="modal-header">
                <h5 class="modal-title fw-bold text-dark tracking-tight"><i class="bi bi-shield-plus me-2" style="color: var(--bps-blue);"></i>Tambah Pengeluaran Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4 pt-1">
                <div class="mb-3">
                    <label class="form-label">Tanggal Transaksi</label>
                    <input type="date" name="tgl_pengeluaran" class="form-control" value="<?= date('Y-m-d'); ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Keterangan Pengeluaran</label>
                    <input type="text" name="keterangan" class="form-control" placeholder="Contoh: Pembelian ATK Kantor" required>
                </div>
                <div class="row g-3 mb-3">
                    <div class="col-6">
                        <label class="form-label">Kategori</label>
                        <select name="kategori" class="form-select" required>
                            <option value="Biaya Admin">Biaya Admin</option>
                            <option value="Pajak">Pajak</option>
                            <option value="Operasional">Operasional</option>
                        </select>
                    </div>
                    <div class="col-6">
                        <label class="form-label">Sumber Dana Kas</label>
                        <select name="bank" class="form-select" required>
                            <option value="BRI">BRI (Kas Utama)</option>
                            <option value="BSI">BSI (Syariah)</option>
                            <option value="Tunai">Tunai Kas</option>
                        </select>
                    </div>
                </div>
                <div class="mb-4">
                    <label class="form-label">Jumlah Beban Dana (Rp)</label>
                    <input type="number" name="jumlah" class="form-control fw-bold fs-5 text-danger" placeholder="0" required>
                </div>
                <button type="submit" class="btn-simpan-full text-white shadow-sm fw-bold">Catat Pengeluaran Kas</button>
            </div>
        </form>
    </div>
</div>

<?php if (!empty($pengeluaran)) : ?>
    <?php foreach ($pengeluaran as $row) : ?>
    <div class="modal fade modal-premium-clean" id="modalEditPengeluaran<?= $row['id_pengeluaran']; ?>" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="max-width: 440px;">
            <form action="<?= base_url('bendahara/pengeluaran/update'); ?>" method="post" class="modal-content">
                <?= csrf_field() ?>
                <input type="hidden" name="id_pengeluaran" value="<?= $row['id_pengeluaran']; ?>"> 
                
                <div class="modal-header">
                    <h5 class="modal-title fw-bold text-dark tracking-tight"><i class="bi bi-pencil-square me-2" style="color: var(--bps-blue);"></i>Koreksi Data Pengeluaran</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                
                <div class="modal-body p-4 pt-1">
                    <div class="mb-3">
                        <label class="form-label">Tanggal Buku</label>
                        <input type="date" name="tgl_pengeluaran" class="form-control" value="<?= $row['tgl_pengeluaran']; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Keterangan Pengeluaran</label>
                        <input type="text" name="keterangan" class="form-control" value="<?= esc($row['keterangan']); ?>" required>
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-6">
                            <label class="form-label">Kategori</label>
                            <select name="kategori" class="form-select" required>
                                <option value="Biaya Admin" <?= $row['kategori'] == 'Biaya Admin' ? 'selected' : ''; ?>>Biaya Admin</option>
                                <option value="Pajak" <?= $row['kategori'] == 'Pajak' ? 'selected' : ''; ?>>Pajak</option>
                                <option value="Operasional" <?= $row['kategori'] == 'Operasional' ? 'selected' : ''; ?>>Operasional</option>
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="form-label">Sumber Dana Kas</label>
                            <select name="bank" class="form-select" required>
                                <option value="BRI" <?= $row['bank'] == 'BRI' ? 'selected' : ''; ?>>BRI</option>
                                <option value="BSI" <?= $row['bank'] == 'BSI' ? 'selected' : ''; ?>>BSI</option>
                                <option value="Tunai" <?= $row['bank'] == 'Tunai' ? 'selected' : ''; ?>>Tunai</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="form-label">Jumlah Dana Terkoreksi (Rp)</label>
                        <input type="number" name="jumlah" class="form-control fw-bold fs-5 text-danger" value="<?= (int)$row['jumlah']; ?>" required>
                    </div>
                    <button type="submit" class="btn-simpan-full text-white shadow-sm fw-bold">Validasi Perubahan Data</button>
                </div>
            </form>
        </div>
    </div>
    <?php endforeach; ?>
<?php endif; ?>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const inputCari = document.getElementById('cariNama');
        const bodyTabel = document.getElementById('bodyTabelNama');
        
        if (inputCari && bodyTabel) {
            const barisTabel = bodyTabel.getElementsByTagName('tr');

            inputCari.addEventListener('keyup', function(e) {
                const teksPencarian = e.target.value.toLowerCase();
                
                for (let i = 0; i < barisTabel.length; i++) {
                    const kolomKeterangan = barisTabel[i].getElementsByTagName('td')[2];
                    
                    if (kolomKeterangan) {
                        const teksKeterangan = kolomKeterangan.textContent || kolomKeterangan.innerText;
                        
                        if (teksKeterangan.toLowerCase().indexOf(teksPencarian) > -1) {
                            barisTabel[i].style.display = "";
                        } else {
                            barisTabel[i].style.display = "none";
                        }
                    }
                }
            });
        }

        const deleteButtons = document.querySelectorAll('.btn-delete-trigger');
        deleteButtons.forEach(button => {
            button.addEventListener('click', function (e) {
                e.preventDefault();
                const targetUrl = this.getAttribute('data-url');
                
                Swal.fire({
                    title: 'Hapus Log Pengeluaran?',
                    text: "Sistem akan menghapus catatan operasional kas ini secara permanen dari neraca arus pembukuan keluar.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#f48221', 
                    cancelButtonColor: '#94a3b8',  
                    confirmButtonText: 'Ya, Hapus Log',
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