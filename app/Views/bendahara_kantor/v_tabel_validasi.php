<?= $this->extend('layout/main_bendahara_kantor'); ?>
<?= $this->section('content'); ?>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
    :root {
        --fin-navy: #111827;
        --fin-blue: #3b82f6;
        --fin-blue-light: #eff6ff;
        --fin-green: #10b981;
        --fin-green-light: #ecfdf5;
        --text-dark: #0f172a;
        --text-muted: #64748b;
        --border-light: rgba(226, 232, 240, 0.8);
    }

    .tracking-tight { letter-spacing: -0.5px; }
    .font-monospace { font-family: 'Fira Code', 'Courier New', monospace !important; }

    /* Modern Table Card System */
    .fintech-table-card { 
        border-radius: 20px; 
        background: #ffffff; 
        border: 1px solid var(--border-light); 
        box-shadow: 0 4px 24px rgba(15, 23, 42, 0.02);
        overflow: hidden;
    }

    .table-premium th { 
        position: sticky !important; 
        top: 0 !important; 
        z-index: 10; 
        background-color: #f8fafc !important; 
        color: #475569 !important; 
        font-weight: 700 !important; 
        font-size: 0.72rem; 
        text-transform: uppercase; 
        letter-spacing: 0.8px; 
        padding: 18px 24px; 
        border-bottom: 2px solid #e2e8f0 !important; 
    }

    .table-premium td { 
        padding: 18px 24px; 
        font-size: 0.88rem; 
        color: #334155; 
        border-bottom: 1px solid #f1f5f9;
        vertical-align: middle;
    }
    .table-premium tbody tr:last-child td { border-bottom: none; }
    .table-premium tbody tr:hover { background-color: #f8fafc; }

    .avatar-premium-circle { 
        width: 40px; 
        height: 40px; 
        background: var(--fin-blue-light); 
        color: #1d4ed8; 
        display: flex; 
        align-items: center; 
        justify-content: center; 
        border-radius: 50%; 
        font-weight: 700; 
        font-size: 0.88rem; 
        border: 1px solid rgba(59, 130, 246, 0.1);
    }

    /* Slick Premium Pill Radio Button Styling */
    .radio-container {
        display: inline-flex;
        align-items: center;
        cursor: pointer;
        font-weight: 600;
        font-size: 0.78rem;
        padding: 8px 18px;
        border-radius: 50px;
        border: 1.5px solid #e2e8f0;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        background-color: #ffffff;
        color: #64748b;
    }
    .radio-container input[type="radio"] { display: none; }
    
    .radio-success-pill:hover { border-color: var(--fin-green); background-color: var(--fin-green-light); color: var(--fin-green); }
    .radio-success-pill.active-checked { 
        border-color: var(--fin-green) !important; 
        background-color: var(--fin-green-light) !important; 
        color: var(--fin-green) !important; 
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.15);
    }

    .radio-danger-pill:hover { border-color: #ef4444; background-color: #fef2f2; color: #ef4444; }
    .radio-danger-pill.active-checked { 
        border-color: #ef4444 !important; 
        background-color: #fef2f2 !important; 
        color: #ef4444 !important; 
        box-shadow: 0 4px 12px rgba(239, 68, 68, 0.15);
    }

  /* 🟢 GAYA WARNA GRADASI BIRU PREMIUM */
  .btn-premium-blue {
        background: linear-gradient(135deg, #00a2e9 0%, #0066cc 100%) !important;
        color: #ffffff !important;
        font-size: 0.88rem;
        letter-spacing: 0.3px;
        transition: all 0.3s ease-in-out;
        box-shadow: 0 4px 15px rgba(0, 162, 233, 0.2) !important;
    }

    /* Efek interaktif saat kursor menyentuh tombol */
    .btn-premium-blue:hover {
        background: linear-gradient(135deg, #008cd1 0%, #0052b3 100%) !important;
        transform: translateY(-1px);
        box-shadow: 0 6px 20px rgba(0, 162, 233, 0.35) !important;
        color: #ffffff !important;
    }
</style>

<div class="container-fluid py-4 px-4" style="background-color: #f8fafc; min-height: 100vh;">
    
    <!-- TOP INTERFACE HEADER BAR -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 pb-2">
        <div class="mb-3 mb-md-0">
            <span class="text-uppercase tracking-widest fw-bold d-block" style="font-size: 0.68rem; color: var(--fin-blue); letter-spacing: 1px;">Kompilasi Data Transaksi</span>
            <h3 class="fw-bold text-dark tracking-tight mb-0 mt-1" style="font-size: 1.4rem;">Validasi Potong Gaji Pegawai</h3>
        </div>
        
        <!-- Action Utility Tools -->
        <div class="d-flex align-items-center gap-2 flex-wrap">
            <a href="<?= base_url('kantor/dashbord/export_excel') ?>" class="btn btn-sm btn-white border rounded-pill px-3 fw-semibold shadow-sm d-inline-flex align-items-center small text-secondary bg-white">
                <i class="bi bi-file-earmark-excel-fill text-success me-1.5"></i> Export Excel
            </a>
            <a href="<?= base_url('kantor/dashbord/export_pdf') ?>" class="btn btn-sm btn-white border rounded-pill px-3 fw-semibold shadow-sm d-inline-flex align-items-center small text-secondary bg-white">
                <i class="bi bi-file-earmark-pdf-fill text-danger me-1.5"></i> Cetak PDF
            </a>
            <a href="<?= base_url('kantor/dashbord/riwayat') ?>" class="btn btn-sm btn-white border rounded-pill px-3 fw-semibold shadow-sm d-inline-flex align-items-center small text-secondary bg-white">
                <i class="bi bi-clock-history text-primary me-1.5"></i> Riwayat Otoritas
            </a>
        </div>
    </div>

    <!-- FLASH DATA ALERTS HANDLER -->
    <?php if (session()->getFlashdata('success')) : ?>
        <div class="alert alert-success border-0 small rounded-4 bg-success bg-opacity-10 text-success p-3 mb-4 d-flex align-items-center gap-2 shadow-sm">
            <i class="bi bi-check-circle-fill"></i> <span><?= session()->getFlashdata('success') ?></span>
        </div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')) : ?>
        <div class="alert alert-danger border-0 small rounded-4 bg-danger bg-opacity-10 text-danger p-3 mb-4 d-flex align-items-center gap-2 shadow-sm">
            <i class="bi bi-exclamation-triangle-fill"></i> <span><?= session()->getFlashdata('error') ?></span>
        </div>
    <?php endif; ?>

    <!-- CORE TRANSACTION WORKSPACE FORM -->
    <form id="formValidasiSerentak" action="<?= base_url('kantor/dashbord/validasi_serentak') ?>" method="POST">
        <?= csrf_field() ?>
        
 <!-- CORE TRANSACTION WORKSPACE FORM -->
 <form id="formValidasiSerentak" action="<?= base_url('kantor/dashbord/validasi_serentak') ?>" method="POST">
        <?= csrf_field() ?>
        
        <div class="fintech-table-card mb-4">
            <div class="p-3 bg-white d-flex align-items-center justify-content-between border-bottom px-4">
                <div class="d-flex align-items-center gap-2">
                    <div class="avatar-premium-circle" style="width:32px; height:32px;"><i class="bi bi-collection-play-fill" style="font-size:0.9rem;"></i></div>
                    <h6 class="fw-bold mb-0 text-dark" style="font-size:0.9rem;">Daftar Antrean Auto-Debit Payroll</h6>
                </div>
                <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-3 py-1.5 fw-bold" style="font-size:0.65rem; letter-spacing:0.5px;">READY TO MUTATE</span>
            </div>
            
            <div class="table-responsive">
                <table class="table table-premium align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="text-center" width="70">Rank</th>
                            <th class="text-start">Nama Anggota Rekening</th>
                            <th class="text-center" width="180">Periode Potongan</th>
                            <th class="text-end" width="180">Nominal Tagihan</th>
                            <th class="text-center" width="360">Opsi Mutasi Otoritas Kantor</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(!empty($list_validasi)) : $no=1; foreach($list_validasi as $row) : ?>
                            <tr>
                                <td class="text-center font-monospace fw-bold text-secondary">#<?= $no++ ?></td>
                                <td class="text-start">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-premium-circle me-3 fw-bold">
                                            <?= strtoupper(substr($row['nama_anggota'], 0, 2)) ?>
                                        </div>
                                        <div>
                                            <div class="fw-bold text-dark" style="font-size: 0.88rem;"><?= esc($row['nama_anggota']) ?></div>
                                            <small class="text-success fw-semibold" style="font-size: 11px;"><i class="bi bi-patch-check-fill me-1"></i>PNS / Pegawai BPS</small>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <span class="badge border px-3 py-1.5 rounded-pill fw-bold text-uppercase" style="font-size: 0.72rem; border-color: #e2e8f0 !important; color: #475569 !important; background-color: #f8fafc;">
                                        <i class="bi bi-calendar-event me-1.5 text-primary"></i><?= date('M Y') ?>
                                    </span>
                                </td>
                                <td class="text-end fw-bold font-monospace text-dark" style="font-size: 0.95rem;">
                                    Rp <?= number_format($row['total_potongan'], 0, ',', '.') ?>
                                </td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-2">
                                        <label class="radio-container radio-success-pill active-checked" id="container-terima-<?= $row['id_potongan'] ?>">
                                            <input type="radio" name="opsi_debit[<?= $row['id_potongan'] ?>]" value="terima" checked onchange="ubahWarnaPill('<?= $row['id_potongan'] ?>', 'terima')">
                                            <span><i class="bi bi-check2-circle me-1"></i> Berhasil Debit</span>
                                        </label>
                                        
                                        <label class="radio-container radio-danger-pill" id="container-tolak-<?= $row['id_potongan'] ?>">
                                            <input type="radio" name="opsi_debit[<?= $row['id_potongan'] ?>]" value="tolak" onchange="ubahWarnaPill('<?= $row['id_potongan'] ?>', 'tolak')">
                                            <span><i class="bi bi-x-circle me-1"></i> Gagal Debit</span>
                                        </label>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; else : ?>
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted small">
                                    <div class="py-4">
                                        <i class="bi bi-folder-x fs-2 text-secondary opacity-50 mb-2 d-block"></i>
                                        <p class="text-secondary italic mb-0">Bersih! Tidak ada antrean berkas payroll potong gaji dalam log kantor.</p>
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                    
                    <?php if(!empty($list_validasi)) : ?>
                        <tfoot>
                            <tr>
                            <tfoot>
                                  <tr>
                                     <td colspan="5" class="text-end py-3 px-4" style="background-color: #f8fafc; border-top: 2px solid #e2e8f0;">
                                     <button type="button" id="btnSubmitSerentak" class="btn btn-premium-blue px-5 py-2.5 fw-bold rounded-pill border-0 shadow-sm">
                                      <i class="bi bi-shield-lock-fill me-2 text-white"></i> Validasi Serentak
                                      </b utton>
                                     </td>
                                 </tr>
                            
                       </tfoot>
                    <?php endif; ?>
                </table>
            </div>
        </div>
    </form>
<script>
    // Micro interaction function untuk mengubah warna pill background radio secara realtime
    function ubahWarnaPill(id, jenis) {
        const pTerima = document.getElementById(`container-terima-${id}`);
        const pTolak = document.getElementById(`container-tolak-${id}`);
        
        if (jenis === 'terima') {
            pTerima.classList.add('active-checked');
            pTolak.classList.remove('active-checked');
        } else {
            pTolak.classList.add('active-checked');
            pTerima.classList.remove('active-checked');
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        const btnSubmit = document.getElementById('btnSubmitSerentak');
        if(btnSubmit) {
            btnSubmit.addEventListener('click', function (e) {
                e.preventDefault();
                
                Swal.fire({
                    title: 'Proses Otoritas Serentak?',
                    text: 'Apakah Anda yakin ingin mengeksekusi seluruh status debit potongan gaji pegawai yang telah dipilih di atas secara bersamaan?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#111827', 
                    cancelButtonColor: '#cbd5e1',
                    confirmButtonText: 'Ya, Eksekusi Serentak',
                    cancelButtonText: 'Batal',
                    customClass: { popup: 'rounded-4' }
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById('formValidasiSerentak').submit();
                    }
                });
            });
        }
    });
</script>

<?= $this->endSection(); ?>