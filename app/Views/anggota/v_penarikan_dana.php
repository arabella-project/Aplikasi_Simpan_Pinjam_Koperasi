<?= $this->extend('layout/v_anggota_layout') ?>
<?= $this->section('content') ?>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
    .tracking-tight { letter-spacing: -0.5px; }
    .balance-card { background: linear-gradient(135deg, #0066ff 0%, #0044cc 100%); border-radius: 16px; color: #ffffff; border: none; }
    .form-premium-card { border: none; border-radius: 20px; background: #ffffff; border: 1px solid #e2e8f0; }
    .form-control-premium { border-radius: 12px; border: 1px solid #cbd5e1; padding: 12px 16px; font-size: 1rem; color: #334155; transition: all 0.2s ease; }
    .form-control-premium:focus { border-color: #0066ff; box-shadow: 0 0 0 4px rgba(0, 102, 255, 0.08); outline: none; }
    .btn-premium-submit { background: linear-gradient(135deg, #0066ff 0%, #0052cc 100%); border: none; color: #ffffff; padding: 14px; font-weight: 700; border-radius: 14px; transition: all 0.25s ease; width: 100%; font-size: 0.95rem; }
    .btn-premium-submit:hover { transform: translateY(-1px); box-shadow: 0 6px 20px rgba(0, 102, 255, 0.35); }
    .table-premium-header th { font-weight: 800 !important; color: #0b1a30 !important; text-transform: uppercase; text-align: center; background-color: #f8fafc !important; border-bottom: 2px solid #0b1a30 !important; padding: 16px 12px; font-size: 0.8rem; letter-spacing: 0.5px; }
    .table-premium-body td { border-bottom: 1px solid #e2e8f0; padding: 14px 12px; vertical-align: middle; text-align: center; font-size: 0.88rem; color: #334155; }
</style>

<div class="container-fluid py-4 px-4">
    <div class="mb-4 border-bottom pb-3" style="border-color: #f1f5f9 !important;">
        <span class="text-uppercase tracking-widest text-primary fw-bold" style="font-size: 0.72rem; letter-spacing: 0.5px;">Otoritas Dana Mandiri</span>
        <h4 class="fw-bold text-dark tracking-tight mb-0 mt-1">Penarikan Dana Simpanan Anggota</h4>
        <p class="text-muted small mb-0">Ajukan permohonan pencairan dana simpanan Anda langsung ke rekening bank tujuan.</p>
    </div>

    <?php if (session()->getFlashdata('success')) : ?>
        <div class="alert alert-success border-0 shadow-sm mb-4 small rounded-3 bg-success bg-opacity-10 text-success p-3">
            <i class="bi bi-check-circle-fill me-2"></i> <?= session()->getFlashdata('success') ?>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')) : ?>
        <div class="alert alert-danger border-0 shadow-sm mb-4 small rounded-3 bg-danger bg-opacity-10 text-danger p-3">
            <i class="bi bi-exclamation-triangle-fill me-2"></i> <?= session()->getFlashdata('error') ?>
        </div>
    <?php endif; ?>

    <div class="row g-4 mb-5">
        <div class="col-lg-4">
            <div class="card balance-card shadow-sm p-4 h-100 d-flex flex-column justify-content-center">
                <small class="opacity-75 text-uppercase fw-bold tracking-wider" style="font-size: 0.7rem;">Total Saldo Pencairan Bersih</small>
                <h2 class="fw-bold my-2 tracking-tight">Rp <?= number_format($saldo_sukarela, 0, ',', '.') ?></h2>
                <hr style="border-top: 1px solid rgba(255,255,255,0.2); margin: 15px 0;">
                <div class="opacity-80 small">
                    <i class="bi bi-shield-lock-fill me-1.5"></i> Saldo bersih aman dari penalti sanksi kredit macet koperasi.
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card form-premium-card shadow-sm h-100">
                <div class="card-body p-4">
                    
                    <?php if ($is_macet) : ?>
                        <div class="alert alert-danger border-0 small rounded-3 p-3 mb-4 d-flex align-items-start gap-2.5" style="background-color: #fef2f2; color: #991b1b;">
                            <i class="bi bi-exclamation-octagon-fill fs-5 mt-0.5"></i>
                            <div>
                                <strong>Sistem Proteksi Kredit Macet Aktif!</strong> Anda menunggak cicilan pinjaman berjalan (> 2 Bulan). Dana 30% dari total simpanan Anda sebesar <strong>Rp <?= number_format($dana_ditahan, 0, ',', '.') ?></strong> dikunci sementara.
                            </div>
                        </div>
                    <?php endif; ?>

                    <form action="<?= base_url('anggota/penarikan/simpan') ?>" method="post" id="formPenarikanDana">
                        <?= csrf_field() ?>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-dark small mb-1">Bank Tujuan Transfer</label>
                                <input type="text" id="bankTujuan" name="bank_tujuan" class="form-control form-control-premium" placeholder="Contoh: BRI, BNI, Mandiri" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-dark small mb-1">Nomor Rekening Anda</label>
                                <input type="text" id="noRekening" name="no_rekening" class="form-control form-control-premium" placeholder="Masukkan nomor rekening..." required>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold text-dark small mb-1">Nominal yang Ingin Dicairkan (Rp)</label>
                            <input type="number" id="jumlahDitarik" name="jumlah_ditarik" min="50000" max="<?= $saldo_sukarela ?>" class="form-control form-control-premium fw-bold text-primary fs-5" placeholder="Masukkan nominal..." required>
                            <div class="form-text text-muted mt-2">
                                Batas maksimal penarikan dana Anda saat ini: <span class="fw-bold text-dark">Rp <?= number_format($saldo_sukarela, 0, ',', '.') ?></span>
                            </div>
                        </div>

                        <?php if ($saldo_sukarela < 50000) : ?>
                            <button type="button" class="btn btn-secondary py-3 w-100 fw-bold" style="border-radius: 14px;" disabled>
                                <i class="bi bi-lock-fill me-2"></i> SALDO TIDAK MENCUKUPI
                            </button>
                        <?php else : ?>
                            <button type="button" id="btnSubmitPenarikan" class="btn btn-premium-submit py-3">
                                <i class="bi bi-wallet2 me-2"></i> KIRIM PERMOHONAN PENARIKAN DANA
                            </button>
                        <?php endif; ?>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="card form-premium-card shadow-sm rounded-4 overflow-hidden">
        <div class="card-header bg-white border-0 py-3 px-4">
            <h5 class="fw-bold mb-0 text-dark tracking-tight"><i class="bi bi-clock-history me-2 text-primary"></i>Log Riwayat Permohonan Penarikan</h5>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-premium-header">
                    <tr>
                        <th width="60">No</th>
                        <th>Waktu Pengajuan</th>
                        <th>Tujuan Transfer</th>
                        <th class="text-end">Nominal Penarikan</th>
                        <th>Status Otoritas</th>
                    </tr>
                </thead>
                <tbody class="table-premium-body">
                    <?php if(!empty($histori)) : $no=1; foreach($histori as $row) : ?>
                        <tr>
                            <td class="text-secondary fw-semibold"><?= $no++ ?></td>
                            <td>
                                <span class="fw-bold text-dark"><?= date('d M Y', strtotime($row['tgl_pengajuan'])) ?></span>
                                <small class="text-muted d-block" style="font-size: 0.75rem;"><?= date('H:i', strtotime($row['tgl_pengajuan'])) ?> WIB</small>
                            </td>
                            <td>
                                <div class="fw-bold text-dark"><?= esc($row['bank_tujuan']) ?></div>
                                <small class="text-muted">Rek: <?= esc($row['no_rekening']) ?></small>
                            </td>
                            <td class="text-end fw-bold text-primary fs-6">
                                Rp <?= number_format($row['jumlah_ditarik'], 0, ',', '.') ?>
                            </td>
                            <td>
                                <?php if($row['status_penarikan'] == 'pending') : ?>
                                    <span class="badge bg-warning text-dark rounded-pill px-3 py-1.5 fw-bold" style="font-size: 0.72rem;">Menunggu Validasi</span>
                                <?php elseif($row['status_penarikan'] == 'disetujui') : ?>
                                    <span class="badge bg-info text-white rounded-pill px-3 py-1.5 fw-bold" style="font-size: 0.72rem;">Approved Pengurus</span>
                                <?php elseif($row['status_penarikan'] == 'ditransfer') : ?>
                                    <span class="badge bg-success text-white rounded-pill px-3 py-1.5 fw-bold" style="font-size: 0.72rem;">Dana Ditransfer</span>
                                <?php else : ?>
                                    <span class="badge bg-danger text-white rounded-pill px-3 py-1.5 fw-bold" style="font-size: 0.72rem;">Berkas Ditolak</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; else : ?>
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">Belum ada rekam data riwayat penarikan dana.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // 1. Logika Interseptor SweetAlert2 untuk Validasi Form Penarikan Dana Anggota
        const btnSubmit = document.getElementById('btnSubmitPenarikan');
        if (btnSubmit) {
            btnSubmit.addEventListener('click', function (e) {
                e.preventDefault();
                
                const form = document.getElementById('formPenarikanDana');
                const bank = document.getElementById('bankTujuan').value.trim();
                const rekening = document.getElementById('noRekening').value.trim();
                const nominal = document.getElementById('jumlahDitarik').value;
                const maxNominal = parseInt(document.getElementById('jumlahDitarik').getAttribute('max'));

                // Validasi Client-Side Dasar agar Modul Tidak Kosong
                if (bank === '' || rekening === '' || nominal === '') {
                    Swal.fire({
                        title: 'Data Tidak Lengkap!',
                        text: 'Mohon lengkapi seluruh field instruksi bank, nomor rekening, dan nominal pencairan.',
                        icon: 'warning',
                        confirmButtonColor: '#0066ff',
                        customClass: { popup: 'rounded-4' }
                    });
                    return;
                }

                if (parseInt(nominal) < 50000 || parseInt(nominal) > maxNominal) {
                    Swal.fire({
                        title: 'Nominal Tidak Valid!',
                        text: `Minimal penarikan Rp 50.000 dan maksimal sebesar Rp ${maxNominal.toLocaleString('id-ID')}`,
                        icon: 'error',
                        confirmButtonColor: '#0066ff',
                        customClass: { popup: 'rounded-4' }
                    });
                    return;
                }

                // Format Tampilan Mata Uang untuk Modal Konfirmasi Penarikan Dana
                const formattedNominal = parseInt(nominal).toLocaleString('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 });

                // Render SweetAlert2 Premium untuk Konfirmasi Pengajuan Dana Anggota
                Swal.fire({
                    title: 'Konfirmasi Penarikan Dana',
                    html: `
                        <div class="text-start bg-light p-3 rounded-3 small border mb-1">
                            <div class="mb-2"><strong>Bank Tujuan:</strong> <span class="text-primary">${bank.toUpperCase()}</span></div>
                            <div class="mb-2"><strong>No. Rekening:</strong> <span class="text-dark font-monospace">${rekening}</span></div>
                            <div><strong>Total Pencairan:</strong> <span class="text-success fw-bold">${formattedNominal}</span></div>
                        </div>
                        <p class="text-muted small mt-3 mb-0 text-center">Pastikan data rekening Anda sudah valid sebelum mengirim permohonan dana.</p>
                    `,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#0066ff', /* Biru Premium FinTech */
                    cancelButtonColor: '#94a3b8',
                    confirmButtonText: 'Ya, Kirim Sekarang',
                    cancelButtonText: 'Periksa Kembali',
                    customClass: { popup: 'rounded-4' }
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit(); // Trigger Eksekusi Submit Form Resmi ke Backend
                    }
                });
            });
        }

        // 2. Logika Validasi Konfirmasi Review dari Sisi Pengurus/Bendahara (Tetap Berjalan Normal)
        const approveButtons = document.querySelectorAll('.btn-approve-trigger');
        approveButtons.forEach(button => {
            button.addEventListener('click', function (e) {
                e.preventDefault();
                const targetUrl = this.getAttribute('data-url');
                
                Swal.fire({
                    title: 'Setujui Permohonan?',
                    text: "Apakah Anda yakin data kelayakan sudah sesuai dan menyetujui permohonan dana ini?",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#0066ff',
                    cancelButtonColor: '#94a3b8',
                    confirmButtonText: 'Ya, Setujui',
                    cancelButtonText: 'Batal',
                    customClass: { popup: 'rounded-4' }
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = targetUrl;
                    }
                });
            });
        });
    });
</script>

<?= $this->endSection() ?>