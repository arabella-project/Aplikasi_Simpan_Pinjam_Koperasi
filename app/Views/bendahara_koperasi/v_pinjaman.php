<?= $this->extend('layout/main'); ?>
<?= $this->section('content'); ?>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

<style>
    :root {
        --bps-blue: #00a2e9;
        --bps-green: #4aa135;
        --bps-orange: #f48221;
        --bps-purple: #7c3aed;
        --bps-red: #ef4444;
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

    /* --- PREMIUM TABLES STYLING --- */
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

    .kontrak-divider { 
        border-top: 1px dashed #e2e8f0; 
        margin-top: 6px; 
        padding-top: 6px; 
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

    .modal-bps-clean .modal-content {
        border-radius: 20px !important;
        border: none !important;
        position: relative;
        overflow: hidden;
    }
    .modal-bps-clean .modal-content::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0; height: 5px;
        background: linear-gradient(90deg, var(--bps-blue) 0%, var(--bps-orange) 50%, var(--bps-green) 100%);
    }
    .modal-bps-clean .modal-header { border-bottom: none !important; padding: 24px 24px 8px 24px !important; }
    .modal-bps-clean .form-label {
        font-size: 11px !important;
        font-weight: 700 !important;
        color: var(--text-muted) !important;
        text-transform: uppercase !important;
        letter-spacing: 0.5px;
        margin-bottom: 5px;
    }
    .modal-bps-clean .form-control, 
    .modal-bps-clean .form-select {
        border-radius: 10px !important;
        padding: 10px 14px !important;
        border: 1.5px solid #d1d5db !important;
        background-color: #f8fafc;
        font-size: 0.9rem;
    }
    .modal-bps-clean .form-control:focus, 
    .modal-bps-clean .form-select:focus {
        background-color: #ffffff;
        border-color: var(--bps-blue) !important;
        box-shadow: 0 0 0 4px rgba(0, 162, 233, 0.12) !important;
    }
    .modal-bps-clean .btn-primary-full {
        background: var(--bps-blue) !important;
        border: none !important;
        padding: 11px !important;
        border-radius: 10px !important;
        font-weight: 600 !important;
        width: 100% !important;
    }
    .modal-bps-clean .btn-primary-full:hover { background: var(--bps-blue-dark) !important; }
</style>

<div class="container-fluid py-3 px-4">
    
    <div class="row align-items-center mb-4 g-3">
        <div class="col-12 col-md-6">
            <span class="text-uppercase tracking-widest pack-semibold mb-1 d-block" style="font-size: 0.68rem; color: var(--bps-blue);">Manajemen Data Pinjaman Anggota</span>
            <h4 class="fw-bold text-dark tracking-tight mb-1">Data Pinjaman Anggota</h4>
             </div>
        <div class="col-12 col-md-6 ms-auto d-flex justify-content-start justify-content-md-end gap-2 flex-wrap">
            <a href="<?= base_url('bendahara/pinjaman/export_excel_pinjaman') ?>" class="btn btn-success rounded-3 py-2 px-3 fw-bold small d-inline-flex align-items-center" style="font-size: 0.82rem;">
                <i class="bi bi-file-earmark-spreadsheet me-2"></i> Ekspor Angsuran Excel
            </a>
            <button class="btn btn-bps-blue rounded-3 py-2 px-3 fw-bold small" style="font-size: 0.82rem;" data-bs-toggle="modal" data-bs-target="#modalTambahPinjaman">
                <i class="bi bi-plus-lg me-2"></i> Input Pinjaman Baru
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
                        <small class="text-uppercase fw-bold text-muted" style="font-size: 0.65rem; letter-spacing: 0.5px;">Anggota Meminjam</small>
                        <h4 class="fw-bold text-dark tracking-tight mb-0 mt-1"><?= $total_peminjam; ?></h4>
                    </div>
                    <div class="metric-icon-circle" style="background-color: rgba(0, 162, 233, 0.08); color: var(--bps-blue);">
                        <i class="bi bi-person-fill-exclamation"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-3">
            <div class="card card-metric-summary p-3 shadow-sm h-100">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-uppercase fw-bold text-muted" style="font-size: 0.65rem; letter-spacing: 0.5px;">Total Hutang</small>
                        <h5 class="fw-bold text-dark tracking-tight mb-0 mt-1" style="font-size: 1.15rem;">Rp <?= number_format($total_sisa_hutang, 0, ',', '.'); ?></h5>
                           </div>
                    <div class="metric-icon-circle" style="background-color: rgba(239, 68, 68, 0.08); color: var(--bps-red);">
                        <i class="bi bi-cash-stack"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-3">
            <div class="card card-metric-summary p-3 shadow-sm h-100">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-uppercase fw-bold text-muted" style="font-size: 0.65rem; letter-spacing: 0.5px;">Tagihan Bulanan Kolektif</small>
                        <h5 class="fw-bold text-dark tracking-tight mb-0 mt-1" style="font-size: 1.15rem;">Rp <?= number_format($total_tagihan_bulanan, 0, ',', '.'); ?></h5>
                    </div>
                    <div class="metric-icon-circle" style="background-color: rgba(74, 161, 53, 0.08); color: var(--bps-green);">
                        <i class="bi bi-calendar-check-fill"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-3">
            <div class="card card-metric-summary p-3 shadow-sm h-100">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-uppercase fw-bold text-muted" style="font-size: 0.65rem; letter-spacing: 0.5px;">Total Pinjaman Aktif</small>
                        <h4 class="fw-bold text-dark tracking-tight mb-0 mt-1"><?= $total_kontrak_aktif; ?></h4>
                    </div>
                    <div class="metric-icon-circle" style="background-color: rgba(124, 58, 237, 0.08); color: var(--bps-purple);">
                        <i class="bi bi-file-earmark-medical-fill"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-header bg-white border-0 py-3 px-4">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                <h6 class="fw-bold text-dark mb-0 d-flex align-items-center gap-2"><i class="bi bi-list-task text-primary"></i> Monitoring Pinjaman Aktif</h6>
                <input type="text" id="searchPeminjam" class="form-control form-control-sm border rounded-3 px-3 py-1.5" style="font-size: 0.82rem; width: 220px;" placeholder="Cari Id peminjam...">
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-premium-header">
                    <tr>
                        <th class="text-center" width="60">NO</th>
                        <th class="text-start" width="260">PROFIL ANGGOTA</th>
                        <th class="text-center">ANGSURAN</th> 
                        <th class="text-end">BIAYA JASA</th> 
                        <th class="text-end">SISA UTANG</th>
                        <th class="text-end" width="210">ANGSURAN / BLN</th>
                        <th class="text-center" width="160">AKSI</th>
                    </tr>
                </thead>
                <tbody class="table-premium-body" id="tableBodyPinjaman">
                    <?php 
                    if (!empty($pinjaman)) : 
                        $db = \Config\Database::connect();
                        $no = 1 + (10 * ($pager->getCurrentPage('pinjaman_pager') - 1));
                        $colorIndex = 1;

                        foreach ($pinjaman as $p) :
                            $arr_id_pinjaman = explode(',', $p['append_id_pinjaman']);
                            $arr_angsuran_ke = explode(',', $p['append_angsuran_ke']);
                            $arr_jasa_perbulan = explode(',', $p['append_jasa_perbulan']);
                            $initials = strtoupper(substr($p['nama_anggota'], 0, 2));
                    ?>
                        <tr>
                            <td class="text-center text-secondary fw-bold"><?= $no++; ?></td>
                            <td class="text-start">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="avatar-circle avatar-<?= $colorIndex; ?>"><?= $initials ?></div>
                                    <div>
                                        <div class="fw-bold text-dark mb-0.5" style="font-size: 0.88rem;"><?= esc($p['nama_anggota']); ?></div>
                                        <span class="text-muted fw-semibold" style="font-size: 0.72rem;">ID: #<?= $p['id_anggota']; ?></span>
                                    </div>
                                </div>
                            </td>
                            
                            <td class="text-center">
                                <?php foreach ($arr_id_pinjaman as $idx => $id_pjn) : ?>
                                    <div class="<?= $idx > 0 ? 'kontrak-divider' : '' ?>">
                                        <span class="badge bg-light text-dark border px-2 py-1 rounded font-monospace" style="font-size: 0.7rem;">
                                            #<?= $id_pjn ?> (Ke-<?= $arr_angsuran_ke[$idx]; ?>)
                                        </span>
                                    </div>
                                <?php endforeach; ?>
                            </td>

                            <td class="text-end font-monospace text-secondary">
                                <?php foreach ($arr_jasa_perbulan as $idx => $jasa) : ?>
                                    <div class="<?= $idx > 0 ? 'kontrak-divider' : '' ?>">
                                        Rp <?= number_format((float)$jasa, 0, ',', '.'); ?>
                                    </div>
                                <?php endforeach; ?>
                            </td>

                            <td class="text-end fw-semibold text-danger font-monospace">Rp <?= number_format($p['gabung_sisa_hutang'], 0, ',', '.'); ?></td>
                            <td class="text-end">
                                <div class="d-flex align-items-center justify-content-end gap-2">
                                    <span class="fw-bold font-monospace" style="font-size: 0.92rem; color: var(--bps-green);">
                                        Rp <?= number_format($p['gabung_angsuran_bulanan'], 0, ',', '.'); ?>
                                    </span>
                              </div>
                            </td>
                            
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-1">
                                    <button class="btn btn-sm btn-bps-blue rounded-pill py-1 px-2.5 fw-bold shadow-sm" style="font-size:0.75rem;" data-bs-toggle="modal" data-bs-target="#modalBayar<?= $p['id_pinjaman_ref']; ?>">
                                        <i class="bi bi-cash-coin me-1"></i> Bayar
                                    </button>
                                    <a href="<?= base_url('bendahara/pinjaman/histori/'.$p['id_anggota']); ?>" class="btn btn-sm btn-light border rounded-pill py-1 px-2.5 fw-bold shadow-sm" style="font-size: 0.75rem; color: var(--text-dark);">
                                        <i class="bi bi-clock-history me-1"></i> Histori
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php 
                        $colorIndex = ($colorIndex % 5) + 1;
                        endforeach; else : 
                    ?>
                        <tr><td colspan="7" class="text-center py-5 text-muted small">Belum ada riwayat data pinjaman aktif di sistem.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <?php if (!empty($pinjaman)) : ?>
            <div class="card-footer bg-white border-0 py-3 px-4 d-flex flex-column flex-sm-row justify-content-between align-items-center border-top gap-3">
                <div class="small text-muted text-center text-sm-start">Menampilkan baris data riwayat pinjaman berjalan koperasi secara berkala.</div>
                <div class="pagination-container">
                    <?= $pager->links('pinjaman_pager', 'default_full') ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php 
if (!empty($pinjaman)) : 
    foreach ($pinjaman as $p) : 
        $cekDouble = $db->table('pinjaman')->where('id_anggota', $p['id_anggota'])->where('status_pinjaman', 'aktif')->get()->getResultArray();
        $punyaDuaPinjaman = (count($cekDouble) >= 2);
        
        $totalAngsuranBulananKeduanya = 0;
        foreach($cekDouble as $cd) { 
            $totalAngsuranBulananKeduanya += ((float)$cd['angsuran_perbulan'] + (float)$cd['jasa_perbulan']); 
        }
?>
<div class="modal fade modal-bps-clean" id="modalBayar<?= $p['id_pinjaman_ref']; ?>" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 450px;">
        <form action="<?= base_url('bendahara/pinjaman/store_angsuran') ?>" method="post" class="modal-content">
            <?= csrf_field() ?>
            <input type="hidden" name="id_pinjaman" value="<?= $p['id_pinjaman_ref']; ?>">
            <input type="hidden" name="id_anggota" value="<?= $p['id_anggota']; ?>">
            
            <div class="modal-header">
                <h5 class="modal-title fw-bold text-dark"><i class="bi bi-wallet2 me-2" style="color: var(--bps-blue);"></i>Form Validasi Setoran</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4 pt-1">
                <div class="p-2.5 rounded-3 mb-3 border bg-light">
                    <label class="form-label mb-0.5">Nama Anggota Pembayar</label>
                    <span class="fw-bold text-dark d-block" style="font-size: 0.9rem;"><?= esc($p['nama_anggota']); ?></span>
                </div>

                <?php if ($punyaDuaPinjaman): ?>
                    <div class="mb-3 p-3 bg-warning bg-opacity-10 border border-warning border-opacity-30 rounded-3">
                        <label class="form-label text-warning-emphasis fw-bold">Skema Multi-Kontrak</label>
                        <div class="form-check mb-1.5">
                            <input class="form-check-input" type="radio" name="target_pembayaran" id="target1_<?= $p['id_pinjaman_ref'] ?>" value="single" checked onclick="document.getElementById('inputNominal_<?= $p['id_pinjaman_ref'] ?>').value='<?= (int)($p['gabung_angsuran_bulanan'] / 2) ?>'">
                            <label class="form-check-label text-dark small" for="target1_<?= $p['id_pinjaman_ref'] ?>">
                                Bayar 1 Angsuran Saja
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="target_pembayaran" id="target2_<?= $p['id_pinjaman_ref'] ?>" value="keduanya" onclick="document.getElementById('inputNominal_<?= $p['id_pinjaman_ref'] ?>').value='<?= (int)$totalAngsuranBulananKeduanya ?>'">
                            <label class="form-check-label text-dark small" for="target2_<?= $p['id_pinjaman_ref'] ?>">
                                <span class="badge bg-danger me-1">Gabungan</span> Bayar Keduanya (<strong>Rp <?= number_format($totalAngsuranBulananKeduanya,0,',','.') ?></strong>)
                            </label>
                        </div>
                    </div>
                <?php else: ?>
                    <input type="hidden" name="target_pembayaran" value="single">
                <?php endif; ?>

                <div class="row g-3 mb-3">
                    <div class="col-6">
                        <label class="form-label">Metode Pembayaran</label>
                        <select name="metode" class="form-select" required>
                            <option value="Transfer">Transfer</option>
                            <option value="Tunai">Tunai</option>
                        </select>
                    </div>
                    <div class="col-6">
                        <label class="form-label">Ke Bank / Kas Mana</label>
                        <select name="bank" class="form-select" required>
                            <option value="BRI">BRI</option>
                            <option value="BSI">BSI</option>
                            <option value="Tunai Brankas">Tunai</option>
                        </select>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Jumlah Uang Setoran Angsuran (Rp)</label>
                    <input type="number" name="jumlah_bayar" id="inputNominal_<?= $p['id_pinjaman_ref'] ?>" class="form-control fw-bold fs-5 text-success" value="<?= (int)($p['gabung_angsuran_bulanan'] / $p['total_kontrak']); ?>" required>
                </div>
                
                <div class="mb-4">
                    <label class="form-label">Tanggal Validasi Buku</label>
                    <input type="date" name="tgl_bayar" class="form-control" value="<?= date('Y-m-d'); ?>" required>
                </div>

                <button type="submit" class="btn btn-primary-full text-white shadow-sm fw-bold">Validasi Angsuran & Kirim Email</button>
            </div>
        </form>
    </div>
</div>
<?php endforeach; endif; ?>

<div class="modal fade modal-bps-clean" id="modalTambahPinjaman" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 440px;">
        <form action="<?= base_url('bendahara/pinjaman/store_pinjaman') ?>" method="post" class="modal-content">
            <?= csrf_field() ?>
            <div class="modal-header">
                <h5 class="modal-title fw-bold text-dark"><i class="bi bi-pencil-square me-2" style="color: var(--bps-blue);"></i>Registrasi Kontrak Pinjaman</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4 pt-1">
                <div class="mb-3">
                    <label class="form-label">Nama Anggota Pemohon</label>
                    <select name="id_anggota" class="form-select" required>
                        <option value="">-- Cari Nama Anggota --</option>
                        <?php foreach($anggota as $a): ?>
                            <option value="<?= $a['id_anggota'] ?>"><?= esc($a['nama_anggota']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Plafon Utama Pinjaman Pokok (Rp)</label>
                    <input type="number" name="jumlah_total" class="form-control fw-bold fs-5 text-primary" required>
                </div>
                <div class="row g-3 mb-3">
                    <div class="col-6">
                        <label class="form-label">Angsuran Pokok / Bulan</label>
                        <input type="number" name="angsuran_perbulan" class="form-control" required>
                    </div>
                    <div class="col-6">
                        <label class="form-label">Jasa Bunga / Bulan</label>
                        <input type="number" name="jasa_perbulan" class="form-control" required>
                    </div>
                </div>
                <div class="mb-4">
                    <label class="form-label">Biaya Potongan Administrasi Awal</label>
                    <input type="number" name="jumlah_potongan" class="form-control" placeholder="0 (Bisa dikosongkan)">
                </div>
                <button type="submit" class="btn btn-primary-full text-white shadow-sm fw-bold">Cairkan Pinjaman Kontrak</button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchPeminjam');
        const tableBody = document.getElementById('tableBodyPinjaman');
        
        if (searchInput && tableBody) {
            const rows = tableBody.getElementsByTagName('tr');

            searchInput.addEventListener('keyup', function(e) {
                const text = e.target.value.toLowerCase();
                
                for (let i = 0; i < rows.length; i++) {
                    const nameColumn = rows[i].getElementsByTagName('td')[1];
                    
                    if (nameColumn) {
                        const nameText = nameColumn.textContent || nameColumn.innerText;
                        
                        if (nameText.toLowerCase().indexOf(text) > -1) {
                            rows[i].style.display = "";
                        } else {
                            rows[i].style.display = "none";
                        }
                    }
                }
            });
        }
    });
</script>

<?= $this->endSection(); ?>