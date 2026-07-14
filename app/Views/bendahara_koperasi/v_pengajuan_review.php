<?= $this->extend('layout/main'); ?>
<?= $this->section('content'); ?>

<style>
    .review-premium-card { border: none; border-radius: 20px; background: #ffffff; border: 1px solid #e2e8f0; }
    .review-side-panel { border: none; border-radius: 20px; background-color: #0b1a30; color: #ffffff; box-shadow: 0 10px 25px rgba(11, 26, 48, 0.15); }
    .table-premium-review { border: 1px solid #e2e8f0; border-radius: 14px; overflow: hidden; background: #ffffff; }
    .table-premium-review thead th { background-color: #f8fafc; color: #64748b; font-weight: 600; font-size: 0.75rem; padding: 14px 20px; border-bottom: 1px solid #e2e8f0; }
    .table-premium-review tbody td { padding: 14px 20px; border-bottom: 1px solid #f1f5f9; font-size: 0.88rem; color: #334155; }
    .form-select-dark { border-radius: 12px; border: 1px solid #1e293b; padding: 12px; font-size: 0.9rem; background-color: #0f172a; color: #ffffff; }
    .form-select-dark:focus { border-color: #0066ff; background-color: #0f172a; color: #ffffff; }
    .btn-saw-trigger { background: linear-gradient(135deg, #0066ff 0%, #0052cc 100%); border: none; color: #ffffff; padding: 14px; font-weight: 700; border-radius: 14px; font-size: 0.9rem; box-shadow: 0 4px 14px rgba(0, 102, 255, 0.3); }
</style>

<div class="container-fluid py-4 p-0">
    <div class="row g-4">
        
        <div class="col-lg-8">
            <div class="card review-premium-card shadow-sm mb-4">
                <div class="card-body p-4 p-md-5">
                    
                    <div class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-4">
                        <div>
                            <span class="text-uppercase tracking-widest text-primary fw-bold" style="font-size: 0.72rem;">Review Dokumen Administrasi</span>
                            <h4 class="fw-bold text-dark mb-0 mt-1">Detail Pengajuan: <?= esc($p['nama_anggota'] ?? 'Anggota Koperasi') ?></h4>
                        </div>
                        <span class="badge bg-light text-secondary border px-3 py-2 fw-semibold rounded-pill">ID: #PJN-<?= $p['id_pengajuan'] ?></span>
                    </div>

                    <div class="row g-3 bg-light p-3 rounded-4 mb-4 border border-light-subtle m-0">
                        <div class="col-md-6 border-end border-2">
                            <small class="text-muted text-uppercase fw-semibold tracking-wider" style="font-size: 0.68rem;">Nominal Dana yang Diajukan</small>
                            <h3 class="fw-bold text-primary mb-0 mt-1">Rp <?= number_format($p['jumlah_diajukan'], 0, ',', '.') ?></h3>
                        </div>
                        <div class="col-md-6 ps-md-4 d-flex flex-column justify-content-center">
                            <small class="text-muted text-uppercase fw-semibold tracking-wider" style="font-size: 0.68rem;">Rekening Bank Tujuan Transfer</small>
                            <h6 class="fw-bold text-dark mb-0 mt-1"><i class="bi bi-bank me-2 text-secondary"></i><?= esc($p['nama_bank'] ?? 'Belum Diisi') ?> — <span class="text-primary"><?= esc($p['nomor_rekening'] ?? 'Belum Diisi') ?></span></h6>
                        </div>
                    </div>

                    <div class="d-flex align-items-center gap-2 mb-2 mt-4 text-dark">
                        <i class="bi bi-cart-check-fill text-primary fs-5"></i>
                        <h6 class="fw-bold mb-0">Rincian Anggaran Kebutuhan Dana (RHB)</h6>
                    </div>
                    
                    <div class="table-premium-review shadow-sm mb-4 p-3 bg-white">
                        <div class="mb-3">
                            <label class="fw-semibold text-secondary small">Judul Rincian Anggaran:</label>
                            <div class="p-2.5 bg-light border border-dashed rounded-3 fw-bold text-dark"><?= esc($judul); ?></div>
                        </div>

                        <div class="table-responsive">
                            <table class="table align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th width="60">No</th>
                                        <th>Kebutuhan Anggaran Belanja</th>
                                        <th class="text-center">Harga Satuan</th>
                                        <th class="text-center" width="90">QTY</th>
                                        <th class="text-end">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($rhb_list)) : 
                                        $no = 1;
                                        foreach ($rhb_list as $item) : ?>
                                        <tr>
                                            <td class="text-center"><?= $no++ ?></td>
                                            <td class="fw-semibold text-dark"><?= esc($item['kebutuhan'] ?? '-') ?></td>
                                            <td class="text-center">Rp <?= number_format($item['harga_satuan'] ?? 0, 0, ',', '.') ?></td>
                                            <td class="text-center"><span class="badge bg-light text-dark border px-2.5 py-1.5"><?= esc($item['qty'] ?? 0) ?></span></td>
                                            <td class="text-end fw-bold text-secondary">Rp <?= number_format($item['subtotal'] ?? 0, 0, ',', '.') ?></td>
                                        </tr>
                                    <?php endforeach; else : ?>
                                        <tr><td colspan="5" class="text-center py-4 text-muted small">Data lampiran rincian anggaran kosong.</td></tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="d-flex align-items-center gap-2 mb-3 mt-4 text-dark">
                        <i class="bi bi-file-earmark-lock2-fill text-primary fs-5"></i>
                        <h6 class="fw-bold mb-0">Berkas Validasi Legalitas Administrasi </h6>
                    </div>
                    <div class="p-3 border rounded-3 d-flex align-items-center justify-content-between bg-white">
                        <div class="d-flex align-items-center gap-3">
                            <div class="bg-info bg-opacity-10 text-info p-2 rounded-3"><i class="bi bi-file-earmark-pdf-fill fs-4"></i></div>
                            <div>
                                <h6 class="small fw-bold text-dark mb-0">Surat Izin Pasangan / Bukti Belum Menikah</h6>
                                <small class="text-muted" style="font-size: 0.72rem;">Periksa keabsahan berkas jika nominal pengajuan diatas Rp 25.000.000</small>
                            </div>
                        </div>
                        <?php if (!empty($p['bukti_c1'])) : ?>
                            <a href="<?= base_url('uploads/bukti_c1/'.$p['bukti_c1']) ?>" target="_blank" class="btn btn-sm btn-light border rounded-pill px-3 fw-bold text-primary">
                                <i class="bi bi-eye-fill me-1"></i> Lihat Berkas Dokumen
                            </a>
                        <?php else : ?>
                            <span class="badge bg-danger-subtle text-danger px-3 py-1.5 rounded-pill fw-bold">Berkas Kosong</span>
                        <?php endif; ?>
                    </div>

                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card review-side-panel shadow h-100">
                <div class="card-body p-4 p-md-5 d-flex flex-column justify-content-between">
                    <div>
                        <div class="d-flex align-items-center gap-2 mb-2 text-white">
                            <i class="bi bi-pencil-square text-warning fs-5"></i>
                            <h6 class="fw-bold mb-0">Panel Penilaian Bendahara</h6>
                        </div>
                        
                        <form action="<?= base_url('bendahara/pengajuan/simpan_review') ?>" method="post">
                            <?= csrf_field() ?>
                            <input type="hidden" name="id_pengajuan" value="<?= $p['id_pengajuan'] ?>">
                            
                            <div class="mb-4">
                                <label class="form-label text-white-50 small fw-bold">Validasi Berkas Pasangan/KTP</label>
                                <select name="skor_c1" class="form-select form-select-dark mt-2" required>
                                    <option value="" disabled selected>-- Pilih Validitas--</option>
                                    <option value="1">1 Berkas sesuai</option>
                                    <option value="0">0 Berkas Tidak sesuai</option>
                                </select>
                            </div>

                            <div class="mb-4">
                                <label class="form-label text-white-50 small fw-bold">Tentukan Skor Kualitas RHB</label>
                                <select name="skor_c2" class="form-select form-select-dark mt-2" required>
                                    <option value="" disabled selected>Beri Nilai RHB</option>
                                    <option value="5">5 Sangat Baik </option>
                                    <option value="4">4 Baik </option>
                                    <option value="3">3 Cukup </option>
                                    <option value="2">2 Kurang </option>
                                    <option value="1">1 Tidak Ada / Tidak Rasional</option>
                                </select>
                            </div>
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary btn-saw-trigger w-100 py-3">
                            <i class="bi bi-cpu-fill me-2"></i>ANALISI PENGAJUAN
                        </button>
                        </form>
                    </div>

                </div>
            </div>
        </div>

    </div>
</div>

<?= $this->endSection(); ?>