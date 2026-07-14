<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Login | Koperasi BPS</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

<style>

:root{

    /* Sama seperti Dashboard */

    --primary:#00A2E9;
    --success:#4AA135;
    --warning:#F48221;

    --sidebar:#0F172A;
    --sidebar-hover:#1E293B;

    --background:#F8FAFC;

    --card:#FFFFFF;

    --text:#0F172A;
    --text-muted:#64748B;

    --border:#E2E8F0;
}

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:'Inter',sans-serif;
}

body{
    min-height:100vh;
    background:var(--background);
    display:flex;
}

/* ==========================
   PANEL KIRI
========================== */

.left-side{

    width:45%;
    background:var(--sidebar);

    display:flex;
    flex-direction:column;
    justify-content:center;

    padding:60px;
    position:relative;
    overflow:hidden;
}

.left-side::before{
    content:'';
    position:absolute;

    width:350px;
    height:350px;

    background:rgba(0,162,233,.08);

    border-radius:50%;

    top:-100px;
    right:-100px;
}

.left-side::after{
    content:'';
    position:absolute;

    width:250px;
    height:250px;

    background:rgba(0,162,233,.05);

    border-radius:50%;

    bottom:-80px;
    left:-80px;
}

.logo-box{

    display:flex;
    align-items:center;
    gap:15px;

    margin-bottom:60px;

    position:relative;
    z-index:2;
}

.logo-icon{

    width:60px;
    height:60px;

    background:linear-gradient(
        135deg,
        var(--primary),
        #4f8dfd
    );

    border-radius:18px;

    display:flex;
    align-items:center;
    justify-content:center;

    color:white;
    font-size:28px;
}

.logo-text h3{
    color:white;
    margin:0;
    font-size:28px;
    font-weight:700;
}

.logo-text p{
    margin:0;
    color:#94A3B8;
}

.welcome{
    position:relative;
    z-index:2;
}

.welcome h1{

    color:white;

    font-size:48px;
    font-weight:800;

    margin-bottom:20px;
}

.welcome p{

    color:#CBD5E1;

    line-height:1.8;

    font-size:16px;
}

.feature-list{

    margin-top:40px;
    position:relative;
    z-index:2;
}

.feature{

    display:flex;
    align-items:center;

    gap:15px;

    margin-bottom:18px;

    color:#E2E8F0;
}

.feature i{

    color:var(--primary);

    font-size:20px;
}

/* ==========================
   PANEL KANAN
========================== */

.right-side{

    width:55%;

    display:flex;
    justify-content:center;
    align-items:center;

    padding:40px;
}

.login-card{

    width:100%;
    max-width:500px;

    background:white;

    border-radius:24px;

    padding:40px;

    box-shadow:
    0 10px 35px rgba(15,23,42,.08);
}

.login-header{

    text-align:center;
    margin-bottom:35px;
}

.login-header img{

    width:80px;
    margin-bottom:15px;
}

.login-header h2{

    font-weight:700;
    color:var(--text);
}

.login-header p{

    color:var(--text-muted);
}

.form-label{

    font-weight:600;
    color:var(--text);

    margin-bottom:8px;
}

.form-control,
.form-select{

    border:1px solid var(--border);

    border-radius:14px;

    padding:13px 15px;

    height:52px;
}

.form-control:focus,
.form-select:focus{

    border-color:var(--primary);

    box-shadow:
    0 0 0 4px rgba(0,162,233,.15);
}

.input-group-text{

    border-radius:14px 0 0 14px;

    background:white;
}

.password-btn{

    border:1px solid var(--border);
    border-left:none;

    background:white;

    border-radius:0 14px 14px 0;
}

.btn-login{

    width:100%;

    height:55px;

    border:none;

    border-radius:14px;

    background:linear-gradient(
        135deg,
        var(--primary),
        #3B82F6
    );

    color:white;

    font-weight:700;

    margin-top:10px;

    transition:.3s;
}

.btn-login:hover{

    transform:translateY(-2px);

    box-shadow:
    0 10px 25px rgba(0,162,233,.25);
}

