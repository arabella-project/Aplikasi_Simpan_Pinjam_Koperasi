<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Bendahara Kantor BPS' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <style>
        body {
            background-color: #f8fafc;
            padding-left: 260px; /* Menyediakan ruang agar tidak tertutup sidebar fixed */
            min-height: 100vh;
        }
        .main-content-wrapper {
            width: 100%;
            min-height: 100vh;
            background-color: #f8fafc;
        }
    </style>
</head>
<body>

    <?= $this->include('layout/sidebar_bendahara'); ?>

    <div class="main-content-wrapper">
        <?= $this->renderSection('content'); ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>