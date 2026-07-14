<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// =======================================================================================
// 1. JALUR UTAMA (LANDING & AUTHENTICATION)
// =======================================================================================
$routes->get('/', 'Auth::login'); 
$routes->get('login', 'Auth::login');
$routes->post('auth/login_action', 'Auth::login_action');
$routes->get('logout', 'Auth::logout');
$routes->get('auth/reset', 'Auth::reset');


// =======================================================================================
// 2. GROUP: BENDAHARA KOPERASI (PROSES SIMPAN PINJAM & MANAJEMEN DSS SAW)
// Proteksi Filter: auth dengan hak akses bendahara_kop
// =======================================================================================
$routes->group('bendahara', ['namespace' => 'App\Controllers\BendaharaKoperasi', 'filter' => 'auth:bendahara_kop'], function($routes) {
    
    $routes->get('dashbord', 'Dashbord::index');

    // Modul Perhitungan SPK Metode SAW Pengajuan Pinjaman
    $routes->get('pengajuan', 'Pengajuan::index');
    $routes->get('pengajuan/riwayat', 'Pengajuan::riwayat');
    $routes->get('pengajuan/proses_ke_saw/(:num)', 'Pengajuan::proses_ke_saw/$1');
    $routes->get('pengajuan/review/(:num)', 'Pengajuan::review/$1');
    $routes->post('pengajuan/simpan_review', 'Pengajuan::simpan_review');
    $routes->get('pengajuan/finalisasi/(:num)/(:any)', 'Pengajuan::finalisasi/$1/$2');
    $routes->post('pengajuan/transfer_pencairan', 'Pengajuan::transfer_pencairan');
    $routes->get('pengajuan/delete/(:num)', 'Pengajuan::delete/$1');
    $routes->get('pengajuan/tambah', 'Pengajuan::tambah');
    $routes->post('pengajuan/simpan', 'Pengajuan::simpan');

    // Modul Data Riil Manajemen Simpanan Tabungan Anggota
    $routes->get('simpanan', 'Simpanan::index');
    $routes->get('simpanan/detail/(:num)', 'Simpanan::detail/$1');
    $routes->post('simpanan/store', 'Simpanan::store');
    $routes->post('simpanan/update_transaksi', 'Simpanan::update_transaksi');
    $routes->get('simpanan/delete_transaksi/(:any)', 'Simpanan::delete_transaksi/$1');
    $routes->post('simpanan/import_excel_simpanan', 'Simpanan::import_excel_simpanan');
    $routes->get('simpanan/export_excel_simpanan', 'Simpanan::export_excel_simpanan');

    // Modul Histori Angsuran Hutang Berjalan Pegawai
    $routes->get('pinjaman', 'Pinjaman::index'); 
    $routes->get('pinjaman/histori/(:num)', 'Pinjaman::histori/$1'); 
    $routes->post('pinjaman/store_pinjaman', 'Pinjaman::store_pinjaman'); 
    $routes->post('pinjaman/store_angsuran', 'Pinjaman::store_angsuran'); 
    $routes->get('pinjaman/delete_angsuran/(:num)', 'Pinjaman::delete_angsuran/$1'); 
    $routes->post('pinjaman/update_angsuran', 'Pinjaman::update_angsuran'); 
    $routes->get('pinjaman/export_excel_pinjaman', 'Pinjaman::export_excel_pinjaman');

    // Modul Sinkronisasi Payroll Gaji Kantor Kantor BPS
    $routes->get('potongan', 'TabelPengajuanPotonganGaji::index');
    $routes->get('potongan/generate', 'TabelPengajuanPotonganGaji::generate');
    $routes->get('potongan/ajukan_semua', 'TabelPengajuanPotonganGaji::ajukan_semua');
    $routes->post('potongan/ajukan_ke_kantor', 'TabelPengajuanPotonganGaji::ajukan_ke_kantor');

    // Modul Pengeluaran Finansial & Neraca Koperasi
    $routes->get('neraca', 'Neraca::index');
    $routes->get('pengeluaran', 'Pengeluaran::index');
    $routes->post('pengeluaran/store', 'Pengeluaran::store');
    $routes->get('pengeluaran/delete/(:num)', 'Pengeluaran::delete/$1');
    $routes->get('pengeluaran/export_excel_pengeluaran', 'Pengeluaran::export_excel_pengeluaran');
    $routes->get('neraca/export_excel_neraca', 'Neraca::export_excel_neraca');

    // FITUR INTEGRASI BERJENJANG: Meja Kerja Verifikasi & Transfer Penarikan Dana
    $routes->get('konfirmasi_penarikan', 'KonfirmasiPenarikan::index');
    $routes->get('konfirmasi_penarikan/proses/(:num)/(:any)', 'KonfirmasiPenarikan::proses/$1/$2');
    $routes->post('konfirmasi_penarikan/proses/(:num)/(:any)', 'KonfirmasiPenarikan::proses/$1/$2');

    // Modul Data Master Akun Log Sistem
    $routes->get('akun', 'Akun::index');
    $routes->post('akun/simpan', 'Akun::simpan');
    $routes->post('akun/update', 'Akun::update');
    $routes->post('akun/hapus/(:num)', 'Akun::hapus/$1');
});


