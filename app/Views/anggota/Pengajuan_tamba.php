<?= $this->extend('layout/v_anggota_layout') ?>
<?= $this->section('content'); ?>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
    /* Premium FinTech UI Style Optimization */
    .form-premium-card { border: none; border-radius: 24px; background: #ffffff; border: 1px solid #e2e8f0; box-shadow: 0 10px 30px rgba(0,0,0,0.02); }
    .form-control-premium, .form-select-premium { border-radius: 12px; border: 1px solid #cbd5e1; padding: 14px 18px; font-size: 0.92rem; color: #334155; transition: all 0.2s ease; background-color: #f8fafc; }
    .form-control-premium:focus, .form-select-premium:focus { border-color: #0066ff; box-shadow: 0 0 0 4px rgba(0, 102, 255, 0.08); background-color: #ffffff; outline: none; }
    
    /* Wizard Step Progress Header Styles */
    .step-wizard-wrapper { display: flex; justify-content: space-between; position: relative; margin-bottom: 40px; background: #f8fafc; padding: 20px; border-radius: 16px; border: 1px solid #e2e8f0; }
    .step-wizard-line { position: absolute; top: 50%; left: 10%; right: 10%; height: 4px; background: #e2e8f0; transform: translateY(-50%); z-index: 1; }
    .step-wizard-progress-line { position: absolute; top: 50%; left: 10%; height: 4px; background: #0066ff; transform: translateY(-50%); z-index: 2; transition: width 0.4s ease; width: 0%; }
    .wizard-step-node { position: relative; z-index: 3; display: flex; flex-direction: column; align-items: center; width: 100px; }
    .wizard-icon { width: 40px; height: 40px; border-radius: 50%; background: #e2e8f0; color: #64748b; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 0.95rem; transition: all 0.3s ease; border: 3px solid #ffffff; }
    
    /* Active & Completed Status Nodes */
    .wizard-step-node.active .wizard-icon { background: #0066ff; color: #ffffff; box-shadow: 0 0 0 4px rgba(0, 102, 255, 0.15); }
    .wizard-step-node.active .wizard-label { color: #0066ff; font-weight: 700; }
    .wizard-step-node.completed .wizard-icon { background: #10b981; color: #ffffff; }
    .wizard-step-node.completed .wizard-label { color: #10b981; font-weight: 600; }
    .wizard-label { font-size: 0.75rem; margin-top: 8px; color: #64748b; font-weight: 500; text-transform: uppercase; letter-spacing: 0.5px; transition: color 0.3s; }

    /* Desain Grid Tabel Dinamis RHB */
    .table-rhb thead th { background-color: #f1f5f9; color: #475569; font-weight: 700; font-size: 0.78rem; padding: 14px; border-bottom: 2px solid #cbd5e1; text-align: center; }
    .form-control-rhb { border-radius: 8px; border: 1px solid #cbd5e1; padding: 10px 14px; font-size: 0.88rem; }
    
    /* Wizard Button Actions */
    .btn-wizard-action { border-radius: 12px; padding: 12px 28px; font-weight: 700; font-size: 0.9rem; transition: all 0.2s; }
    .btn-premium-submit { background: linear-gradient(135deg, #10b981 0%, #059669 100%); border: none; color: #ffffff; box-shadow: 0 4px 14px rgba(16, 185, 129, 0.3); }
    .btn-premium-submit:hover { transform: translateY(-1px); box-shadow: 0 6px 20px rgba(16, 185, 129, 0.45); }
</style>

<div class="container-fluid py-4 px-4">
    <div class="mb-4">
        <span class="text-uppercase tracking-widest text-primary fw-bold" style="font-size: 0.72rem; letter-spacing: 0.5px;">Akses Permohonan Pinjaman</span>
        <h4 class="fw-bold text-dark tracking-tight mb-1">Formulir Pengajuan Pinjaman Baru</h4>
    </div>

    <?php if (session()->getFlashdata('error')) : ?>
        <div class="alert alert-danger border-0 mb-4 small rounded-3 bg-danger bg-opacity-10 text-danger p-3">
            <i class="bi bi-exclamation-triangle-fill me-2"></i> <?= session()->getFlashdata('error') ?>
        </div>
    <?php endif; ?>

    <?php if ($boleh_pinjam === false) : ?>
        <div class="card border-0 p-5 rounded-4 text-center border border-danger-subtle shadow-sm" style="background-color: #fff5f5;">
            <i class="bi bi-shield-lock-fill text-danger mb-3" style="font-size: 4rem;"></i>
            <h5 class="fw-bold text-dark">Akses Pengajuan Terkunci Otomatis</h5>
            <p class="small text-secondary mx-auto mt-2 mb-0" style="max-width: 550px; line-height: 1.6;"><?= $pesan_blokir ?></p>
            <div class="mt-4">
                <a href="<?= base_url('anggota/dashbord') ?>" class="btn btn-sm btn-outline-secondary px-4 rounded-pill fw-bold">Kembali ke Dashboard</a>
            </div>
        </div>
    <?php else : ?>

    <div class="card form-premium-card shadow-sm mb-4">
        <div class="card-body p-4 p-md-5">
            
            <div class="step-wizard-wrapper">
                <div class="step-wizard-line"></div>
                <div class="step-wizard-progress-line" id="wizardProgressLine"></div>
                
                <div class="wizard-step-node active" id="nodeStep1">
                    <div class="wizard-icon">1</div>
                    <div class="wizard-label">RHB</div>
                </div>
                <div class="wizard-step-node" id="nodeStep2">
                    <div class="wizard-icon">2</div>
                    <div class="wizard-label">Kriteria SAW</div>
                </div>
                <div class="wizard-step-node" id="nodeStep3">
                    <div class="wizard-icon">3</div>
                    <div class="wizard-label">Rekening Pencairan</div>
                </div>
            </div>

            <form action="<?= base_url('anggota/pengajuan/simpan') ?>" method="post" enctype="multipart/form-data" id="loanWizardForm">
                <?= csrf_field() ?>
                
                <!-- TAB 1: AREA RHB -->
                <div class="wizard-form-page-tab" id="formPage1">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-dark small mb-1">Nominal yang Diajukan (Rp) <span class="text-danger">*Maksimal Rp 80.000.000</span></label>
                            <!-- 🟢 MODIFIKASI: Type diganti text untuk mendukung pemisah ribuan titik -->
                            <input type="text" id="jumlah_diajukan_mask" class="form-control form-control-premium fw-bold text-primary fs-5 rupiah-mask" placeholder="Masukkan nominal dana..." required>
                            <input type="hidden" id="jumlah_diajukan" name="jumlah_diajukan">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-dark small mb-1">Judul Rincian Anggaran Kebutuhan Belanja</label>
                            <input type="text" name="judul_rhb" class="form-control form-control-premium" placeholder="Contoh: Renovasi Rumah" required>
                        </div>
                        <div class="col-12">
                            <div class="table-responsive mt-2">
                                <table class="table table-rhb align-middle mb-3" id="tableAnggaranBelanja">
                                    <thead>
                                        <tr>
                                            <th width="50">No</th>
                                            <th>Kebutuhan Alokasi Belanja</th>
                                            <th width="180">Harga Satuan (Rp)</th>
                                            <th width="110">QTY</th>
                                            <th width="180">Subtotal (Rp)</th>
                                            <th width="50">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="text-center fw-bold text-secondary">1</td>
                                            <td><input type="text" name="rhb[kebutuhan][]" class="form-control form-control-rhb" placeholder="Nama barang / keperluan..." required></td>
                                            <!-- 🟢 MODIFIKASI: Input harga dan subtotal menggunakan mask titik -->
                                            <td>
                                                <input type="text" class="form-control form-control-rhb rupiah-mask harga-mask fw-semibold" oninput="calculateRow(this)" placeholder="0" required>
                                                <input type="hidden" name="rhb[harga][]" class="harga-raw">
                                            </td>
                                            <td><input type="number" name="rhb[qty][]" class="form-control form-control-rhb qty text-center" oninput="calculateRow(this)" value="1" min="1" required></td>
                                            <td>
                                                <input type="text" class="form-control form-control-rhb subtotal-mask fw-bold text-secondary" readonly placeholder="0">
                                                <input type="hidden" name="rhb[subtotal][]" class="subtotal-raw">
                                            </td>
                                            <td class="text-center"><span class="text-muted small">-</span></td>
                                        </tr>
                                    </tbody>
                                </table>
                                <button type="button" class="btn btn-sm btn-outline-primary px-3 rounded-pill fw-bold" onclick="addRow()"><i class="bi bi-plus-circle me-1"></i> Tambah Baris Anggaran</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- TAB 2: KRITERIA SAW & TENOR BARU -->
                <div class="wizard-form-page-tab d-none" id="formPage2">
                    <div class="row g-4">
                        <!-- 🟢 MODIFIKASI: INPUT JANGKA WAKTU (TENOR) DIUBAH MENJADI PILIHAN KELIPATAN 6 BULAN -->
                        <div class="col-md-4">
                            <label class="form-label fw-bold text-dark small mb-1">Tenor Angsuran (Bulan) <span class="text-danger">*Maks 24 Bulan</span></label>
                            <select name="tenor_bulan" id="tenor_bulan" class="form-select form-select-premium fw-bold text-success fs-6" required>
                                <option value="" disabled selected>-- Pilih Jangka Waktu --</option>
                                <option value="6">6 Bulan (0.5 Tahun)</option>
                                <option value="12">12 Bulan (1 Tahun)</option>
                                <option value="18">18 Bulan (1.5 Tahun)</option>
                                <option value="24">24 Bulan (2 Tahun)</option>
                            </select>
                            <small class="text-muted d-block mt-1" style="font-size: 0.72rem;">Sesuai regulasi, pilihan tenor dibatasi kelipatan 6 bulan.</small>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-bold text-dark small mb-1">Pendapatan Gaji Bulanan Anda</label>
                            <select name="c3" class="form-select form-select-premium" required>
                                <option value="" disabled selected>-- Pilih Rentang Gaji --</option>
                                <option value="5">> Rp 5.000.000 / Bulan</option>
                                <option value="4">Rp 4.000.000 – Rp 5.000.000 / Bulan</option>
                                <option value="3">Rp 3.000.000 – Rp 3.999.999 / Bulan</option>
                                <option value="2">Rp 2.000.000 – Rp 2.999.999 / Bulan</option>
                                <option value="1">< Rp 2.000.000 / Bulan</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold text-dark small mb-1">Lama Keanggotaan Koperasi Pegawai</label>
                            <select name="c4" class="form-select form-select-premium" required>
                                <option value="" disabled selected>-- Pilih Lama Anggota --</option>
                                <option value="5">> 5 Tahun</option>
                                <option value="4">3 – 5 Tahun</option>
                                <option value="3">2 – < 3 Tahun</option>
                                <option value="2">1 – < 2 Tahun</option>
                                <option value="1">< 1 Tahun</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold text-dark small mb-1">Kolektibilitas Kredit Berjalan</label>
                            <input type="text" class="form-control form-control-premium bg-light fw-bold text-secondary" value="<?= esc($status_label) ?>" readonly>
                            <input type="hidden" name="c6_hidden" value="<?= esc($skor_c6) ?>">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold text-dark small mb-1">Status Hubungan Sipil</label>
                            <select name="c1_status" id="c1_status" class="form-select form-select-premium" required>
                                <option value="menikah">Menikah (Wajib Unggah Surat Persetujuan Istri/Suami)</option>
                                <option value="belum_menikah">Belum Menikah (Wajib Unggah Surat Keterangan / KTP Pendukung)</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold text-dark small mb-1">Unggah Dokumen Berkas Pendukung (C1)</label>
                            <input type="file" name="bukti_c1" id="bukti_c1" class="form-control form-control-premium" accept="image/*,.pdf">
                            <small class="d-block mt-1" id="note_c1" style="font-size: 0.73rem;"></small>
                        </div>
                    </div>
                </div>

                <!-- TAB 3: REKENING -->
                <div class="wizard-form-page-tab d-none" id="formPage3">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-dark small mb-1">Nama Bank Rekening Pribadi</label>
                            <input type="text" name="nama_bank" class="form-control form-control-premium" placeholder="Contoh: BANK BRI, BANK BSI, BANK MANDIRI" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-dark small mb-1">Nomor Rekening Tujuan Pencairan Modal</label>
                            <input type="text" name="nomor_rekening" class="form-control form-control-premium fw-bold text-dark fs-5" placeholder="Masukkan nomor rekening asli pemohon..." required>
                        </div>
                        <div class="col-12 mt-3">
                            <div class="alert alert-info border-0 rounded-3 small p-3 mb-0">
                                <i class="bi bi-shield-check-fill me-2 fs-6 text-primary"></i>
                                <strong>Pernyataan Otoritas:</strong> Dengan mengirim berkas ini, saya menyatakan data di atas sah dan lembar RHB diisi dengan jujur.
                            </div>
                        </div>
                    </div>
                </div>

                <!-- WIZARD NAV ACTION ACCORDION -->
                <div class="d-flex justify-content-between align-items-center border-top pt-4 mt-5">
                    <button type="button" class="btn btn-outline-secondary btn-wizard-action d-none" id="btnWizardPrev" onclick="moveStep(-1)">
                        <i class="bi bi-arrow-left me-1"></i> Sebelumnya
                    </button>
                    <div class="ms-auto">
                        <button type="button" class="btn btn-primary btn-wizard-action" id="btnWizardNext" onclick="moveStep(1)">
                            Lanjutkan <i class="bi bi-arrow-right ms-1"></i>
                        </button>
                        <button type="submit" class="btn btn-wizard-action btn-premium-submit d-none" id="btnWizardSubmit">
                            <i class="bi bi-send-check-fill me-2"></i> KIRIM PERMOHONAN PINJAMAN
                        </button>
                    </div>
                </div>

            </form>
        </div>
    </div>
    <?php endif; ?>
</div>

<script>
    let currentStep = 1;
    const totalSteps = 3;

    // 🟢 HELPER UTAMA JS: Mengubah nominal murni menjadi format titik ribuan Indonesia
    function formatRupiahString(angka) {
        let number_string = angka.replace(/[^,\d]/g, '').toString(),
            split = number_string.split(','),
            sisa  = split[0].length % 3,
            rupiah = split[0].substr(0, sisa),
            ribuan = split[0].substr(sisa).match(/\d{3}/gi);

        if (ribuan) {
            let separator = sisa ? '.' : '';
            rupiah += separator + ribuan.join('.');
        }
        return rupiah;
    }

    // 🟢 HELPER UTAMA JS: Menghapus titik agar menjadi integer murni sebelum dikirim ke database
    function cleanRupiahValue(stringData) {
        return parseInt(stringData.replace(/\./g, '')) || 0;
    }

    // Event Listener untuk Masking Nominal Utama Yang Diajukan
    const maskJumlah = document.getElementById('jumlah_diajukan_mask');
    const rawJumlah = document.getElementById('jumlah_diajukan');

    if (maskJumlah) {
        maskJumlah.addEventListener('input', function() {
            this.value = formatRupiahString(this.value);
            let nilaiBersih = cleanRupiahValue(this.value);
            rawJumlah.value = nilaiBersih;

            // Validasi Surat C1 Berdasarkan Nilai Bersih Angka Murni
            const buktiInput = document.getElementById('bukti_c1');
            const labelNote = document.getElementById('note_c1');
            if (nilaiBersih >= 25000000) {
                buktiInput.setAttribute('required', 'required');
                labelNote.innerHTML = "<span class='text-danger fw-bold'><i class='bi bi-exclamation-circle-fill me-1'></i>Wajib Berkas! Pengajuan dana $\\ge$ Rp 25.000.000</span>";
            } else {
                buktiInput.removeAttribute('required');
                labelNote.innerHTML = "<span class='text-muted'><i class='bi bi-info-circle me-1'></i>Opsional jika dana di bawah Rp 25 Juta</span>";
            }
        });
    }

    function moveStep(direction) {
        if (direction === 1 && !validateCurrentPage()) {
            Swal.fire({
                title: 'Isian Belum Lengkap',
                text: 'Harap lengkapi semua kolom isian yang bertanda bintang (*) wajib diisi pada halaman ini.',
                icon: 'info',
                confirmButtonColor: '#0066ff',
                confirmButtonText: 'Saya Mengerti',
                customClass: { popup: 'rounded-4' }
            });
            return;
        }
        document.getElementById(`formPage${currentStep}`).classList.add('d-none');
        currentStep += direction;
        document.getElementById(`formPage${currentStep}`).classList.remove('d-none');
        updateWizardUI();
    }

    function validateCurrentPage() {
        const activePage = document.getElementById(`formPage${currentStep}`);
        const inputs = activePage.querySelectorAll('input[required], select[required]');
        let isValid = true;
        inputs.forEach(input => {
            if (!input.value || input.value.trim() === "") {
                input.classList.add('is-invalid');
                isValid = false;
            } else {
                input.classList.remove('is-invalid');
            }
        });
        return isValid;
    }

    function updateWizardUI() {
        const progressLine = document.getElementById('wizardProgressLine');
        const percent = ((currentStep - 1) / (totalSteps - 1)) * 80;
        progressLine.style.width = `${percent}%`;

        for (let i = 1; i <= totalSteps; i++) {
            const node = document.getElementById(`nodeStep${i}`);
            node.classList.remove('active', 'completed');
            if (i < currentStep) {
                node.classList.add('completed');
            } else if (i === currentStep) {
                node.classList.add('active');
            }
        }

        const prevBtn = document.getElementById('btnWizardPrev');
        const nextBtn = document.getElementById('btnWizardNext');
        const submitBtn = document.getElementById('btnWizardSubmit');

        if (currentStep === 1) {
            prevBtn.classList.add('d-none');
            nextBtn.classList.remove('d-none');
            submitBtn.classList.add('d-none');
        } else if (currentStep === totalSteps) {
            prevBtn.classList.remove('d-none');
            nextBtn.classList.add('d-none');
            submitBtn.classList.remove('d-none');
        } else {
            prevBtn.classList.remove('d-none');
            nextBtn.classList.remove('d-none');
            submitBtn.classList.add('d-none');
        }
    }

    // CRUD Dynamic Input Rows Lembar Belanja RHB (Disertai Masking Titik Otomatis)
    function addRow() {
        let table = document.getElementById("tableAnggaranBelanja").getElementsByTagName('tbody')[0];
        let row = table.insertRow(-1);
        row.innerHTML = `
            <td class="text-center fw-bold text-secondary">...</td>
            <td><input type="text" name="rhb[kebutuhan][]" class="form-control form-control-rhb" placeholder="Nama barang..." required></td>
            <td>
                <input type="text" class="form-control form-control-rhb rupiah-mask harga-mask fw-semibold" oninput="calculateRow(this)" placeholder="0" required>
                <input type="hidden" name="rhb[harga][]" class="harga-raw">
            </td>
            <td><input type="number" name="rhb[qty][]" class="form-control form-control-rhb qty text-center" oninput="calculateRow(this)" value="1" min="1" required></td>
            <td>
                <input type="text" class="form-control form-control-rhb subtotal-mask fw-bold text-secondary" readonly placeholder="0">
                <input type="hidden" name="rhb[subtotal][]" class="subtotal-raw">
            </td>
            <td class="text-center"><button type="button" class="btn btn-sm btn-danger rounded-3 px-2.5 py-1" onclick="this.parentElement.parentElement.remove(); reindexRows();"><i class="bi bi-trash-fill"></i></button></td>
        `;
        reindexRows();
    }
    
    // 🟢 LOGIKA PERHITUNGAN DAN MASKING BARIS RHB
    function calculateRow(input) {
        let row = input.parentElement.parentElement;
        let hargaMaskedInput = row.querySelector('.harga-mask');
        
        // Terapkan format titik pada input harga saat user mengetik
        if(input.classList.contains('harga-mask')) {
            input.value = formatRupiahString(input.value);
        }

        let rawHarga = cleanRupiahValue(hargaMaskedInput.value);
        row.querySelector('.harga-raw').value = rawHarga;

        let q = parseInt(row.querySelector('.qty').value) || 0;
        let totalMurni = rawHarga * q;

        // Set nilai mentah dan nilai bermask titik pada kolom subtotal
        row.querySelector('.subtotal-raw').value = totalMurni;
        row.querySelector('.subtotal-mask').value = formatRupiahString(totalMurni.toString());
    }
    
    function reindexRows() {
        let rows = document.querySelectorAll('#tableAnggaranBelanja tbody tr');
        rows.forEach((r, i) => r.cells[0].innerText = i + 1);
    }

    // Pasang listener awal ke baris pertama tabel RHB bawaan halaman
    document.querySelector('.harga-mask').addEventListener('input', function() {
        calculateRow(this);
    });
</script>

<?= $this->endSection(); ?>