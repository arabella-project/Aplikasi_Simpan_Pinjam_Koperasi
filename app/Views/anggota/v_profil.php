<?= $this->extend('layout/v_anggota_layout') ?>
<?= $this->section('content') ?>

<style>
    /* Premium Profile Component Theme */
    .profile-premium-card { border: none; border-radius: 20px; background: #ffffff; border: 1px solid #e2e8f0; }
   
    .form-control-premium { border-radius: 12px; border: 1px solid #cbd5e1; padding: 12px 16px; font-size: 0.9rem; color: #334155; transition: all 0.2s ease; }
    .form-control-premium:focus { border-color: #0066ff; box-shadow: 0 0 0 4px rgba(0, 102, 255, 0.08); outline: none; }
    .form-control-premium[readonly] { background-color: #f8fafc; color: #64748b; font-weight: 600; border-color: #e2e8f0; }

    /* Avatar Upload Preview Box */
    .avatar-upload-wrapper { position: relative; width: 110px; height: 110px; margin: 0 auto 20px; }
    .avatar-preview-circle { width: 100%; height: 100%; border-radius: 50%; border: 3px solid #ffffff; box-shadow: 0 4px 14px rgba(11,26,48,0.12); object-fit: cover; background: #eff6ff; }
    .avatar-upload-icon-badge { position: absolute; bottom: 2px; right: 2px; width: 32px; height: 32px; background: #0066ff; color: #ffffff; border-radius: 50%; display: flex; align-items: center; justify-content: center; border: 2px solid #ffffff; cursor: pointer; transition: all 0.2s; box-shadow: 0 2px 6px rgba(0,0,0,0.15); }
    .avatar-upload-icon-badge:hover { background: #0052cc; transform: scale(1.08); }

    /* Action Button Custom styles */
    .btn-premium-save { background: linear-gradient(135deg, #0066ff 0%, #0052cc 100%); border: none; color: #ffffff; padding: 12px; font-weight: 700; border-radius: 12px; font-size: 0.88rem; transition: all 0.2s; box-shadow: 0 4px 12px rgba(0, 102, 255, 0.15); }
    .btn-premium-save:hover { background: linear-gradient(135deg, #0052cc 0%, #0044b3 100%); transform: translateY(-1px); box-shadow: 0 6px 16px rgba(0, 102, 255, 0.25); }
    
    .btn-premium-orange { background: linear-gradient(135deg, #f97316 0%, #ea580c 100%); box-shadow: 0 4px 12px rgba(249, 115, 22, 0.15); border: none; color: #fff; padding: 12px; font-weight: 700; border-radius: 12px; font-size: 0.88rem; transition: all 0.2s; }
    .btn-premium-orange:hover { background: linear-gradient(135deg, #ea580c 0%, #d97706 100%); transform: translateY(-1px); box-shadow: 0 6px 16px rgba(249, 115, 22, 0.25); }
</style>

<div class="container-fluid p-0">
    <?php if (session()->getFlashdata('success')) : ?>
        <div class="alert alert-success border-0 shadow-sm rounded-3 p-3 mb-4 d-flex align-items-center gap-2" style="background-color: #f0fdf4; color: #16a34a;">
            <i class="bi bi-check-circle-fill fs-5"></i> <span class="fw-semibold small"><?= session()->getFlashdata('success') ?></span>
        </div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')) : ?>
        <div class="alert alert-danger border-0 shadow-sm rounded-3 p-3 mb-4 d-flex align-items-center gap-2" style="background-color: #fef2f2; color: #dc2626;">
            <i class="bi bi-exclamation-circle-fill fs-5"></i> <span class="fw-semibold small"><?= session()->getFlashdata('error') ?></span>
        </div>
    <?php endif; ?>

    <div class="row g-4">
        
        <div class="col-lg-7">
            <div class="card profile-premium-card shadow-sm">
                <div class="p-4 border-bottom bg-white">
                    <h6 class="fw-bold text-dark mb-1"><i class="bi bi-person-lines-fill me-2 text-primary"></i>Informasi Data Diri</h6>
                    <p class="text-muted small mb-0">Kelola informasi keanggotaan Anda.</p>
                </div>
                <div class="card-body p-4 p-md-5">
                    
                    <form action="<?= base_url('anggota/profil/update') ?>" method="post" enctype="multipart/form-data">
                        <?= csrf_field() ?>
                        <input type="hidden" name="old_foto" value="<?= $user['foto'] ?? '' ?>">

                        <div class="text-center mb-4">
                            <div class="avatar-upload-wrapper">
                                <?php if (!empty($user['foto']) && file_exists(FCPATH . 'uploads/profil/' . $user['foto'])) : ?>
                                    <img src="<?= base_url('uploads/profil/' . $user['foto']) ?>" id="profileImagePreview" class="avatar-preview-circle" alt="Foto Profil">
                                <?php else : ?>
                                    <div class="avatar-preview-circle d-flex align-items-center justify-content-center text-primary border"><i class="bi bi-person-fill display-4"></i></div>
                                <?php endif; ?>
                                
                                <label for="uploadFotoInput" class="avatar-upload-icon-badge"><i class="bi bi-camera-fill small"></i></label>
                                <input type="file" name="foto" id="uploadFotoInput" class="d-none" accept="image/*" onchange="previewImage(this)">
                            </div>
                            <span class="badge bg-light text-secondary border px-2.5 py-1.5 small fw-semibold">ID Anggota: #BPS-<?= $user['id_anggota'] ?></span>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label form-label-premium">ID Anggota (Kunci)</label>
                                <input type="text" class="form-control form-control-premium" value="BPS-<?= $user['id_anggota'] ?>" readonly>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label form-label-premium">Username Akses</label>
                                <input type="text" class="form-control form-control-premium" value="<?= esc($user['username']) ?>" readonly>
                            </div>
                            <div class="col-12">
                                <label class="form-label form-label-premium">Nama Lengkap Anggota</label>
                                <input type="text" name="nama_lengkap" class="form-control form-control-premium" value="<?= esc($user['nama_lengkap'] ?? $user['nama_anggota'] ?? '') ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label form-label-premium">Nomor Telepon / WA</label>
                                <input type="text" name="no_telp" class="form-control form-control-premium" value="<?= esc($user['no_telp'] ?? '') ?>" placeholder="Contoh: 08123456789" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label form-label-premium">Alamat Email Aktif</label>
                                <input type="email" name="email" class="form-control form-control-premium" value="<?= esc($user['email'] ?? '') ?>" placeholder="nama@mail.com" required>
                            </div>
                            
                            <div class="col-12 mt-4">
                                <button type="submit" class="btn btn-primary btn-premium-save w-100">
                                    <i class="bi bi-check-circle-fill me-2"></i>SIMPAN PERUBAHAN DATA PROFIL
                                </button>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="card profile-premium-card shadow-sm h-100">
                <div class="p-4 border-bottom bg-white">
                    <h6 class="fw-bold text-dark mb-1"><i class="bi bi-shield-lock-fill me-2 text-danger"></i>Keamanan Sandi Akun</h6>
                    <p class="text-muted small mb-0">Perbarui kunci password Anda untuk proteksi enkripsi ganda.</p>
                </div>
                <div class="card-body p-4 p-md-5 d-flex flex-column justify-content-center">
                    
                    <form action="<?= base_url('anggota/profil/update_password') ?>" method="post">
                        <?= csrf_field() ?>
                        
                        <div class="mb-3">
                            <label class="form-label form-label-premium">Password Saat Ini</label>
                            <input type="password" name="old_password" class="form-control form-control-premium" placeholder="Masukkan password sekarang" required>
                        </div>
                        
                        <hr class="my-4 opacity-25">
                        
                        <div class="mb-3">
                            <label class="form-label form-label-premium">Password Baru</label>
                            <input type="password" name="new_password" class="form-control form-control-premium" placeholder="Minimal 6 karakter baru" required minlength="6">
                        </div>

                        <div class="mb-4">
                            <label class="form-label form-label-premium">Konfirmasi Ulang Password Baru</label>
                            <input type="password" name="confirm_password" class="form-control form-control-premium" placeholder="Ulangi kembali password baru" required>
                        </div>

                        <button type="submit" class="btn btn-warning btn-premium-orange w-100">
                            <i class="bi bi-key-fill me-2"></i>AMANKAN KUNCI PASSWORD BARU
                        </button>
                    </form>

                </div>
            </div>
        </div>

    </div>
</div>

<script>
function previewImage(input) {
    if (input.files && input.files[0]) {
        let reader = new FileReader();
        reader.onload = function(e) {
            let previewContainer = document.getElementById('profileImagePreview');
            if (previewContainer) {
                previewContainer.src = e.target.result;
            } else {
                let wrapper = document.querySelector('.avatar-upload-wrapper');
                let img = document.createElement('img');
                img.id = 'profileImagePreview';
                img.className = 'avatar-preview-circle';
                img.src = e.target.result;
                wrapper.insertBefore(img, wrapper.firstChild);
                let iconPlaceholder = wrapper.querySelector('.avatar-preview-circle.d-flex');
                if (iconPlaceholder) iconPlaceholder.remove();
            }
        }
        reader.readAsDataURL(input.files[0]);
    }
}
</script>

<?= $this->endSection() ?>