// =======================================================================================
// 3. GROUP KETUA KOPERASI (PIMPINAN - OTORITAS VETO PINJAMAN & PENARIKAN)
// Proteksi Filter: auth dengan hak akses ketua_kop
// =======================================================================================
$routes->group('ketua', ['namespace' => 'App\Controllers\KetuaKoperasi', 'filter' => 'auth:ketua_kop'], function($routes) {
    // [MENU UTAMA]
    $routes->get('dashboard', 'Dashboard::index');
    $routes->get('pengajuan', 'Pengajuan::index');
    $routes->get('penarikan', 'Pengajuan::penarikan');

    // [INTERSEPTOR NAVIGASI] - Menggunakan prefix 'fitur/' untuk menghindari bentrok segment 'dashboard' bawaan
    $routes->get('fitur/buka_semua_pinjaman', 'Dashboard::buka_semua_pinjaman');
    $routes->get('fitur/buka_semua_penarikan', 'Dashboard::buka_semua_penarikan');
    $routes->get('fitur/buka_pinjaman/(:num)', 'Dashboard::buka_pinjaman/$1');
    $routes->get('fitur/buka_penarikan/(:num)', 'Dashboard::buka_penarikan/$1');
    
    // [ACTION VOTE & FINALISASI INDUK]
    $routes->post('pengajuan/finalisasi/(:num)/(:any)', 'Pengajuan::finalisasi/$1/$2');
    $routes->post('pengajuan/vote_pinjaman/(:num)/(:any)', 'Pengajuan::vote_pinjaman/$1/$2');
    $routes->post('pengajuan/vote_penarikan/(:num)/(:any)', 'Pengajuan::vote_penarikan/$1/$2');
    $routes->post('penarikan/setujui/(:num)', 'Pengajuan::setujui_penarikan/$1');
    $routes->post('penarikan/tolak/(:num)', 'Pengajuan::tolak_penarikan/$1');
});


// =======================================================================================
// 4. GROUP: BENDAHARA KANTOR BPS (OTORITAS VERIFIKASI AUTO-DEBIT PAYROLL)
// Proteksi Filter: auth dengan hak akses bendahara_kan
// =======================================================================================
$routes->group('kantor', ['namespace' => 'App\Controllers\BendaharaKantor', 'filter' => 'auth:bendahara_kan'], function($routes) {
    // Menu Utama & Export Data
    $routes->get('dashbord', 'Dashbord::index');
    $routes->get('dashbord/tabel', 'Dashbord::tabel');
    $routes->get('dashbord/export_excel', 'Dashbord::export_excel');
    $routes->get('dashbord/export_pdf', 'Dashbord::export_pdf');
    $routes->get('dashbord/riwayat', 'LogRiwayat::index'); 
    
    // Antrean Tabel Utama Verifikasi Kantor
    $routes->get('verifikasi_potongan', 'Dashbord::tabel');

    // 🟢 TAMBAHAN UTAMA: Route Eksekusi Serentak Massal (Wajib POST & ditaruh di atas rute dinamis)
    $routes->post('dashbord/validasi_serentak', 'Dashbord::validasi_serentak');

    // Filter Otoritas Tunggal (Data Lama / Cadangan)
    $routes->get('dashbord/validasi/(:num)/(:any)', 'Dashbord::validasi/$1/$2');
    $routes->get('verifikasi_potongan/setujui/(:num)/(:any)', 'Dashbord::validasi/$1/$2');
});


// =======================================================================================
// 5. GROUP: USER ANGGOTA KOPERASI BPS (HAK AKSES MENGAJUKAN DANA & CEK SALDO PRIBADI)
// Proteksi Filter: auth dengan hak akses anggota
// =======================================================================================
$routes->group('anggota', ['namespace' => 'App\Controllers\AnggotaKoperasi', 'filter' => 'auth:anggota'], function($routes) {
    $routes->get('dashbord', 'Dashbord::index');
    
    $routes->get('profil', 'Profil::index');
    $routes->post('profil/update', 'Profil::update');
    $routes->post('profil/update_password', 'Profil::update_password');
    
    $routes->get('simpanan', 'Simpanan::index');
    $routes->get('pinjaman', 'Pinjaman::index');
    
    $routes->get('pengajuan', 'Pengajuan::index'); 
    $routes->post('pengajuan/simpan', 'Pengajuan::simpan'); 

    // Modul Pengajuan Form Mandiri Penarikan Sisi Anggota
    $routes->get('penarikan', 'Penarikan::index'); 
    $routes->post('penarikan/simpan', 'Penarikan::simpan'); 
});


// =======================================================================================
// 6. BACKUP GROUP PREFIX URL 'koperasi/konfirmasi_penarikan' SISI BENDAHARA
// =======================================================================================
$routes->group('koperasi', ['namespace' => 'App\Controllers\BendaharaKoperasi', 'filter' => 'auth:bendahara_kop'], function ($routes) {
    $routes->get('konfirmasi_penarikan', 'KonfirmasiPenarikan::index');
    $routes->get('konfirmasi_penarikan/proses/(:num)/(:any)', 'KonfirmasiPenarikan::proses/$1/$2');
    $routes->post('konfirmasi_penarikan/proses/(:num)/(:any)', 'KonfirmasiPenarikan::proses/$1/$2');
});