<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
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
    body { background-color: #f4f7fe; }
    .tracking-tight { letter-spacing: -0.5px; }
    .card-stats { border: 1px solid #e2e8f0; border-radius: 16px; background: #ffffff; }
    .accent-orange { border-top: 4px solid var(--bps-orange) !important; }
    .accent-blue { border-top: 4px solid var(--bps-blue) !important; }
    .accent-green { border-top: 4px solid var(--bps-green) !important; }
    .table-premium-header th { font-weight: 600 !important; color: #64748b !important; text-transform: uppercase; background-color: #f8fafc !important; border-bottom: 1px solid #e2e8f0 !important; padding: 14px 16px; font-size: 0.72rem; letter-spacing: 0.5px; }
    .table-premium-body td { border-bottom: 1px solid #f1f5f9; padding: 14px 16px; vertical-align: middle; font-size: 0.85rem; color: #334155; }
    .avatar-circle { width: 34px; height: 34px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 0.78rem; color: #ffffff; }
    .avatar-1 { background-color: #00a2e9; }
    .avatar-2 { background-color: #4aa135; }
    .avatar-3 { background-color: #f48221; }
    .avatar-4 { background-color: #7c3aed; }
    .avatar-5 { background-color: #ec4899; }
</style>

<div class="container-fluid py-3 px-4">
    <div class="mb-4">
        <span class="text-uppercase tracking-widest fw-bold mb-1 d-block" style="font-size: 0.68rem; color: var(--bps-blue);">Otorisasi Validasi Kasir Bendahara</span>
        <h4 class="fw-bold text-dark tracking-tight mb-0">Verifikasi & Konfirmasi Penarikan Dana Berjenjang</h4>
    </div>

    <?php if (session()->getFlashdata('success')) : ?>
        <div class="alert alert-success border-0 shadow-sm mb-4 small rounded-3 bg-success bg-opacity-10 text-success d-flex align-items-center gap-2"><i class="bi bi-check-circle-fill"></i> <span><?= session()->getFlashdata('success') ?></span></div>
    <?php endif; ?>

    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card card-stats accent-orange p-3 shadow-sm d-flex flex-row align-items-center justify-content-between h-100">
                <div>
                    <small class="text-muted text-uppercase fw-bold" style="font-size: 0.65rem; letter-spacing: 0.5px;">Antrean Belum Dikirim</small>
                    <h4 class="fw-bold mb-0 mt-1" style="color: var(--bps-orange-dark);"><?= $stats['total_pending'] ?> Berkas</h4>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card card-stats accent-blue p-3 shadow-sm d-flex flex-row align-items-center justify-content-between h-100">
                <div>
                    <small class="text-muted text-uppercase fw-bold" style="font-size: 0.65rem; letter-spacing: 0.5px;">Sedang Di Meja Ketua</small>
                    <h4 class="fw-bold mb-0 mt-1" style="color: var(--bps-blue-dark);"><?= $stats['total_disetujui'] ?> Berkas</h4>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card card-stats accent-green p-3 shadow-sm d-flex flex-row align-items-center justify-content-between h-100">
                <div>
                    <small class="text-muted text-uppercase fw-bold" style="font-size: 0.65rem; letter-spacing: 0.5px;">Dana Di-ACC Pimpinan</small>
                    <h4 class="fw-bold mb-0 mt-1" style="color: var(--bps-green-dark);"><?= $stats['total_transfer'] ?> Berkas Siap Cair</h4>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-header bg-white border-0 py-3 px-4">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                <h6 class="fw-bold mb-0 text-dark tracking-tight">Daftar Permohonan Pencairan Dana Aktif</h6>
                <input type="text" id="cariNama" class="form-control form-control-sm border rounded-3 px-3 py-1.5" style="font-size: 0.82rem; width: 240px;" placeholder="Cari nama pemohon...">
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-premium-header">
                    <tr>
                        <th width="60" class="text-center">No</th>
                        <th>Nama Anggota Pemohon</th>
                        <th class="text-center">Tanggal Ajuan</th>
                        <th class="text-end">Nominal Penarikan</th>
                        <th class="text-center">Status Alur Berkas</th>
                        <th class="text-center">Tindakan Bendahara</th>
                    </tr>
                </thead>
                <tbody class="table-premium-body" id="bodyTabelNama">
                    <?php if(!empty($list_antrean)) : $no=1; $colorIndex = 1; foreach($list_antrean as $row) : 
                        $initials = strtoupper(substr($row['nama_anggota'], 0, 2));
                    ?>
                        <tr>
                            <td class="text-center font-monospace fw-bold">#<?= $no++ ?></td>
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <div class="avatar-circle avatar-<?= $colorIndex; ?>"><?= $initials ?></div>
                                    <div>
                                        <div class="fw-bold text-dark mb-0.5"><?= esc($row['nama_anggota']) ?></div>
                                        <small class="text-muted">Rekening: <?= esc($row['no_rekening']) ?></small>
                                    </div>
                                </div>
                            </td>
                            <td class="text-center small"><?= date('d/m/Y H:i', strtotime($row['tgl_pengajuan'])) ?> WIB</td>
                            <td class="text-end fw-bold font-monospace">Rp <?= number_format($row['jumlah_ditarik'], 0, ',', '.') ?></td>
                            
                            <td class="text-center">
                                <?php if($row['status_penarikan'] == 'pending') : ?>
                                    <span class="badge border px-2.5 py-1.5 rounded-pill text-uppercase fw-bold" style="border-color: var(--bps-orange) !important; color: var(--bps-orange-dark) !important; background-color: #fff7ed;">PENDING BENDAHARA</span>
                                <?php elseif($row['status_penarikan'] == 'disetujui') : ?>
                                    <span class="badge border px-2.5 py-1.5 rounded-pill text-uppercase fw-bold" style="border-color: #cbd5e1 !important; color: #475569 !important; background-color: #f8fafc;">DI MEJA VERIFIKATOR</span>
                                <?php else: ?>
                                    <span class="badge border px-2.5 py-1.5 rounded-pill text-uppercase fw-bold" style="border-color: var(--bps-green) !important; color: var(--bps-green-dark) !important; background-color: #f0fdf4;">ACC KETUA (SIAP CAIR)</span>
                                <?php endif; ?>
                            </td>
                            
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-1">
                                <?php if($row['status_penarikan'] == 'pending') : ?>
                                     <button type="button" data-url="<?= base_url('bendahara/konfirmasi_penarikan/proses/'.$row['id_penarikan'].'/setuju') ?>" class="btn btn-sm btn-primary btn-approve-trigger py-1.5 px-2.5" style="background-color: var(--bps-blue) !important; border:none;">
                                         <i class="bi bi-send-fill me-1"></i> Kirim ke verifikator
                                      </button>
                                <?php elseif($row['status_penarikan'] == 'ditransfer') : ?>
                                     <button type="button" class="btn btn-sm btn-success text-white py-1.5 px-2.5" style="background-color: var(--bps-green) !important; border:none;" data-bs-toggle="modal" data-bs-target="#modalTransfer<?= $row['id_penarikan'] ?>">
                                      <i class="bi bi-bank me-1"></i> Konfirmasi Kirim
                                     </button>
                                <?php else : ?>
                                     <span class="text-muted font-monospace small"><i class="bi bi-lock-fill me-1"></i> Menunggu Otorisasi Ketua</span>
                                <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php 
                        $colorIndex = ($colorIndex % 5) + 1;
                        endforeach; else : 
                    ?>
                        <tr><td colspan="6" class="text-center py-5 text-muted small">Tidak ada permohonan penarikan dana aktif.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php if(!empty($list_antrean)): foreach($list_antrean as $row): if($row['status_penarikan'] == 'ditransfer'): ?>
    <div class="modal fade modal-transfer-clean" id="modalTransfer<?= $row['id_penarikan'] ?>" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="max-width: 420px;">
            <div class="modal-content">
                <form action="<?= base_url('bendahara/konfirmasi_penarikan/proses/'.$row['id_penarikan'].'/transfer') ?>" method="post">
                    <?= csrf_field() ?>
                    <div class="modal-header">
                        <h5 class="modal-title fw-bold text-dark"><i class="bi bi-send-check-fill me-2" style="color: var(--bps-green);"></i>Validasi Kirim Dana Kasir</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4 pt-1">
                        <div class="p-2.5 rounded-3 mb-3 border bg-light text-start">
                            <label class="form-label mb-0.5 fw-bold">Anggota Penerima</label>
                            <span class="fw-bold text-dark d-block"><?= esc($row['nama_anggota']) ?></span>
                            <small class="text-primary fw-semibold">No Rekening: <?= esc($row['no_rekening'] ?? '-') ?></small>
                        </div>
                        <div class="mb-4 text-start">
                            <label class="form-label fw-bold" style="color: var(--bps-green-dark);">Nominal Pencairan (Rp)</label>
                            <div class="fs-4 fw-bold font-monospace" style="color: var(--bps-green-dark);">Rp <?= number_format($row['jumlah_ditarik'], 0, ',', '.') ?></div>
                        </div>
                        <button type="submit" class="btn btn-success w-100 py-2.5 fw-bold rounded-3 shadow-sm text-white" style="background-color: var(--bps-green) !important; border: none;">NYATAKAN TRANSFER LUNAS</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php endif; endforeach; endif; ?>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const approveButtons = document.querySelectorAll('.btn-approve-trigger');
        approveButtons.forEach(button => {
            button.addEventListener('click', function (e) {
                e.preventDefault();
                const targetUrl = this.getAttribute('data-url');
                Swal.fire({
                    title: 'Ajukan ke Verifikator?',
                    text: "Apakah dokumen penarikan dana anggota ini valid dan siap diteruskan ke verifikator untuk proses otorisasi?",
                    icon: 'question', showCancelButton: true, confirmButtonColor: '#00a2e9', cancelButtonColor: '#94a3b8', confirmButtonText: 'Ya, Kirim', cancelButtonText: 'Batal', customClass: { popup: 'rounded-4' }
                }).then((result) => {
                    if (result.isConfirmed) { window.location.href = targetUrl; }
                });
            });
        });
    });
</script>
<?= $this->endSection() ?>