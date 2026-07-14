<?= $this->extend('layout/v_anggota_layout') ?>
<?= $this->section('content') ?>

<style>
    /* Aura Finansial Premium BPS */
    .total-banner-card { border: none; border-radius: 20px; background: linear-gradient(135deg, #0b1a30 0%, #0066ff 100%); }
    
    /* Indikator Klik & Desain Kartu Kategori */
    .clickable-card { border: 2px solid #e2e8f0; border-radius: 20px; background: #ffffff; padding: 24px; cursor: pointer; transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1); position: relative; }
    .clickable-card:hover { transform: translateY(-4px); box-shadow: 0 12px 24px rgba(11,26,48,0.06); }
    
    /* Keadaan Aktif Saat Kartu Dipilih (Warna Logo BPS Segmen) */
    .clickable-card.active-blue { border-color: #0066ff; background-color: #f0f7ff; box-shadow: 0 8px 20px rgba(0, 102, 255, 0.08); }
    .clickable-card.active-orange { border-color: #f97316; background-color: #fff7ed; box-shadow: 0 8px 20px rgba(249, 115, 22, 0.08); }
    .clickable-card.active-green { border-color: #10b981; background-color: #f0fdf4; box-shadow: 0 8px 20px rgba(16, 185, 129, 0.08); }

    /* Custom Table Style */
    .history-card-frame { border: none; border-radius: 20px; background: #ffffff; border: 1px solid #e2e8f0; overflow: hidden; }
    .table-premium-simpanan thead th { background-color: #f8fafc; color: #64748b; font-weight: 600; font-size: 0.75rem; letter-spacing: 0.5px; padding: 16px 24px; border-bottom: 1px solid #e2e8f0; }
    .table-premium-simpanan tbody td { padding: 16px 24px; border-bottom: 1px solid #f1f5f9; font-size: 0.88rem; color: #334155; }
    .table-premium-simpanan tbody tr:last-child td { border-bottom: none; }
    .table-premium-simpanan tbody tr { transition: all 0.15s ease; }
</style>

<div class="container-fluid p-0">
    <div class="mb-4">
        <h4 class="fw-bold text-dark mb-1">Rincian Simpanan Anggota</h4>
        <p class="text-muted small">Klik pada salah satu kategori kartu di bawah untuk menyaring riwayat transaksi mutasi secara spesifik.</p>
    </div>

    <div class="row mb-4">
        <div class="col-12">
            <div class="card total-banner-card text-white shadow-sm">
                <div class="card-body p-4 p-md-5">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h6 class="opacity-75 mb-2 fw-medium" style="letter-spacing: 0.5px;">TOTAL AKUMULASI SALDO SIMPANAN</h6>
                            <h1 class="fw-bold mb-0 display-5" style="letter-spacing: -1px;">Rp <?= number_format($total, 0, ',', '.') ?></h1>
                        </div>
                        <div class="col-md-4 text-md-end d-none d-md-block">
                            <i class="bi bi-wallet2" style="font-size: 4.5rem; opacity: 0.15;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-5">
        <div class="col-md-4">
            <div class="clickable-card h-100" id="card-pokok" onclick="filterSimpanan('pokok', 'blue')">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div class="d-flex align-items-center">
                        <div class="bg-primary bg-opacity-10 p-2.5 rounded-3 me-3 text-primary">
                            <i class="bi bi-piggy-bank-fill fs-4"></i>
                        </div>
                        <h6 class="fw-bold mb-0 text-dark">Simpanan Pokok</h6>
                    </div>
                    <i class="bi bi-chevron-right text-muted small nav-chevron"></i>
                </div>
                <h3 class="fw-bold text-dark mb-1">Rp <?= number_format($simpanan['simpanan_pokok'], 0, ',', '.') ?></h3>
                <p class="text-muted small mb-0">Iuran pertama pendaftaran anggota.</p>
            </div>
        </div>

        <div class="col-md-4">
            <div class="clickable-card h-100" id="card-wajib" onclick="filterSimpanan('wajib', 'orange')">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div class="d-flex align-items-center">
                        <div class="bg-warning bg-opacity-10 p-2.5 rounded-3 me-3 text-warning" style="color: #f97316 !important; background-color: rgba(249, 115, 22, 0.1) !important;">
                            <i class="bi bi-calendar-check-fill fs-4"></i>
                        </div>
                        <h6 class="fw-bold mb-0 text-dark">Simpanan Wajib</h6>
                    </div>
                    <i class="bi bi-chevron-right text-muted small nav-chevron"></i>
                </div>
                <h3 class="fw-bold text-dark mb-1">Rp <?= number_format($simpanan['simpanan_wajib'], 0, ',', '.') ?></h3>
                <p class="text-muted small mb-0">Iuran rutin bulanan wajib pegawai.</p>
            </div>
        </div>

        <div class="col-md-4">
            <div class="clickable-card h-100" id="card-sukarela" onclick="filterSimpanan('sukarela', 'green')">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div class="d-flex align-items-center">
                        <div class="bg-success bg-opacity-10 p-2.5 rounded-3 me-3 text-success">
                            <i class="bi bi-cash-coin fs-4"></i>
                        </div>
                        <h6 class="fw-bold mb-0 text-dark">Simpanan Sukarela</h6>
                    </div>
                    <i class="bi bi-chevron-right text-muted small nav-chevron"></i>
                </div>
                <h3 class="fw-bold text-dark mb-1">Rp <?= number_format($simpanan['simpanan_sukarela'], 0, ',', '.') ?></h3>
                <p class="text-muted small mb-0">Simpanan cadangan sukarela anggota.</p>
            </div>
        </div>
    </div>

    <div class="card history-card-frame shadow-sm mb-4">
        <div class="p-4 border-bottom bg-white d-flex justify-content-between align-items-center">
            <div>
                <h6 class="fw-bold text-dark mb-1"><i class="bi bi-clock-history me-2 text-primary"></i>Riwayat Transaksi Buku Simpanan</h6>
                <p class="text-muted small mb-0">Menampilkan mutasi saldo Anda saat ini untuk kategori: <span class="fw-bold text-primary" id="text-kategori-aktif">Semua Simpanan</span></p>
            </div>
            <button class="btn btn-light btn-sm rounded-pill px-3 border fw-semibold text-secondary" id="btn-reset-filter" onclick="resetFilter()" style="display: none;">
                <i class="bi bi-arrow-clockwise me-1"></i> Lihat Semua
            </button>
        </div>
        
        <div class="table-responsive">
            <table class="table table-premium-simpanan align-middle mb-0" id="tableMutasi">
                <thead>
                    <tr class="text-uppercase">
                        <th>Tanggal Transaksi</th>
                        <th>Keterangan Mutasi</th>
                        <th>Kategori Simpanan</th>
                        <th class="text-end">Nominal Transaksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($history)) : foreach ($history as $h) : 
                        // Trik Cerdas Proteksi: Jika 'jenis_transaksi' tidak ada, kita deteksi dari string 'keterangan'
                        $jenis_raw = $h['jenis_transaksi'] ?? $h['keterangan'] ?? '';
                        
                        // Menentukan kategori bersih untuk filter JavaScript
                        $jenis_clean = 'sukarela'; // Fallback default
                        if (strpos(strtolower($jenis_raw), 'pokok') !== false) {
                            $jenis_clean = 'pokok';
                        } elseif (strpos(strtolower($jenis_raw), 'wajib') !== false) {
                            $jenis_clean = 'wajib';
                        }
                    ?>
                        <tr data-kategori="<?= $jenis_clean ?>">
                            <td class="fw-semibold text-dark">
                                <div><?= date('d M Y', strtotime($h['tgl_transaksi'])) ?></div>
                                <small class="text-muted fw-normal" style="font-size: 0.72rem;"><?= date('H:i', strtotime($h['tgl_transaksi'])) ?> WIB</small>
                            </td>
                            
                            <td class="text-secondary small">
                                <?= esc($h['keterangan']) ?>
                            </td>
                            
                            <td>
                                <?php if ($jenis_clean == 'pokok') : ?>
                                    <span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-1.5 fw-semibold">Simpanan Pokok</span>
                                <?php elseif ($jenis_clean == 'wajib') : ?>
                                    <span class="badge bg-warning-subtle text-warning rounded-pill px-3 py-1.5 fw-semibold" style="color: #f97316 !important; background-color: rgba(249, 115, 22, 0.1) !important;">Simpanan Wajib</span>
                                <?php else : ?>
                                    <span class="badge bg-success-subtle text-success rounded-pill px-3 py-1.5 fw-semibold">Simpanan Sukarela</span>
                                <?php endif; ?>
                            </td>
                            
                            <td class="text-end fw-bold text-success">
                                + Rp <?= number_format($h['jumlah'], 0, ',', '.') ?>
                            </td>
                        </tr>
                    <?php endforeach; else : ?>
                        <tr id="row-empty-master">
                            <td colspan="4" class="text-center py-5 text-muted small italic">
                                <i class="bi bi-folder-x d-block fs-2 mb-2 text-secondary"></i>
                                Belum ada riwayat catatan mutasi simpanan untuk akun Anda.
                            </td>
                        </tr>
                    <?php endif; ?>
                    
                    <tr id="row-empty-filter" style="display: none;">
                        <td colspan="4" class="text-center py-5 text-muted small italic">
                            <i class="bi bi-filter-circle-x d-block fs-2 mb-2 text-secondary"></i>
                            Tidak ditemukan riwayat transaksi untuk kategori simpanan ini.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
function filterSimpanan(kategori, warnaTheme) {
    // 1. Bersihkan semua status aktif di kartu
    const cards = document.querySelectorAll('.clickable-card');
    cards.forEach(card => {
        card.classList.remove('active-blue', 'active-orange', 'active-green');
    });

    // 2. Tambah class aktif sesuai tema segmen warna logo BPS
    const selectedCard = document.getElementById('card-' + kategori);
    selectedCard.classList.add('active-' + warnaTheme);

    // 3. Ubah teks keterangan nama kategori aktif di atas tabel
    document.getElementById('text-kategori-aktif').innerText = 'Simpanan ' + kategori.charAt(0).toUpperCase() + kategori.slice(1);
    document.getElementById('btn-reset-filter').style.display = 'inline-block';

    // 4. Proses penyaringan baris tabel mutasi
    const rows = document.querySelectorAll('#tableMutasi tbody tr:not(#row-empty-master):not(#row-empty-filter)');
    let dataDitemukan = 0;

    rows.forEach(row => {
        const rowKategori = row.getAttribute('data-kategori');
        if (rowKategori && rowKategori.includes(kategori)) {
            row.style.display = '';
            dataDitemukan++;
        } else {
            row.style.display = 'none';
        }
    });

    // 5. Handling jika mutasi kategori tersebut kosong
    const emptyFilterRow = document.getElementById('row-empty-filter');
    if (dataDitemukan === 0) {
        if(emptyFilterRow) emptyFilterRow.style.display = '';
    } else {
        if(emptyFilterRow) emptyFilterRow.style.display = 'none';
    }
}

function resetFilter() {
    // 1. Bersihkan highlight warna di seluruh kartu
    const cards = document.querySelectorAll('.clickable-card');
    cards.forEach(card => {
        card.classList.remove('active-blue', 'active-orange', 'active-green');
    });

    // 2. Kembalikan teks judul default
    document.getElementById('text-kategori-aktif').innerText = 'Semua Simpanan';
    document.getElementById('btn-reset-filter').style.display = 'none';

    // 3. Tampilkan kembali semua baris tabel mutasi asli
    const rows = document.querySelectorAll('#tableMutasi tbody tr:not(#row-empty-master)');
    rows.forEach(row => {
        row.style.display = '';
    });

    // 4. Sembunyikan row warning filter kosong
    const emptyFilterRow = document.getElementById('row-empty-filter');
    if(emptyFilterRow) emptyFilterRow.style.display = 'none';
}
</script>

<?= $this->endSection() ?>