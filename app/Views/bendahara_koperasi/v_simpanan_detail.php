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
        --gradient-start: #0082c8;
        --gradient-end: #1a4cb0;
    }

    .tracking-tight { letter-spacing: -0.5px; }
    .table-premium-header th {
        font-weight: 700 !important;
        color: var(--text-dark) !important;
        text-transform: uppercase;
        background-color: #f8fafc !important;
        border-bottom: 2px solid #e2e8f0 !important;
        border-top: none !important;
        padding: 16px;
        font-size: 0.75rem;
        letter-spacing: 0.8px;
    }

    .table-premium-body td {
        border-bottom: 1px solid #f1f5f9;
        padding: 16px;
        vertical-align: middle;
        font-size: 0.9rem;
        color: #334155;
    }

    .table-hover tbody tr:hover {
        background-color: #f8fafc;
    }

    .modal-bps-clean .modal-content {
        border-radius: 24px !important;
        border: none !important;
        background-color: #ffffff !important;
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
        padding: 30px 30px 10px 30px !important;
    }
    .modal-bps-clean .modal-title {
        font-weight: 700 !important;
        color: var(--text-dark) !important;
        letter-spacing: -0.5px;
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
        width: 100% !important;
        transition: all 0.2s ease;
    }
    .modal-bps-clean .form-control:focus, 
    .modal-bps-clean .form-select:focus {
        background-color: #ffffff !important;
        border-color: var(--bps-blue) !important;
        box-shadow: 0 0 0 4px rgba(0, 162, 233, 0.15) !important;
        outline: none;
    }
    .modal-bps-clean .btn-simpan-full {
        background-color: var(--bps-blue) !important;
        color: white !important;
        border: none !important;
        padding: 13px !important;
        border-radius: 14px !important;
        font-weight: 600 !important;
        width: 100% !important;
        margin-top: 10px;
        transition: all 0.25s ease;
    }
    .modal-bps-clean .btn-simpan-full:hover {
        background-color: var(--bps-blue-dark) !important;
        transform: translateY(-1px);
        box-shadow: 0 8px 20px rgba(0, 162, 233, 0.25) !important;
    }

    .pagination { display: flex; gap: 6px; list-style: none; padding: 0; margin: 0; }
    .pagination li a, .pagination li span { padding: 7px 14px; border: 1px solid #e2e8f0; border-radius: 10px; text-decoration: none; color: var(--bps-blue); font-size: 0.85rem; font-weight: 600; transition: all 0.2s; }
    .pagination li a:hover { background-color: #f1f5f9; color: var(--bps-blue-dark); }
    .pagination li.active span { background-color: var(--bps-blue); color: white; border-color: var(--bps-blue); }

    .btn-navigasi-soft-blue {
        background-color: rgba(0, 162, 233, 0.08);
        color: var(--bps-blue-dark, #0082c8);
        border: 1px solid rgba(0, 162, 233, 0.15);
        font-size: 0.82rem;
        transition: all 0.2s ease;
    }
    .btn-navigasi-soft-blue:hover {
        background-color: var(--bps-blue);
        color: #ffffff;
        border-color: var(--bps-blue);
        transform: translateX(-2px);
    }
</style>

<div class="container-fluid py-4 px-4">
<div class="mb-4">
    <a href="<?= base_url('bendahara/simpanan'); ?>" class="btn btn-navigasi-soft-blue rounded-pill px-3 py-2 fw-bold d-inline-flex align-items-center">
        <i class="bi bi-chevron-left me-1 fw-bold"></i> Kembali ke Data Simpanan
    </a>
</div>

    <div class="card border-0 shadow-sm text-white mb-4" style="border-radius: 20px; background: linear-gradient(135deg, var(--gradient-start) 0%, var(--gradient-end) 100%);">
        <div class="card-body p-4">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <small class="text-uppercase fw-bold text-white opacity-100" style="font-size: 0.68rem; letter-spacing: 1px;">Informasi Anggota Koperasi</small>
                    <h3 class="fw-bold mb-0 mt-1 tracking-tight text-white"><?= $anggota['nama_anggota']; ?></h3>
                    
                    <div class="mt-2">
                        <span class="badge font-monospace text-white border border-white border-opacity-25" style="font-size: 0.75rem; background-color: rgba(255, 255, 255, 0.15); border-radius: 6px; padding: 6px 10px;">
                            ID Anggota: #<?= $anggota['id_anggota']; ?>
                        </span>
                    </div>
                </div>
                <div class="col-md-4 text-md-end mt-3 mt-md-0">
                    <span class="badge bg-white text-dark px-3 py-2 rounded-pill fw-bold shadow-sm" style="color: var(--bps-green-dark) !important;">
                        <i class="bi bi-patch-check-fill me-1 text-success"></i> Status: Aktif
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-header bg-white border-0 py-3 px-4 d-flex align-items-center gap-2">
            <div class="p-2 rounded-3" style="background-color: rgba(0, 162, 233, 0.08); color: var(--bps-blue);"><i class="bi bi-clock-history fs-5"></i></div>
            <h5 class="fw-bold text-dark mb-0 tracking-tight">Riwayat Transaksi Kas</h5>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-premium-header">
                    <tr class="text-uppercase">
                        <th class="ps-4 py-3" width="160">Tanggal</th>
                        <th class="text-center" width="150">Jenis Simpanan</th>
                        <th class="text-center" width="130">Bank</th>
                        <th class="text-end" width="220">Jumlah</th>
                        <th class="text-start">Keterangan</th>
                        <th class="text-center" width="130">Aksi</th>
                    </tr>
                </thead>
                <tbody class="table-premium-body">
                    <?php if (!empty($histori)) : ?>
                        <?php foreach($histori as $h): ?>
                        <tr>
                            <td class="ps-4 fw-medium text-secondary">
                                <i class="bi bi-calendar3 me-2 small" style="color: var(--bps-blue);"></i><?= date('d/m/Y', strtotime($h['tgl_transaksi'])); ?>
                            </td>
                            <td class="text-center">
                                <?php 
                                    $badgeStyle = 'border-color: var(--bps-blue) !important; color: var(--bps-blue-dark) !important; background-color: #f0f9ff;';
                                    if ($h['jenis_simpanan'] == 'wajib') {
                                        $badgeStyle = 'border-color: var(--bps-orange) !important; color: var(--bps-orange-dark) !important; background-color: #fff7ed;';
                                    } elseif ($h['jenis_simpanan'] == 'sukarela') {
                                        $badgeStyle = 'border-color: var(--bps-green) !important; color: var(--bps-green-dark) !important; background-color: #f0fdf4;';
                                    }
                                ?>
                                <span class="badge border px-3 py-1.5 rounded-pill fw-bold text-uppercase" style="font-size: 0.72rem; <?= $badgeStyle ?>">
                                    <?= $h['jenis_simpanan']; ?>
                                </span>
                            </td>
                            <td class="text-center fw-semibold text-dark"><?= $h['bank']; ?></td>
                            <td class="text-end fw-bold font-monospace" style="color: var(--bps-green-dark); font-size: 0.95rem;">
                                Rp <?= number_format($h['jumlah'], 0, ',', '.'); ?>
                            </td>
                            <td class="text-start text-secondary small"><?= $h['keterangan'] ?: '-'; ?></td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-1">
                                    <button class="btn btn-sm btn-light border rounded-circle" style="width:32px; height:32px; padding:0;" data-bs-toggle="modal" data-bs-target="#modalEdit<?= $h['id_transaksi_simpanan']; ?>" title="Ubah Transaksi">
                                        <i class="bi bi-pencil-square text-warning"></i>
                                    </button>
                                    <button type="button" 
                                            class="btn btn-sm btn-light border rounded-circle btn-delete-trigger" 
                                            style="width:32px; height:32px; padding:0;"
                                            data-url="<?= base_url('bendahara/simpanan/delete_transaksi/'.$h['id_transaksi_simpanan']); ?>" 
                                            title="Hapus">
                                        <i class="bi bi-trash text-danger"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr><td colspan="6" class="text-center py-5 text-muted small">Belum ada riwayat mutasi transaksi untuk anggota ini.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        
        <div class="card-footer bg-white border-0 py-3 px-4 d-flex justify-content-between align-items-center">
            <div class="small text-muted">Data pembukuan simpanan mutasi riwayat berjalan resmi BPS Sumsel.</div>
            <div class="pagination-container">
                <?php if (isset($pager)) : ?>
                    <?= $pager->links('group1', 'default_full') ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php if (!empty($histori)) : ?>
    <?php foreach($histori as $h): ?>
    <div class="modal fade modal-bps-clean" id="modalEdit<?= $h['id_transaksi_simpanan']; ?>" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="max-width: 450px;">
            <form action="<?= base_url('bendahara/simpanan/update_transaksi') ?>" method="post" class="modal-content">
                <?= csrf_field() ?>
                <input type="hidden" name="id_transaksi" value="<?= $h['id_transaksi_simpanan']; ?>">
                
                <div class="modal-header">
                    <h5 class="modal-title"><i class="bi bi-pencil-square me-2" style="color: var(--bps-blue);"></i>Koreksi Data Transaksi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                
                <div class="modal-body p-4 pt-0">
                    <div class="mb-3">
                        <label class="form-label">ANGGOTA</label>
                        <select class="form-select text-muted" disabled>
                            <option><?= $anggota['nama_anggota']; ?></option>
                        </select>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-6">
                            <label class="form-label">JENIS SIMPANAN</label>
                            <select name="jenis_simpanan" class="form-select" required>
                                <option value="wajib" <?= $h['jenis_simpanan'] == 'wajib' ? 'selected' : ''; ?>>Wajib</option>
                                <option value="sukarela" <?= $h['jenis_simpanan'] == 'sukarela' ? 'selected' : ''; ?>>Sukarela</option>
                                <option value="pokok" <?= $h['jenis_simpanan'] == 'pokok' ? 'selected' : ''; ?>>Pokok</option>
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="form-label">KAS BANK</label>
                            <select name="bank" class="form-select" required>
                                <option value="BRI" <?= $h['bank'] == 'BRI' ? 'selected' : ''; ?>>BRI</option>
                                <option value="BSI" <?= $h['bank'] == 'BSI' ? 'selected' : ''; ?>>BSI</option>
                                <option value="Tunai" <?= $h['bank'] == 'Tunai' ? 'selected' : ''; ?>>Tunai</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">JUMLAH SETORAN (RP)</label>
                        <input type="number" name="jumlah" class="form-control fw-bold" style="color: var(--bps-green-dark); font-size: 1.1rem;" value="<?= (int)$h['jumlah']; ?>" required>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">KETERANGAN MUTASI</label>
                        <textarea name="keterangan" class="form-control" rows="3" placeholder="Tambahkan catatan koreksi transaksi bulanan..."><?= $h['keterangan']; ?></textarea>
                    </div>

                    <button type="submit" class="btn-simpan-full shadow-sm">
                        PERBARUI RIWAYAT KAS
                    </button>
                </div>
            </form>
        </div>
    </div>
    <?php endforeach; ?>
<?php endif; ?>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const deleteButtons = document.querySelectorAll('.btn-delete-trigger');
        
        deleteButtons.forEach(button => {
            button.addEventListener('click', function (e) {
                e.preventDefault();
                const targetUrl = this.getAttribute('data-url');
                
                // PERBAIKAN: Menggunakan objek 'Swal' resmi agar alert bawaan browser terganti sempurna
                Swal.fire({
                    title: 'Hapus Transaksi?',
                    text: "Data riwayat simpanan kas akan dihapus permanen dari sistem jaminan pembukuan.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#f48221', /* Warna Oranye Khas BPS untuk tombol OK / Eksekusi */
                    cancelButtonColor: '#94a3b8',  /* Abu-abu Slate untuk tombol Batal */
                    confirmButtonText: 'Ya, Hapus Data',
                    cancelButtonText: 'Batal',
                    customClass: {
                        popup: 'rounded-4' /* Membuat sudut pop-up melengkung halus sesuai tema FinTech */
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Jika bendahara memilih "Ya, Hapus Data", sistem dialihkan ke fungsi delete CodeIgniter 4
                        window.location.href = targetUrl;
                    }
                });
            });
        });
    });
</script>

<?= $this->endSection(); ?>