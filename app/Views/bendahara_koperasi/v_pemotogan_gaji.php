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

    .scrollable-table-wrapper {
        max-height: 60vh; 
        overflow-y: auto;  
        overflow-x: auto;  
        border-radius: 0 0 16px 16px;
    }

    .table-premium-header th {
        position: sticky !important;
        top: 0 !important;
        z-index: 10;
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
    
    .table-premium-body td.text-start { text-align: left !important; }
    .table-premium-body td.text-end { text-align: right !important; }
    .table-hover tbody tr:hover { background-color: #f8fafc; }

    .btn-premium-pill {
        border-radius: 8px;
        font-weight: 600;
        padding: 8px 20px;
        font-size: 0.82rem;
        transition: all 0.2s ease;
    }
    .btn-premium-pill:hover {
        transform: translateY(-1px);
    }
    
    .btn-bps-blue {
        background-color: var(--bps-blue);
        color: #ffffff;
        border: none;
    }
    .btn-bps-blue:hover {
        background-color: var(--bps-blue-dark);
        color: #ffffff;
        box-shadow: 0 4px 12px rgba(0, 162, 233, 0.2);
    }
    
    .btn-outline-bps-blue {
        color: var(--bps-blue);
        border: 1.5px solid var(--bps-blue);
        background-color: transparent;
    }
    .btn-outline-bps-blue:hover {
        color: #ffffff;
        background-color: var(--bps-blue);
        border-color: var(--bps-blue);
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
</style>

<div class="container-fluid py-3 px-4">
    
    <div class="row align-items-center mb-4 g-3">
        <div class="col-12 col-md-6">
            <span class="text-uppercase tracking-widest fw-bold mb-1 d-block" style="font-size: 0.68rem; color: var(--bps-blue);">Manajemen Siklus Payroll Jurnal</span>
            <h4 class="fw-bold text-dark tracking-tight mb-0">Potongan Gaji (Angsuran Pinjaman)</h4>
            </div>
        <div class="col-12 col-md-6 ms-auto d-flex justify-content-start justify-content-md-end gap-2 flex-wrap">
            <a href="<?= base_url('bendahara/potongan/generate') ?>" class="btn btn-outline-bps-blue btn-premium-pill shadow-sm">
                <i class="bi bi-arrow-repeat me-2"></i> Tarik Data
            </a>
            
            <?php if (isset($jumlah_draft) && $jumlah_draft > 0) : ?>
                <a href="<?= base_url('bendahara/potongan/ajukan_semua') ?>" class="btn btn-bps-blue btn-premium-pill shadow-sm">
                    <i class="bi bi-send-check me-2"></i> Ajukan Bendahara Kantor (<?= $jumlah_draft ?> Data)
                </a>
            <?php endif; ?>
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
                        <small class="text-uppercase fw-bold text-muted" style="font-size: 0.65rem; letter-spacing: 0.5px;">Total Log Payroll</small>
                        <h4 class="fw-bold text-dark tracking-tight mb-0 mt-1"><?= count($list_potongan ?? []); ?></h4>
                    </div>
                    <div class="metric-icon-circle" style="background-color: rgba(124, 58, 237, 0.08); color: var(--bps-purple);">
                        <i class="bi bi-file-earmark-bar-graph"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-3">
            <div class="card card-metric-summary p-3 shadow-sm h-100">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-uppercase fw-bold text-muted" style="font-size: 0.65rem; letter-spacing: 0.5px;">Total Nominal Potongan</small>
                        <?php 
                            $grand_total = 0;
                            if(!empty($list_potongan)) {
                                foreach($list_potongan as $r) { $grand_total += (float)$r['total_potongan']; }
                            }
                        ?>
                        <h5 class="fw-bold text-dark tracking-tight mb-0 mt-1" style="font-size: 1.15rem;">Rp <?= number_format($grand_total, 0, ',', '.'); ?></h5>
                    </div>
                    <div class="metric-icon-circle" style="background-color: rgba(244, 130, 33, 0.08); color: var(--bps-orange);">
                        <i class="bi bi-scissors"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-3">
            <div class="card card-metric-summary p-3 shadow-sm h-100">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-uppercase fw-bold text-muted" style="font-size: 0.65rem; letter-spacing: 0.5px;">Kompilasi Draft</small>
                        <h4 class="fw-bold text-dark tracking-tight mb-0 mt-1"><?= $jumlah_draft ?? 0; ?> Berkas</h4>
                    </div>
                    <div class="metric-icon-circle" style="background-color: rgba(0, 162, 233, 0.08); color: var(--bps-blue);">
                        <i class="bi bi-folder-symlink"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-3">
            <div class="card card-metric-summary p-3 shadow-sm h-100">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-uppercase fw-bold text-muted" style="font-size: 0.65rem; letter-spacing: 0.5px;">Siklus Periode Aktif</small>
                        <h5 class="fw-bold text-dark tracking-tight mb-0 mt-1" style="font-size: 1.15rem;"><?= !empty($list_potongan) ? esc($list_potongan[0]['bulan_tahun']) : date('F Y') ?></h5>
                        </div>
                    <div class="metric-icon-circle" style="background-color: rgba(74, 161, 53, 0.08); color: var(--bps-green);">
                        <i class="bi bi-calendar-range-fill"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm bg-white p-0 rounded-4 overflow-hidden">
        <div class="card-header bg-white border-0 py-3 px-4">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                <h6 class="fw-bold text-dark mb-0 d-flex align-items-center gap-2"><i class="bi bi-list-columns-reverse text-primary"></i> Manifes Potongan Slip Gaji Anggota</h6>
                <div>
                    <input type="text" id="cariNama" class="form-control form-control-sm border rounded-3 px-3 py-1.5" style="font-size: 0.82rem; width: 240px;" placeholder="Cari nama anggota...">
                </div>
            </div>
        </div>

        <div class="scrollable-table-wrapper">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-premium-header">
                    <tr>
                        <th class="text-center" width="60">NO</th>
                        <th class="text-start">NAMA ANGGOTA</th>
                        <th class="text-center" width="180">PERIODE BUKU</th>
                        <th class="text-end" width="260">TOTAL POTONGAN ANGSURAN</th>
                        <th class="text-center" width="240">STATUS VERIFIKASI</th>
                        <th class="text-center" width="160">TGL PENGAJUAN</th>
                    </tr>
                </thead>
                <tbody class="table-premium-body" id="bodyTabelNama">
                    <?php if (!empty($list_potongan)) : $no = 1; $colorIndex = 1; foreach ($list_potongan as $row) : 
                        $initials = strtoupper(substr($row['nama_anggota'], 0, 2));
                    ?>
                        <tr>
                            <td class="text-center text-secondary fw-bold"><?= $no++; ?></td>
                            <td class="text-start">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="avatar-circle avatar-<?= $colorIndex; ?>"><?= $initials ?></div>
                                    <div>
                                        <div class="fw-bold text-dark mb-0.5" style="font-size: 0.88rem;"><?= esc($row['nama_anggota']); ?></div>
                                        <span class="badge bg-light text-secondary border px-2 py-0.5 font-monospace" style="font-size: 0.65rem; border-radius: 4px;">ID: #<?= $row['id_anggota']; ?></span>
                                    </div>
                                </div>
                            </td>
                            <td class="text-center text-uppercase fw-semibold text-secondary" style="font-size: 0.8rem; letter-spacing: 0.3px;">
                                <i class="bi bi-calendar-range me-1.5" style="color: var(--bps-blue);"></i><?= esc($row['bulan_tahun']); ?>
                            </td>
                            <td class="text-end fw-bold font-monospace" style="color: var(--bps-orange-dark); font-size: 0.92rem;">
                                Rp <?= number_format($row['total_potongan'], 0, ',', '.'); ?>
                            </td>
                            <td class="text-center">
                                <?php 
                                    $status = trim(strtolower($row['status_potongan']));
                                    $badgeStyle = [
                                        'draft'    => 'border-color: #cbd5e1 !important; color: var(--text-muted) !important; background-color: #f8fafc;',
                                        'diajukan' => 'border-color: var(--bps-blue) !important; color: var(--bps-blue-dark) !important; background-color: #f0f9ff;',
                                        'diterima' => 'border-color: var(--bps-green) !important; color: var(--bps-green-dark) !important; background-color: #f0fdf4;',
                                        'ditolak'  => 'border-color: var(--bps-orange) !important; color: var(--bps-orange-dark) !important; background-color: #fff7ed;'
                                    ][$status] ?? 'border-color: #cbd5e1 !important; color: var(--text-dark) !important; background-color: #f1f5f9;';
                                ?>
                                <span class="badge rounded-pill border fw-bold text-uppercase" style="font-size: 0.68rem; <?= $badgeStyle ?>">
                                    <?= ($status == 'diajukan') ? 'Menunggu Validasi' : esc($row['status_potongan']); ?>
                                </span>
                            </td>
                            <td class="text-center text-secondary font-monospace" style="font-size: 0.82rem;">
                                <?= ($row['tgl_pengajuan']) ? date('d/m/Y', strtotime($row['tgl_pengajuan'])) : '<span class="text-muted opacity-50">-</span>'; ?>
                            </td>
                        </tr>
                    <?php 
                        $colorIndex = ($colorIndex % 5) + 1;
                        endforeach; else : 
                    ?>
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <div class="py-4">
                                    <i class="bi bi-cloud-download text-muted opacity-30" style="font-size: 2.5rem;"></i>
                                    <p class="mt-2 text-secondary fw-semibold mb-0" style="font-size: 0.85rem;">Belum ada kompilasi berkas potongan bulan berjalan.</p>
                                    <small class="text-muted d-block mt-0.5">Silakan klik tombol <strong>"Tarik Data"</strong> di atas untuk memulai siklus payroll baru.</small>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const inputCari = document.getElementById('cariNama');
        const bodyTabel = document.getElementById('bodyTabelNama');
        
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