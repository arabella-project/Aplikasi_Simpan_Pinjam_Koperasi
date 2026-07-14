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
    .modal-premium-clean .modal-header { border-bottom: none !important; padding: 24px 24px 8px 24px !important; }
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
    
    .btn-simpan-full {
        width: 100%;
        background-color: var(--bps-blue);
        border: none;
        padding: 12px;
        border-radius: 10px;
        transition: background-color 0.2s;
    }
    .btn-simpan-full:hover {
        background-color: var(--bps-blue-dark);
    }
</style>

<div class="container-fluid py-3 px-4">
    
    <div class="row align-items-center mb-4 g-3">
        <div class="col-12 col-md-6">
            <span class="text-uppercase tracking-widest fw-bold mb-1 d-block" style="font-size: 0.68rem; color: var(--bps-blue);">Otoritas Kontrol Sistem</span>
            <h4 class="fw-bold text-dark tracking-tight mb-0">Kelola Akun Otoritas Pengguna</h4>
         </div>
        <div class="col-12 col-md-6 text-md-end">
            <button type="button" class="btn btn-bps-blue btn-premium-pill shadow-sm" data-bs-toggle="modal" data-bs-target="#modalTambahAkun">
                <i class="bi bi-person-plus-fill me-2"></i> Tambah Akun Baru
            </button>
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
                        <small class="text-uppercase fw-bold text-muted" style="font-size: 0.65rem; letter-spacing: 0.5px;">Total Akun</small>
                        <h4 class="fw-bold text-dark tracking-tight mb-0 mt-1"><?= count($users ?? []); ?></h4>
                    </div>
                    <div class="metric-icon-circle" style="background-color: rgba(0, 162, 233, 0.08); color: var(--bps-blue);">
                        <i class="bi bi-shield-lock-fill"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-3">
            <div class="card card-metric-summary p-3 shadow-sm h-100">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-uppercase fw-bold text-muted" style="font-size: 0.65rem; letter-spacing: 0.5px;">Ketua Koperasi</small>
                        <?php 
                            $count_ketua = 0;
                            if(!empty($users)) {
                                foreach($users as $u) { if(($u['role'] ?? '') == 'ketua_kop') $count_ketua++; }
                            }
                        ?>
                        <h4 class="fw-bold text-dark tracking-tight mb-0 mt-1"><?= $count_ketua ?> Pimpinan</h4>
                        <small class="text-muted" style="font-size: 0.72rem;">Otoritas finalisasi</small>
                    </div>
                    <div class="metric-icon-circle" style="background-color: rgba(124, 58, 237, 0.08); color: var(--bps-purple);">
                        <i class="bi bi-person-workspace"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-3">
            <div class="card card-metric-summary p-3 shadow-sm h-100">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-uppercase fw-bold text-muted" style="font-size: 0.65rem; letter-spacing: 0.5px;">Bendahara Koperasi</small>
                        <?php 
                            $count_kop = 0;
                            if(!empty($users)) {
                                foreach($users as $u) { if(($u['role'] ?? '') == 'bendahara_kop') $count_kop++; }
                            }
                        ?>
                        <h4 class="fw-bold text-dark tracking-tight mb-0 mt-1"><?= $count_kop ?> Admin</h4>
                    </div>
                    <div class="metric-icon-circle" style="background-color: rgba(239, 68, 68, 0.08); color: red;">
                        <i class="bi bi-person-badge-fill"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-3">
            <div class="card card-metric-summary p-3 shadow-sm h-100">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-uppercase fw-bold text-muted" style="font-size: 0.65rem; letter-spacing: 0.5px;">Bendahara Kantor</small>
                        <?php 
                            $count_kan = 0;
                            if(!empty($users)) {
                                foreach($users as $u) { if(($u['role'] ?? '') == 'bendahara_kan') $count_kan++; }
                            }
                        ?>
                        <h4 class="fw-bold text-dark tracking-tight mb-0 mt-1"><?= $count_kan ?> Admin</h4>
                    </div>
                    <div class="metric-icon-circle" style="background-color: rgba(244, 130, 33, 0.08); color: var(--bps-orange);">
                        <i class="bi bi-building-fill-check"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm bg-white p-0 rounded-4 overflow-hidden">
        <div class="card-header bg-white border-0 py-3 px-4">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                <h6 class="fw-bold text-dark mb-0 d-flex align-items-center gap-2"><i class="bi bi-person-lines-fill text-primary"></i> Manifes Kredensial Pengguna Sistem</h6>
                <div>
                    <input type="text" id="cariNama" class="form-control form-control-sm border rounded-3 px-3 py-1.5" style="font-size: 0.82rem; width: 240px;" placeholder="Cari nama anggota...">
                </div>
            </div>
        </div>

        <div class="scrollable-table-wrapper">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-premium-header">
                    <tr>
                        <th width="70" class="text-center">ID</th>
                        <th class="text-start">Nama Anggota</th>
                        <th class="text-center">Username</th>
                        <th class="text-center">Hak Akses (Role)</th>
                        <th class="text-end">Gaji Pokok</th>
                        <th width="180" class="text-center">Aksi Manajemen</th>
                    </tr>
                </thead>
                <tbody class="table-premium-body" id="bodyTabelNama">
                    <?php if (!empty($users)) : $colorIndex = 1; foreach ($users as $row) : 
                        $initials = strtoupper(substr($row['nama_anggota'] ?? 'AG', 0, 2));
                    ?>
                    <tr>
                        <td class="font-monospace text-secondary small text-center fw-bold">#<?= esc($row['id_anggota']) ?></td>
                        <td class="text-start">
                            <div class="d-flex align-items-center gap-3">
                                <div class="avatar-circle avatar-<?= $colorIndex; ?>"><?= $initials ?></div>
                                <div>
                                    <div class="fw-bold text-dark mb-0.5" style="font-size: 0.88rem;"><?= esc($row['nama_anggota'] ?? '-') ?></div>
                                    <span class="text-muted" style="font-size: 0.72rem;"><i class="bi bi-envelope me-1"></i><?= esc($row['email'] ?? 'belum diatur') ?></span>
                                </div>
                            </div>
                        </td>
                        <td class="font-monospace small text-center"><?= esc($row['username'] ?? '-') ?></td>
                        <td class="text-center">
                            <?php if (($row['role'] ?? '') == 'ketua_kop') : ?>
                                <span class="badge bg-purple bg-opacity-10 text-purple border px-2.5 py-1.5 rounded-pill fw-bold text-uppercase" style="font-size:0.65rem; color:var(--bps-purple); border-color: rgba(124,58,237,0.15)">Ketua Koperasi</span>
                            <?php elseif (($row['role'] ?? '') == 'bendahara_kop') : ?>
                                <span class="badge bg-danger bg-opacity-10 text-danger border px-2.5 py-1.5 rounded-pill fw-bold text-uppercase" style="font-size:0.65rem; border-color: rgba(239,68,68,0.15)">Bendahara Koperasi</span>
                            <?php elseif (($row['role'] ?? '') == 'bendahara_kan') : ?>
                                <span class="badge bg-warning bg-opacity-10 text-warning border px-2.5 py-1.5 rounded-pill fw-bold text-uppercase" style="font-size:0.65rem; border-color: rgba(244,130,33,0.15)">Bendahara Kantor</span>
                            <?php else : ?>
                                <span class="badge bg-primary bg-opacity-10 text-primary border px-2.5 py-1.5 rounded-pill fw-bold text-uppercase" style="font-size:0.65rem; border-color: rgba(0,162,233,0.15)">Anggota</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-end fw-bold font-monospace text-success" style="font-size: 0.92rem;">Rp <?= number_format($row['gaji_pokok'] ?? 0, 0, ',', '.') ?></td>
                        <td class="text-center">
                            <div class="d-flex justify-content-center gap-1">
                                <button type="button" class="btn btn-sm btn-light border rounded-circle" style="width:30px; height:30px; padding:0;"
                                        data-idanggota="<?= $row['id_anggota'] ?>"
                                        data-iduser="<?= $row['id_user'] ?? '' ?>"
                                        data-nama="<?= esc($row['nama_anggota'] ?? '') ?>" 
                                        data-username="<?= esc($row['username'] ?? '') ?>" 
                                        data-role="<?= $row['role'] ?? 'anggota' ?>" 
                                        data-gaji="<?= (int)($row['gaji_pokok'] ?? 0) ?>"
                                        data-email="<?= esc($row['email'] ?? '') ?>"
                                        data-telp="<?= esc($row['no_telp'] ?? '') ?>" id="btnEdit_<?= $row['id_anggota'] ?>" onclick="bukaModalEdit(this)">
                                    <i class="bi bi-pencil-square text-warning"></i>
                                </button>
                                
                                <button type="button" class="btn btn-sm btn-light border rounded-circle" style="width:30px; height:30px; padding:0;"
                                        onclick="konfirmasiHapus('<?= $row['id_anggota'] ?>', '<?= esc($row['nama_anggota'] ?? '-') ?>')">
                                    <i class="bi bi-trash text-danger"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <?php 
                        $colorIndex = ($colorIndex % 5) + 1;
                        endforeach; else : 
                    ?>
                    <tr><td colspan="6" class="text-center py-5 text-muted small">Belum ada data akun terdaftar.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade modal-premium-clean" id="modalTambahAkun" data-bs-backdrop="static" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 480px;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold text-dark"><i class="bi bi-person-plus-fill text-primary me-2"></i>Registrasi Anggota Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?= base_url('bendahara/akun/simpan') ?>" method="post">
                <?= csrf_field() ?>
                <div class="modal-body p-4 pt-1">
                    <div class="mb-3">
                        <label class="form-label">Nama Lengkap Anggota</label>
                        <input type="text" name="nama_anggota" class="form-control" placeholder="Nama lengkap sesuai data instansi..." required>
                    </div>
                    <div class="row mb-3 g-2">
                        <div class="col-6">
                            <label class="form-label">Username Login</label>
                            <input type="text" name="username" class="form-control" placeholder="Username unik..." required>
                        </div>
                        <div class="col-6">
                            <label class="form-label">Password Sistem</label>
                            <input type="password" name="password" class="form-control" placeholder="Kata sandi..." required>
                        </div>
                    </div>
                    <div class="row mb-3 g-2">
                        <div class="col-6">
                            <label class="form-label">Email Kantor</label>
                            <input type="email" name="email" class="form-control" placeholder="nama@bps.go.id">
                        </div>
                        <div class="col-6">
                            <label class="form-label">No. Telepon / WA</label>
                            <input type="text" name="no_telp" class="form-control" placeholder="08xxxxxxxxxx">
                        </div>
                    </div>
                    <div class="row g-2 mb-2">
                        <div class="col-6">
                            <label class="form-label">Otoritas Akses (Role)</label>
                            <select name="role" class="form-select" required>
                                <option value="anggota">Anggota</option>
                                <option value="ketua_kop">Ketua Koperasi</option>
                                <option value="bendahara_kop">Bendahara Koperasi</option>
                                <option value="bendahara_kan">Bendahara Kantor</option>
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="form-label">Gaji Pokok Anggota (Rp)</label>
                            <input type="number" name="gaji_pokok" class="form-control fw-bold text-success" placeholder="0" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 p-4 pt-0">
                    <button type="submit" class="btn-simpan-full text-white shadow-sm fw-bold">Daftarkan Akun</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade modal-premium-clean" id="modalEditAkun" data-bs-backdrop="static" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 480px;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold text-dark"><i class="bi bi-pencil-square text-warning me-2"></i>Pembaruan Data Anggota</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?= base_url('bendahara/akun/update') ?>" method="post">
                <?= csrf_field() ?>
                <input type="hidden" name="id_anggota" id="editIdAnggota">
                <input type="hidden" name="id_user" id="editIdUser">
                <div class="modal-body p-4 pt-1">
                    <div class="mb-3">
                        <label class="form-label">Nama Lengkap Anggota</label>
                        <input type="text" name="nama_anggota" id="editNama" class="form-control" required>
                    </div>
                    <div class="row mb-3 g-2">
                        <div class="col-6">
                            <label class="form-label">Username Login</label>
                            <input type="text" name="username" id="editUsername" class="form-control" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label">Reset Password (Opsional)</label>
                            <input type="password" name="password" class="form-control" placeholder="Isi jika ingin diganti...">
                        </div>
                    </div>
                    <div class="row mb-3 g-2">
                        <div class="col-6">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" id="editEmail" class="form-control">
                        </div>
                        <div class="col-6">
                            <label class="form-label">No. Telepon</label>
                            <input type="text" name="no_telp" id="editTelp" class="form-control">
                        </div>
                    </div>
                    <div class="row g-2 mb-2">
                        <div class="col-6">
                            <label class="form-label">Otoritas Akses (Role)</label>
                            <select name="role" id="editRole" class="form-select" required>
                                <option value="anggota">Anggota</option>
                                <option value="ketua_kop">Ketua Koperasi</option>
                                <option value="bendahara_kop">Bendahara Koperasi</option>
                                <option value="bendahara_kan">Bendahara Kantor</option>
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="form-label">Gaji Pokok (Rp)</label>
                            <input type="number" name="gaji_pokok" id="editGaji" class="form-control fw-bold text-success" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 p-4 pt-0">
                    <button type="submit" class="btn-simpan-full text-white shadow-sm fw-bold" style="background-color: var(--bps-orange) !important;">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<form id="formGlobalHapus" method="post" style="display: none;">
    <?= csrf_field() ?>
