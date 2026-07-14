<?= $this->extend('layout/v_anggota_layout') ?>
<?= $this->section('content') ?>

<style>
    /* Premium FinTek Component Styles */
    .loan-summary-card { border: none; border-radius: 20px; background: #ffffff; border: 1px solid #e2e8f0; }
    .loan-balance-box { background: linear-gradient(135deg, #fffefe 0%, #fff5f5 100%); border: 1px dashed #fca5a5; border-radius: 16px; padding: 24px; }
    
    /* Table Premium Alignment */
    .pinjaman-card-frame { border: none; border-radius: 20px; background: #ffffff; border: 1px solid #e2e8f0; overflow: hidden; }
    .table-premium-pinjaman thead th { background-color: #f8fafc; color: #64748b; font-weight: 600; font-size: 0.75rem; letter-spacing: 0.5px; padding: 16px 24px; border-bottom: 1px solid #e2e8f0; }
    .table-premium-pinjaman tbody td { padding: 16px 24px; border-bottom: 1px solid #f1f5f9; font-size: 0.88rem; color: #334155; }
    .table-premium-pinjaman tbody tr:last-child td { border-bottom: none; }
    .table-premium-pinjaman tbody tr:hover { background-color: #f8fafc; }

    /* Custom List Row spacing */
    .detail-row { display: flex; justify-content: space-between; align-items: center; padding: 10px 0; border-bottom: 1px solid #f1f5f9; font-size: 0.88rem; }
    .detail-row:last-child { border-bottom: none; padding-bottom: 0; }
    
    /* Tambahan pemisah multi kontrak */
    .dashed-divider { border-bottom: 2px dashed #cbd5e1; margin-bottom: 35px; padding-bottom: 35px; }
</style>

<div class="container-fluid p-0">
    <div class="mb-4">
        <h4 class="fw-bold text-dark mb-1">Informasi Pinjaman Pribadi</h4>
        <p class="text-muted small mb-0">ID Anggota: <span class="fw-bold text-primary">#BPS-<?= session()->get('id_anggota') ?></span></p>
    </div>

    <?php if (!empty($list_aktif)) : ?>
        
        <?php foreach ($list_aktif as $index => $aktif) : 
            // 🟢 MATEMATIKA INTEGRITAS: Menghitung sisa tenor cicilan yang belum dibayar secara akurat
            $total_tenor = (int)($aktif['jumlah_potongan'] ?? 0);
            $sudah_bayar = (int)($aktif['angsuran_ke'] ?? 0);
            $sisa_tenor  = max(0, $total_tenor - $sudah_bayar);
        ?>
            
            <div class="row g-4 m-0 <?= ($index < count($list_aktif) - 1) ? 'dashed-divider' : 'mb-4' ?>">
                
                <div class="col-lg-5 ps-0 pe-lg-2">
                    <div class="card loan-summary-card shadow-sm h-100 p-4">
                        <div class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-4">
                            <h6 class="fw-bold mb-0 text-dark">Status Beban Kredit</h6>
                            <span class="badge rounded-pill bg-success-subtle text-success border border-success-subtle px-3 py-1.5 fw-bold" style="font-size: 0.7rem;">
                                <i class="bi bi-shield-fill-check me-1"></i>KREDIT AKTIF
                            </span>
                        </div>
                        
                        <div class="loan-balance-box text-center mb-4">
                            <small class="text-muted text-uppercase fw-semibold tracking-wider" style="font-size: 0.7rem;">Sisa Kewajiban Hutang</small>
                            <h2 class="fw-bold text-danger my-1" style="letter-spacing: -0.5px;">Rp <?= number_format($aktif['sisa_hutang'], 0, ',', '.') ?></h2>
                            <span class="text-secondary small d-block opacity-75">ID Kontrak: #PJN-<?= $aktif['id_pinjaman'] ?></span>
                        </div>

                        <div class="px-1">
                            <div class="detail-row">
                                <span class="text-secondary">Angsuran Pokok / Bln</span>
                                <span class="fw-bold text-dark">Rp <?= number_format($aktif['angsuran_perbulan'], 0, ',', '.') ?></span>
                            </div>
                            <div class="detail-row">
                                <span class="text-secondary">Jasa Bunga Koperasi / Bln</span>
                                <span class="fw-bold text-dark">Rp <?= number_format($aktif['jasa_perbulan'], 0, ',', '.') ?></span>
                            </div>
                            <div class="detail-row py-3 my-1 border-top border-bottom bg-light px-2 rounded-3">
                                <span class="text-dark fw-bold">Total Tagihan Potong Gaji</span>
                                <span class="fw-bold text-primary" style="font-size: 1rem;">Rp <?= number_format(($aktif['angsuran_perbulan'] + $aktif['jasa_perbulan']), 0, ',', '.') ?></span>
                            </div>
                            <div class="detail-row">
                                <span class="text-secondary">Jangka Waktu Kontak</span>
                                <span class="fw-semibold text-dark"><?= $total_tenor ?> Bulan</span>
                            </div>
                            <div class="detail-row">
                                <span class="text-secondary">Progress Pembayaran</span>
                                <span class="badge rounded-pill bg-primary-subtle text-primary fw-bold px-2.5 py-1">Cicilan Ke-<?= $sudah_bayar ?></span>
                            </div>
                            <div class="detail-row">
                                <span class="text-secondary">Sisa Masa Tenor</span>
                                <span class="fw-bold text-warning"><?= $sisa_tenor ?> Bulan Tersisa</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-7 pe-0 ps-lg-2">
                    <div class="card pinjaman-card-frame shadow-sm h-100">
                        <div class="p-4 border-bottom bg-white d-flex justify-content-between align-items-center">
                            <h6 class="fw-bold text-dark mb-0"><i class="bi bi-clock-history me-2 text-primary"></i>Riwayat Pembayaran Cicilan</h6>
                            <span class="badge bg-light text-secondary border px-2.5 py-1.5 small fw-semibold">ID Pinjaman: #<?= $aktif['id_pinjaman'] ?></span>
                        </div>
                        <div class="table-responsive" style="max-height: 380px;">
                            <table class="table table-premium-pinjaman align-middle mb-0">
                                <thead>
                                    <tr class="text-uppercase">
                                        <th>Tanggal Bayar</th>
                                        <th class="text-center">Angsuran Ke</th>
                                        <th class="text-end">Nominal Setoran</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($aktif['histori_angsuran'])) : foreach ($aktif['histori_angsuran'] as $h) : ?>
                                        <tr>
                                            <td class="fw-semibold text-dark">
                                                <?= date('d M Y', strtotime($h['tgl_bayar'])) ?>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge rounded-pill bg-light text-dark border px-3 py-1.5 fw-medium">Ke-<?= $h['angsuran_ke'] ?></span>
                                            </td>
                                            <td class="text-end fw-bold text-success">
                                                Rp <?= number_format($h['jumlah_bayar'], 0, ',', '.') ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; else : ?>
                                        <tr>
                                            <td colspan="3" class="text-center py-5 text-muted small italic">
                                                <i class="bi bi-wallet2 d-block fs-3 mb-2 text-secondary"></i>
                                                Belum ada data pembayaran cicilan yang terekam untuk kontrak pinjaman ini.
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div> 
        <?php endforeach; ?>

    <?php else : ?>
        <div class="row g-4 m-0">
            <div class="col-12 p-0">
                <div class="card shadow-sm bg-white text-center py-5 rounded-4 border-0" style="border: 1px solid #e2e8f0 !important;">
                    <div class="card-body py-4">
                        <div class="bg-success bg-opacity-10 text-success d-inline-flex align-items-center justify-content-center rounded-circle mb-3" style="width: 70px; height: 70px;">
                            <i class="bi bi-patch-check-fill" style="font-size: 3rem;"></i>
                        </div>
                        <h5 class="fw-bold text-dark">Tidak Ada Pinjaman Aktif</h5>
                        <p class="text-muted mx-auto mb-0 small" style="max-width: 440px; line-height: 1.5;">
                            Luar biasa! Saat ini Anda bersih dari segala beban cicilan pinjaman koperasi. Jika membutuhkan pendanaan darurat, Anda dapat melakukan pengisian kriteria pengajuan melalui menu Dashboard utama.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

</div>

<?= $this->endSection() ?>