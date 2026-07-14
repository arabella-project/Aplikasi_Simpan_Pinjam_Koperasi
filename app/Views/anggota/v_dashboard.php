<?= $this->extend('layout/v_anggota_layout') ?>
<?= $this->section('content') ?>

<style>
    /* Styling Komponen Dashboard */
    .stat-card { border: none; border-radius: 16px; background: #ffffff; border: 1px solid #e2e8f0; padding: 20px; transition: all 0.3s; }
    .stat-card:hover { transform: translateY(-3px); box-shadow: 0 12px 24px rgba(13,27,42,0.04); }
    .stat-icon-wrapper { width: 44px; height: 44px; display: flex; align-items: center; justify-content: center; border-radius: 12px; font-size: 1.35rem; }
    
    /* Banner SAW Modern (Perpaduan Gradasi Biru-Orange Premium) */
    .saw-banner-card { border: none; border-radius: 20px; background: linear-gradient(105deg, #0284c7 0%, #0369a1 40%, #1d4ed8 70%, #f97316 100%); position: relative; overflow: hidden; }
    .saw-vector-icon { position: absolute; right: -20px; bottom: -30px; font-size: 11rem; color: rgba(255,255,255,0.06); transform: rotate(-15px); pointer-events: none; }
    
    /* Profile Summary Card */
    .profile-card-right { border: none; border-radius: 20px; background: #ffffff; border: 1px solid #e2e8f0; }
    .profile-avatar-img { width: 64px; height: 64px; background: #eff6ff; color: #2563eb; display: flex; align-items: center; justify-content: center; border-radius: 50%; font-size: 2rem; border: 2px solid #dbeafe; }
    
    /* Quick Action Buttons */
    .btn-action-panel { border: none; border-radius: 12px; padding: 10px; font-weight: 600; font-size: 0.82rem; display: flex; align-items: center; justify-content: center; gap: 8px; width: 100%; transition: all 0.2s; text-decoration: none; }
    .btn-action-orange { background: #f97316; color: #fff; }
    .btn-action-orange:hover { background: #ea580c; color: #fff; }
    .btn-action-blue { background: #0284c7; color: #fff; }
    .btn-action-blue:hover { background: #0369a1; color: #fff; }

    /* Custom Table & Card Progress Style */
    .tracking-table-card { border: none; border-radius: 20px; background: #ffffff; border: 1px solid #e2e8f0; overflow: hidden; }
    .table-premium thead th { background-color: #f8fafc; color: #64748b; font-weight: 600; font-size: 0.75rem; letter-spacing: 0.5px; padding: 16px 24px; border-bottom: 1px solid #e2e8f0; }
    .table-premium tbody td { padding: 16px 24px; border-bottom: 1px solid #f1f5f9; font-size: 0.88rem; color: #334155; }
    .table-premium tbody tr:last-child td { border-bottom: none; }
    .table-premium tbody tr:hover { background-color: #f8fafc; }
</style>

<div class="container-fluid p-0">
    <!-- ROW 1: KARTU STATISTIK RINGKASAN -->
    <div class="row g-4 mb-4">
        <div class="col-md-6">
            <div class="stat-card">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <small class="text-muted text-uppercase fw-semibold tracking-wider" style="font-size: 0.7rem;">Total Simpanan</small>
                        <h4 class="fw-bold text-dark mt-1 mb-0"><?= $total_simpanan ?></h4>
                        <small class="text-secondary opacity-70" style="font-size: 0.7rem;">Total saldo simpanan Anda saat ini</small>
                    </div>
                    <div class="stat-icon-wrapper bg-primary-subtle text-primary"><i class="bi bi-wallet2"></i></div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="stat-card">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <small class="text-muted text-uppercase fw-semibold tracking-wider" style="font-size: 0.7rem;">Sisa Pinjaman</small>
                        <h4 class="fw-bold text-dark mt-1 mb-0"><?= $sisa_pinjaman ?></h4>
                        <div class="mt-1 mb-1">
                            <span class="badge rounded-pill <?= $has_loan ? 'bg-danger-subtle text-danger' : 'bg-success-subtle text-success' ?> fw-bold py-1 px-2.5" style="font-size: 0.65rem;">
                                <i class="bi bi-exclamation-circle-fill me-1"></i><?= $status_pinjam ?>
                            </span>
                        </div>
                    </div>
                    <div class="stat-icon-wrapper <?= $has_loan ? 'bg-danger-subtle text-danger' : 'bg-success-subtle text-success' ?>">
                        <i class="bi bi-credit-card"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ROW 2: BANNER ADVERTISING SAW & MINI PROFIL -->
    <div class="row g-4 mb-4">
        <div class="col-lg-8">
            <div class="card saw-banner-card text-white p-5 h-100 d-flex flex-column justify-content-center">
                <i class="bi bi-cpu saw-vector-icon"></i>
                <div style="max-width: 80%;">
                    <small class="text-uppercase fw-bold text-info tracking-widest" style="font-size: 0.75rem; color: #38bdf8 !important;">Decision Support System</small>
                    <h2 class="fw-bold my-2">Ajukan Pinjaman Sekarang</h2>
                    <p class="opacity-75 small mb-4">Pengajuan Pinjaman akan dinilai menggunakan sistem pendukung keputusan yaitu Simple Additive Weighting untuk membantu proses penilaian kelayakan pinjaman secara objektif dan transparan.</p>
                    <a href="<?= base_url('anggota/pengajuan') ?>" class="btn btn-light rounded-pill px-4 py-2 fw-bold text-primary shadow-sm" style="font-size: 0.85rem;">
                        <i class="bi bi-plus-circle-fill me-2"></i>Ajukan
                    </a>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card profile-card-right p-4 h-100 d-flex flex-column justify-content-between">
                <div>
                    <div class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-3">
                        <h6 class="fw-bold mb-0 text-dark">Profile Anggota</h6>
                        <i class="bi bi-three-dots text-muted"></i>
                    </div>
                    <div class="d-flex align-items-center gap-3 mb-4">
                        <div class="profile-avatar-img"><i class="bi bi-person-fill"></i></div>
                        <div>
                            <h6 class="fw-bold text-dark mb-1"><?= esc($nama_lengkap) ?></h6>
                        </div>
                    </div>
                    <div class="small text-secondary">
                        <div class="d-flex justify-content-between mb-2">
                            <span>ID Anggota</span>
                            <span class="fw-bold text-dark">BPS-<?= esc($id_anggota) ?></span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Sesi Status</span>
                            <span class="text-success fw-bold">Aktif</span>
                        </div>
                    </div>
                </div>
                <div class="border-top pt-3 mt-3">
                    <div class="row g-2 justify-content-center">
                        <div class="col-6">
                            <a href="<?= base_url('anggota/profil') ?>" class="btn-action-panel btn-action-orange"><i class="bi bi-gear-fill"></i> Edit Profil</a>
                        </div>
                        <div class="col-6">
                            <a href="<?= base_url('anggota/penarikan') ?>" class="btn-action-panel btn-action-blue"><i class="bi bi-cash-draw"></i> Tarik Dana</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        
        <div class="col-lg-6">
            <div class="card tracking-table-card shadow-sm h-100 mb-0">
                <div class="d-flex justify-content-between align-items-center p-4 border-bottom bg-white">
                    <div>
                        <h6 class="fw-bold text-dark mb-1">
                            <i class="bi bi-bezier2 me-2 text-primary"></i>Tracking Status Pinjaman Anda
                        </h6>
                        <p class="text-muted small mb-0">Memantau real-time perkembangan berkas pengajuan pinjaman.</p>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-premium align-middle mb-0">
                        <thead>
                            <tr class="text-uppercase">
                                <th class="ps-4">Tgl Pengajuan</th>
                                <th>Nominal</th>
                                <th class="text-center">Status Tracking</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($pengajuan)) : foreach ($pengajuan as $p) : ?>
                                <tr>
                                    <td class="fw-semibold ps-4">
                                        <div class="text-dark"><?= date('d M Y', strtotime($p['tgl_pengajuan'])) ?></div>
                                        <small class="text-muted text-xs"><?= date('H:i', strtotime($p['tgl_pengajuan'])) ?> WIB</small>
                                    </td>
                                    <td class="fw-bold text-primary">
                                        Rp <?= number_format($p['jumlah_diajukan'], 0, ',', '.') ?>
                                    </td>
                                    <td class="text-center">
                                        <?php $status_clean = trim(strtolower($p['status_pengajuan'])); ?>
                                        <?php if ($status_clean == 'pending') : ?>
                                            <span class="badge rounded-pill bg-warning-subtle text-warning border border-warning-subtle py-1.5 px-3 fw-bold"><i class="bi bi-clock me-1"></i> Pending</span>
                                        <?php elseif ($status_clean == 'proses') : ?>
                                            <span class="badge rounded-pill bg-info-subtle text-info border border-info-subtle py-1.5 px-3 fw-bold"><i class="bi bi-cpu me-1"></i> Diproses</span>
                                        <?php elseif ($status_clean == 'disetujui') : ?>
                                            <span class="badge rounded-pill bg-primary-subtle text-primary border border-primary-subtle py-1.5 px-3 fw-bold"><i class="bi bi-check2-circle me-1"></i> Disetujui</span>
                                        <?php elseif ($status_clean == 'transfer') : ?>
                                            <span class="badge rounded-pill bg-success-subtle text-success border border-success-subtle py-1.5 px-3 fw-bold"><i class="bi bi-cash-stack me-1"></i> Telah ditransfer</span>
                                        <?php else : ?>
                                            <span class="badge rounded-pill bg-danger-subtle text-danger border border-danger-subtle py-1.5 px-3 fw-bold"><i class="bi bi-x-circle-fill me-1"></i> Ditolak</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; else : ?>
                                <tr>
                                    <td colspan="3" class="text-center py-5 text-muted small italic">
                                        <i class="bi bi-clipboard2-x d-block fs-3 mb-2 opacity-50"></i>
                                        Anda belum memiliki riwayat pengajuan pinjaman saat ini.
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card tracking-table-card shadow-sm h-100 mb-0">
                <div class="d-flex justify-content-between align-items-center p-4 border-bottom bg-white">
                    <div>
                        <h6 class="fw-bold text-dark mb-1">
                            <i class="bi bi-arrow-down-up me-2 text-info"></i>Status Progress Penarikan Dana
                        </h6>
                        <p class="text-muted small mb-0">Pantau sejauh mana proses pencairan dana simpanan Anda berjalan.</p>
                    </div>
                </div>
                
                <div class="card-body p-4 bg-light bg-opacity-25 d-flex flex-column justify-content-center">
                    <?php if (!empty($penarikan)) : ?>
                        <?php 
                            $latest_tarik = $penarikan[0]; 
                            $status_tarik = trim(strtolower($latest_tarik['status_penarikan']));
                        ?>
                        
                        <div class="card border-0 shadow-sm p-4 bg-white rounded-4 w-100 mb-0">
                            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 border-bottom pb-3 mb-4">
                                <div>
                                    <span class="text-muted small">Waktu Pengajuan:</span>
                                    <div class="fw-bold text-dark small">
                                        <?= date('d M Y', strtotime($latest_tarik['tgl_pengajuan'])) ?> - <?= date('H:i', strtotime($latest_tarik['tgl_pengajuan'])) ?> WIB
                                    </div>
                                </div>
                                <div class="text-end">
                                    <span class="text-muted small d-block">Nominal Penarikan:</span>
                                    <span class="fs-5 fw-bold text-info">Rp <?= number_format($latest_tarik['jumlah_ditarik'], 0, ',', '.') ?></span>
                                </div>
                            </div>

                            <div class="position-relative my-4 pb-2">
                                <div class="progress" style="height: 6px; border-radius: 10px;">
                                    <?php
                                        $progress_percent = 15; // default pending
                                        if ($status_tarik == 'disetujui') $progress_percent = 60;
                                        if ($status_tarik == 'ditransfer') $progress_percent = 100;
                                        if ($status_tarik == 'ditolak') $progress_percent = 100;
                                    ?>
                                    <div class="progress-bar <?= $status_tarik == 'ditolak' ? 'bg-danger' : 'bg-info' ?>" role="progressbar" style="width: <?= $progress_percent ?>%;" aria-valuenow="<?= $progress_percent ?>" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>

                                <div class="d-flex justify-content-between position-absolute w-100 top-50 translate-middle-y" style="left: 0; padding: 0 5px;">
                                    
                                    <div class="text-center position-relative">
                                        <div class="rounded-circle bg-info text-white d-flex align-items-center justify-content-center shadow" style="width: 26px; height: 26px; font-size: 0.8rem;">
                                            <i class="bi bi-file-earmark-arrow-up-fill"></i>
                                        </div>
                                        <div class="fw-bold text-dark small mt-2" style="font-size: 0.75rem;">Diajukan</div>
                                    </div>

                                    <div class="text-center position-relative">
                                        <?php 
                                            $is_step2_active = ($status_tarik == 'disetujui' || $status_tarik == 'ditransfer');
                                            $node_color = $is_step2_active ? 'bg-info text-white shadow' : 'bg-secondary bg-opacity-20 text-muted';
                                        ?>
                                        <div class="rounded-circle <?= $node_color ?> d-flex align-items-center justify-content-center" style="width: 26px; height: 26px; font-size: 0.8rem;">
                                            <i class="bi bi-shield-check"></i>
                                        </div>
                                        <div class="<?= $is_step2_active ? 'fw-bold text-dark' : 'text-muted' ?> small mt-2" style="font-size: 0.75rem;">Approved</div>
                                    </div>

                                    <div class="text-center position-relative">
                                        <?php 
                                            if ($status_tarik == 'ditransfer') {
                                                $final_node = 'bg-success text-white shadow';
                                                $final_icon = 'bi-check-all';
                                                $final_label = 'Selesai';
                                            } elseif ($status_tarik == 'ditolak') {
                                                $final_node = 'bg-danger text-white shadow';
                                                $final_icon = 'bi-x-lg';
                                                $final_label = 'Ditolak';
                                            } else {
                                                $final_node = 'bg-secondary bg-opacity-20 text-muted';
                                                $final_icon = 'bi-cash-coin';
                                                $final_label = 'Pencairan';
                                            }
                                        ?>
                                        <div class="rounded-circle <?= $final_node ?> d-flex align-items-center justify-content-center" style="width: 26px; height: 26px; font-size: 0.8rem;">
                                            <i class="bi <?= $final_icon ?>"></i>
                                        </div>
                                        <div class="<?= ($status_tarik == 'ditransfer' || $status_tarik == 'ditolak') ? 'fw-bold text-dark' : 'text-muted' ?> small mt-2" style="font-size: 0.75rem;"><?= $final_label ?></div>
                                    </div>

                                </div>
                            </div>

                            <div class="mt-4 p-2.5 rounded-3 bg-light border border-dashed text-secondary small" style="font-size: 0.82rem;">
                                <?php if ($status_tarik == 'pending') : ?>
                                    <div class="d-flex align-items-center gap-2 text-warning fw-bold">
                                        <i class="bi bi-hourglass-split fs-5"></i>
                                        <span>Menunggu verifikasi berkas saldo oleh Bendahara Koperasi.</span>
                                    </div>
                                <?php elseif ($status_tarik == 'disetujui') : ?>
                                    <div class="d-flex align-items-center gap-2 text-primary fw-bold">
                                        <i class="bi bi-shield-check fs-5"></i>
                                        <span>Dana Anda sedang disiapkan untuk dicairkan oleh kasir keuangan BPS.</span>
                                    </div>
                                <?php elseif ($status_tarik == 'ditransfer') : ?>
                                    <div class="d-flex align-items-center gap-2 text-success fw-bold">
                                        <i class="bi bi-check-circle-fill fs-5"></i>
                                        <span>Dana sukses dicairkan! Saldo simpanan sukarela Anda resmi terpotong.</span>
                                    </div>
                                <?php else : ?>
                                    <div class="d-flex align-items-start gap-2 text-danger fw-bold">
                                        <i class="bi bi-exclamation-triangle-fill fs-5 mt-0.5"></i>
                                        <div>
                                            <span>Penarikan Ditolak Pengurus!</span>
                                            <div class="text-muted fw-normal mt-0.5" style="font-size: 0.75rem;">Alasan: <em class="text-dark">"<?= esc($latest_tarik['alasan_penolakan'] ?? 'Syarat tidak memenuhi ketentuan.') ?>"</em></div>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>

                        </div>
                    <?php else : ?>
                        <div class="text-center py-5 bg-white border border-dashed rounded-4 text-muted small w-100">
                            <i class="bi bi-inbox opacity-40 mb-2" style="font-size: 2rem;"></i>
                            <p class="mb-0 italic">Anda tidak memiliki permohonan penarikan dana aktif yang sedang diproses.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

    </div>

<?= $this->endSection() ?>