</form>

<script>
function bukaModalEdit(el) {
    document.getElementById('editIdAnggota').value = el.getAttribute('data-idanggota');
    document.getElementById('editIdUser').value = el.getAttribute('data-iduser');
    document.getElementById('editNama').value = el.getAttribute('data-nama');
    document.getElementById('editUsername').value = el.getAttribute('data-username');
    document.getElementById('editRole').value = el.getAttribute('data-role');
    document.getElementById('editGaji').value = el.getAttribute('data-gaji');
    document.getElementById('editEmail').value = el.getAttribute('data-email');
    document.getElementById('editTelp').value = el.getAttribute('data-telp');
    
    const editModal = new bootstrap.Modal(document.getElementById('modalEditAkun'));
    editModal.show();
}

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

function konfirmasiHapus(idAnggota, namaAnggota) {
    const formEksekutor = document.getElementById('formGlobalHapus');

    if (!idAnggota || idAnggota === "" || idAnggota === "0") {
        Swal.fire({
            title: 'Sistem Error!',
            text: 'ID Anggota terdeteksi kosong.',
            icon: 'error',
            confirmButtonColor: '#00a2e9'
        });
        return;
    }

    Swal.fire({
        title: 'Hapus Anggota?',
        html: `Apakah Anda yakin ingin menghapus profil dan seluruh hak akses login dari <strong>${namaAnggota}</strong> secara permanen?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya, Hapus Permanen',
        cancelButtonText: 'Batal',
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#94a3b8',
        customClass: { popup: 'rounded-4' }
    }).then((result) => {
        if (result.isConfirmed) {
            const urlAksi = `<?= base_url('bendahara/akun/hapus') ?>/${idAnggota}`;
            formEksekutor.setAttribute('action', urlAksi);
            formEksekutor.submit();
        }
    });
}
</script>

<?= $this->endSection(); ?>