.footer-login{

    text-align:center;

    margin-top:25px;

    color:var(--text-muted);

    font-size:13px;
}

/* ==========================
   MOBILE
========================== */

@media(max-width:992px){

    body{
        flex-direction:column;
    }

    .left-side{
        width:100%;
        padding:40px;
    }

    .right-side{
        width:100%;
    }

    .welcome h1{
        font-size:32px;
    }

    .feature-list{
        display:none;
    }
}

</style>
</head>

<body>

<div class="left-side">

    <div class="logo-box">

        <div class="logo-icon">
            <i class="bi bi-grid"></i>
        </div>

        <div class="logo-text">
            <h3>KOPERASI BPS</h3>
        </div>

    </div>

    <div class="welcome">

        <h1>Selamat Datang 👋</h1>

        <p>
            Aplikasi Koperasi Pegawai
            Badan Pusat Statistik Provinsi
            Sumatera Selatan Berbasisi web untuk pengajuan pinjaman, 
            pengelolaan simpanan, pinjaman, dan transaksi
            koperasi secara realtime.
        </p>

    </div>

    <div class="feature-list">

        <div class="feature">
            <i class="bi bi-bank"></i>
            <span>Manajemen Simpanan Anggota</span>
        </div>

        <div class="feature">
            <i class="bi bi-cash-stack"></i>
            <span>Pengajuan Pinjaman Online</span>
        </div>

        <div class="feature">
            <i class="bi bi-shield-check"></i>
            <span>Keamanan Data Terjamin</span>
        </div>

        <div class="feature">
            <i class="bi bi-graph-up-arrow"></i>
            <span>Laporan dan Monitoring Real-Time</span>
        </div>

    </div>

</div>

<div class="right-side">

    <div class="login-card">

        <div class="login-header">

            <img src="<?= base_url('logo_bps.png') ?>" alt="Logo BPS">

            <h2>Login Portal</h2>

            <p>Masuk sesuai hak akses pengguna</p>

        </div>

        <?php if (session()->getFlashdata('error')) : ?>
            <div class="alert alert-danger">
                <?= session()->getFlashdata('error') ?>
            </div>
        <?php endif; ?>

        <form action="<?= base_url('auth/login_action') ?>" method="post">

            <?= csrf_field() ?>

            <div class="mb-3">

                <label class="form-label">
                    Role Pengguna
                </label>

                <select name="role" class="form-select">

                    <option value="anggota">
                        Anggota Koperasi
                    </option>

                    <option value="ketua_kop">
                        Pimpinan Koperasi
                    </option>

                    <option value="bendahara_kop">
                        Bendahara Koperasi
                    </option>

                    <option value="bendahara_kan">
                        Bendahara Kantor
                    </option>

                </select>

            </div>

            <div class="mb-3">

                <label class="form-label">
                    Username
                </label>

                <input
                    type="text"
                    name="username"
                    class="form-control"
                    placeholder="Masukkan Username">

            </div>

            <div class="mb-4">

                <label class="form-label">
                    Password
                </label>

                <div class="input-group">

                    <input
                        type="password"
                        id="password"
                        name="password"
                        class="form-control"
                        placeholder="Masukkan Password">

                    <button
                        type="button"
                        class="password-btn px-3"
                        onclick="togglePassword()">

                        <i id="eye" class="bi bi-eye"></i>

                    </button>

                </div>

            </div>

            <button
                type="submit"
                class="btn-login">

                <i class="bi bi-box-arrow-in-right me-2"></i>
                LOGIN

            </button>

        </form>

        <div class="footer-login">

            © 2026 Koperasi Pegawai BPS Sumatera Selatan

        </div>

    </div>

</div>

<script>

function togglePassword(){

    let pass =
    document.getElementById("password");

    let eye =
    document.getElementById("eye");

    if(pass.type==="password"){

        pass.type="text";
        eye.className="bi bi-eye-slash";

    }else{

        pass.type="password";
        eye.className="bi bi-eye";
    }
}

</script>

</body>
</html>