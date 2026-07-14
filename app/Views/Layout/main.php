<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title; ?> | Koperasi BPS</title>
    

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    
    <!-- CSS Plugins -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    
    <!-- Custom CSS Terpisah -->
    <link rel="stylesheet" href="<?= base_url('css/style-koperasi.css'); ?>">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f4f7fe; }
        .main-wrapper { display: flex; width: 100%; align-items: stretch; }
        #content { width: 100%; min-height: 100vh; transition: all 0.3s; padding: 0; }
        .content-body { padding: 30px; }
    </style>
</head>
<body>

<div class="main-wrapper">
    <!-- Memanggil Sidebar -->
    <?= $this->include('layout/sidebar'); ?>

    <div id="content">
        <!-- Memanggil Navbar Atas -->
        <?= $this->include('layout/navbar'); ?>

        <div class="content-body">
            <!-- Pesan Alert Otomatis (Flashdata) -->
            <?php if(session()->getFlashdata('success')): ?>
                <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i> <?= session()->getFlashdata('success'); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <!-- Bagian Konten Dinamis -->
            <?= $this->renderSection('content'); ?>
        </div>
    </div>
</div>

<!-- JS Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Area Script Tambahan per Halaman -->
<?= $this->renderSection('script'); ?>

</body>
</html>