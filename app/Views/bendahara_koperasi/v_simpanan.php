<?= $this->extend('layout/main'); ?>
<?= $this->section('content'); ?>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

<style>
    :root {
        --bps-blue: #00a2e9;
        --bps-green: #4aa135;
        --bps-orange: #f48221;
        --bps-purple: #7c3aed;
        --bps-blue-dark: #0082c8;
        --bps-green-dark: #3b822a;
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
        padding: 12px 16px;
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
    
    .btn-bps-outline-green {
        color: var(--bps-green);
        border: 1.5px solid var(--bps-green);
        background: transparent;
        transition: all 0.2s ease;
    }
    .btn-bps-outline-green:hover {
        background-color: var(--bps-green);
        color: #ffffff;
    }

    .avatar-circle {
        width: 34px;
        height: 34px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 0.78rem;
        color: #ffffff;
    }
    .avatar-1 { background-color: #00a2e9; }
    .avatar-2 { background-color: #4aa135; }
    .avatar-3 { background-color: #f48221; }
    .avatar-4 { background-color: #7c3aed; }
    .avatar-5 { background-color: #ec4899; }

    /* --- PREMIUM MODAL CLEAN --- */
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
</style>

<div class="container-fluid py-3 px-4">
    
    <div class="row align-items-center mb-4 g-3">
        <div class="col-12 col-xl-6">
            <span class="text-uppercase tracking-widest fw-bold mb-1 d-block" style="font-size: 0.68rem; color: var(--bps-blue);">Manajemen Data Simpanan Anggota</span>
            <h4 class="fw-bold text-dark tracking-tight mb-1">Data Simpanan Anggota</h4>
       </div>
        <div class="col-12 col-xl-6 d-flex justify-content-start justify-content-xl-end gap-2 flex-wrap">
            <a href="<?= base_url('bendahara/simpanan/export_excel_simpanan') ?>" class="btn btn-success rounded-3 py-2 px-3 fw-bold small d-inline-flex align-items-center" style="font-size: 0.82rem;">
                <i class="bi bi-file-earmark-spreadsheet me-2"></i> Ekspor Laporan Simpanan
            </a>
            <button class="btn btn-bps-outline-green rounded-3 py-2 px-3 fw-bold small" style="font-size: 0.82rem;" data-bs-toggle="modal" data-bs-target="#modalImportExcelSimpanan">
                <i class="bi bi-file-earmark-arrow-up me-2"></i> Impor Excel
            </button>
            <button class="btn btn-bps-blue rounded-3 py-2 px-3 fw-bold small" style="font-size: 0.82rem;" data-bs-toggle="modal" data-bs-target="#modalInputSimpanan">
                <i class="bi bi-plus-lg me-2"></i> Input Simpanan
            </button>
        </div>
    </div>

    <?php if (session()->getFlashdata('success')) : ?>
        <div class="alert alert-success border-0 shadow-sm mb-4 small rounded-3 bg-success bg-opacity-10 text-success d-flex align-items-center gap-2">
            <i class="bi bi-check-circle-fill"></i> <span><?= session()->getFlashdata('success') ?></span>
        </div>
    <?php endif; ?>

    <div class="row g-3 mb-4">
        <div class="col-12 col-sm-6 col-md-3">
            <div class="card card-metric-summary p-3 shadow-sm h-100">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-uppercase fw-bold text-muted" style="font-size: 0.65rem; letter-spacing: 0.5px;">Total Anggota</small>
                        <h4 class="fw-bold text-dark tracking-tight mb-0 mt-1"><?= $total_anggota; ?></h4>
                        <small class="text-muted" style="font-size: 0.72rem;">Aktif terdaftar</small>
                    </div>
                    <div class="metric-icon-circle" style="background-color: rgba(0, 162, 233, 0.08); color: var(--bps-blue);">
                        <i class="bi bi-people-fill"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-3">
            <div class="card card-metric-summary p-3 shadow-sm h-100">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-uppercase fw-bold text-muted" style="font-size: 0.65rem; letter-spacing: 0.5px;">Total Saldo Keseluruhan</small>
                        <h5 class="fw-bold text-dark tracking-tight mb-0 mt-1" style="font-size: 1.15rem;">Rp <?= number_format($total_saldo_koperasi, 0, ',', '.'); ?></h5>
                    </div>
                    <div class="metric-icon-circle" style="background-color: rgba(74, 161, 53, 0.08); color: var(--bps-green);">
                        <i class="bi bi-piggy-bank-fill"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-3">
            <div class="card card-metric-summary p-3 shadow-sm h-100">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-uppercase fw-bold text-muted" style="font-size: 0.65rem; letter-spacing: 0.5px;">Rata-Rata Saldo Anggota</small>
                        <h5 class="fw-bold text-dark tracking-tight mb-0 mt-1" style="font-size: 1.15rem;">Rp <?= number_format($rata_rata_saldo, 0, ',', '.'); ?></h5>
                        <small class="text-muted d-block mt-1" style="font-size: 0.72rem;">Per anggota</small>
                    </div>
                    <div class="metric-icon-circle" style="background-color: rgba(244, 130, 33, 0.08); color: var(--bps-orange);">
                        <i class="bi bi-pie-chart-fill"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-3">
            <div class="card card-metric-summary p-3 shadow-sm h-100">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-uppercase fw-bold text-muted" style="font-size: 0.65rem; letter-spacing: 0.5px;">Simpanan Tertinggi</small>
                        <h5 class="fw-bold text-dark tracking-tight mb-0 mt-1" style="font-size: 1.15rem;">Rp <?= number_format($simpanan_tertinggi['nominal'] ?? 0, 0, ',', '.'); ?></h5>
                        <small class="text-muted d-block mt-1 text-truncate" style="font-size: 0.72rem; max-width: 140px;"><?= esc($simpanan_tertinggi['nama'] ?? '-'); ?></small>
                    </div>
                    <div class="metric-icon-circle" style="background-color: rgba(124, 58, 237, 0.08); color: var(--bps-purple);">
                        <i class="bi bi-trophy-fill"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-header bg-white border-0 py-3 px-4">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                <h6 class="fw-bold text-dark mb-0 d-flex align-items-center gap-2"><i class="bi bi-list-ul text-primary"></i> Daftar Simpanan Anggota</h6>
                <div class="d-flex gap-2">
                    <input type="text" id="searchSimpanan" class="form-control form-control-sm border rounded-3 px-3 py-1.5" style="font-size: 0.82rem; width: 220px;" placeholder="Cari ID anggota...">
                </div>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-premium-header">
                    <tr>
                        <th class="text-center" width="60">NO</th>
                        <th class="text-start">PROFIL ANGGOTA</th>
                        <th class="text-end">POKOK</th>
                        <th class="text-end">WAJIB</th>
                        <th class="text-end">SUKARELA</th>
                        <th class="text-end" width="220">TOTAL SALDO</th>
                        <th class="text-center" width="140">AKSI</th>
                    </tr>
                </thead>
                <tbody class="table-premium-body" id="tableBodySimpanan">
                    <?php if (!empty($simpanan)) : ?>
                        <?php 
                            $no = 1 + (10 * ($pager->getCurrentPage('group1') - 1)); 
                            $colorIndex = 1;
                        ?>
                        <?php foreach($simpanan as $s): 
                            $initials = strtoupper(substr($s['nama_anggota'], 0, 2));
                        ?>
                        <tr>
                            <td class="text-center text-secondary fw-bold"><?= $no++ ?></td>
                            <td class="text-start">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="avatar-circle avatar-<?= $colorIndex; ?>"><?= $initials ?></div>
                                    <div>
                                        <div class="fw-bold text-dark mb-0.5" style="font-size: 0.88rem;"><?= esc($s['nama_anggota']) ?></div>
                                        <span class="text-primary fw-semibold" style="font-size: 0.72rem;">ID: #<?= $s['id_anggota'] ?></span>
                                    </div>
                                </div>
                            </td>
                            <td class="text-end font-monospace">Rp <?= number_format($s['simpanan_pokok'], 0, ',', '.') ?></td>
                            <td class="text-end font-monospace">Rp <?= number_format($s['simpanan_wajib'], 0, ',', '.') ?></td>
                            <td class="text-end font-monospace">Rp <?= number_format($s['simpanan_sukarela'], 0, ',', '.') ?></td>
                            <td class="text-end">
                                <div class="d-flex align-items-center justify-content-end gap-2">
                                    <span class="fw-bold font-monospace" style="font-size: 0.92rem; color: #1e293b;">
                                        Rp <?= number_format($s['simpanan_pokok'] + $s['simpanan_wajib'] + $s['simpanan_sukarela'], 0, ',', '.') ?>
                                    </span>
                                 </div>
                            </td>
                            <td class="text-center">
                                <a href="<?= base_url('bendahara/simpanan/detail/'.$s['id_anggota']) ?>" class="btn btn-sm btn-light border rounded-pill py-1 px-3 fw-bold shadow-sm" style="font-size: 0.75rem; color: var(--bps-blue);">
                                    <i class="bi bi-clock-history me-1"></i> Lihat Histori
                                </a>
                            </td>
                        </tr>
                        <?php 
                            $colorIndex = ($colorIndex % 5) + 1; 
                            endforeach; 
                        ?>
                    <?php else : ?>
                        <tr><td colspan="7" class="text-center py-5 text-muted small">Data master simpanan anggota tidak ditemukan.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="card-footer bg-white border-0 py-3 px-4 d-flex flex-column flex-sm-row justify-content-between align-items-center border-top gap-3">
            <div class="small text-muted text-center text-sm-start">Menampilkan <?= count($simpanan); ?> baris data akumulasi simpanan anggota.</div>
            <div class="pagination-container"><?= $pager->links('group1', 'default_full') ?></div>
        </div>
    </div>
</div>

<div class="modal fade modal-premium-clean" id="modalImportExcelSimpanan" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 420px;">
        <form action="<?= base_url('bendahara/simpanan/import_excel_simpanan') ?>" method="post" enctype="multipart/form-data" class="modal-content">
            <?= csrf_field() ?>
            <div class="modal-header border-0 pt-4 px-4 pb-0">
                <h5 class="modal-title fw-bold text-dark tracking-tight"><i class="bi bi-file-earmark-excel-fill text-success me-2"></i>Impor Massal Simpanan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="mb-3">
                    <label class="form-label">Pilih Berkas Berformat (.xlsx / .xls)</label>
                    <input type="file" name="file_excel" class="form-control" accept=".xlsx, .xls" required>
                </div>
                <div class="p-3 bg-light rounded-3 d-flex gap-2">
                    <i class="bi bi-info-circle-fill text-primary mt-0.5" style="font-size: 0.85rem;"></i>
                    <small class="text-muted d-block" style="font-size: 0.72rem; line-height: 1.4;">Sistem otomatis memproses rekapan kolom simpanan horizontal, menyinkronkan saldo utama, serta merekam mutasi mutakhir.</small>
                </div>
            </div>
            <div class="modal-footer border-0 p-4 pt-0">
                <button type="submit" class="btn btn-success w-100 py-2.5 fw-bold shadow-sm rounded-3" style="background-color: var(--bps-green); border: none; font-size: 0.9rem;">PROSES IMPORT DATA</button>
            </div>
        </form>
    </div>
</div>

<div class="modal fade modal-premium-clean" id="modalInputSimpanan" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 460px;">
        <form action="<?= base_url('bendahara/simpanan/store') ?>" method="post" class="modal-content">
            <?= csrf_field() ?>
            <div class="modal-header border-0 pt-4 px-4 pb-0">
                <h5 class="modal-title fw-bold text-dark tracking-tight"><i class="bi bi-cash-coin me-2" style="color: var(--bps-blue);"></i>Catat Transaksi Simpanan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="mb-3">
                    <label class="form-label">Anggota Koperasi</label>
                    <select name="id_anggota" class="form-select" required>
                        <option value="">-- Pilih Nama Anggota --</option>
                        <?php foreach($anggota as $a): ?>
                            <option value="<?= $a['id_anggota'] ?>"><?= esc($a['nama_anggota']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="row g-3 mb-3">
                    <div class="col-6">
                        <label class="form-label">Jenis Simpanan</label>
                        <select name="jenis_simpanan" class="form-select" required>
                            <option value="wajib">Wajib</option>
                            <option value="sukarela">Sukarela</option>
                            <option value="pokok">Pokok</option>
                        </select>
                    </div>
                    <div class="col-6">
                        <label class="form-label">Bank Penerima</label>
                        <select name="bank" class="form-select" required>
                            <option value="BRI">BRI</option>
                            <option value="BSI">BSI</option>
                        </select>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Jumlah Setoran (RP)</label>
                    <input type="number" name="jumlah" class="form-control fw-bold fs-5 text-success ps-3" placeholder="0" required>
                </div>
                <div class="mb-0">
                    <label class="form-label">Keterangan Transaksi</label>
                    <textarea name="keterangan" class="form-control" rows="2" placeholder="Contoh: Setoran wajib bulanan berjalan..."></textarea>
                </div>
                <input type="hidden" name="tgl_transaksi" value="<?= date('Y-m-d') ?>">
            </div>
            <div class="modal-footer border-0 p-4 pt-0">
                <button type="submit" class="btn btn-primary w-100 py-2.5 fw-bold shadow-sm rounded-3" style="background-color: var(--bps-blue); border: none; font-size: 0.9rem;">SIMPAN</button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const inputCari = document.getElementById('searchSimpanan');
        const bodyTabel = document.getElementById('tableBodySimpanan');
        
        if (inputCari && bodyTabel) {
            const barisTabel = bodyTabel.getElementsByTagName('tr');

            inputCari.addEventListener('keyup', function(e) {
                const teksPencarian = e.target.value.toLowerCase();
                
                for (let i = 0; i < barisTabel.length; i++) {
                    const kolomNama = barisTabel[i].getElementsByTagName('td')[1];
                    
                    if (kolomNama) {
                        const teksNama = kolomNama.textContent || kolomNama.innerText;
                        
                        if (teksNama.toLowerCase().indexOf(teksPencarian) > -1) {
                            barisTabel[i].style.display = "";
                        } else {
                            barisTabel[i].style.display = "none";
                        }
                    }
                }
            });
        }
    });
</script>

<?= $this->endSection(); ?>