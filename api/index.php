<?php
// Jalur absolut ke config.php
include dirname(__DIR__) . '/config.php';

// PROTEKSI HALAMAN: Cek apakah Cookie login TIDAK ADA atau TIDAK VALID
if (!isset($_COOKIE['login_user']) || $_COOKIE['login_user'] !== 'aktif') { 
    header("Location: /login"); 
    exit; 
}

// Ambil data produk terbaru
$result = mysqli_query($conn, "SELECT * FROM produk ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - UMKM Hub Portal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f8fafc; }
        .sidebar { background-color: #1e293b; min-height: 100vh; color: #cbd5e1; }
        .sidebar .nav-link { color: #94a3b8; font-weight: 500; border-radius: 8px; margin-bottom: 5px; }
        .sidebar .nav-link:hover { background-color: rgba(255,255,255,0.05); color: #fff; }
        .sidebar .nav-link.active { background-color: #4f46e5; color: #fff; }
        .main-card { background: white; border-radius: 16px; box-shadow: 0 4px 20px rgba(0,0,0,0.02); border: none; }
        .table img { object-fit: cover; border-radius: 8px; }
    </style>
</head>
<body>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-3 col-lg-2 sidebar p-4 d-flex flex-column justify-content-between">
            <div>
                <div class="d-flex align-items-center gap-2 mb-4">
                    <i class="bi bi-box-seam-fill text-primary fs-3"></i>
                    <h5 class="fw-bold text-white mb-0">UMKM Hub Portal</h5>
                </div>
                
                <p class="text-uppercase text-muted small fw-bold tracking-wider mb-2">Menu Navigasi</p>
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link active" href="/index">
                            <i class="bi bi-grid-1x2-fill me-2"></i> Produk UMKM
                        </a>
                    </li>
                </ul>

                <p class="text-uppercase text-muted small fw-bold tracking-wider mt-4 mb-2">Sesi</p>
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link text-danger" href="/logout">
                            <i class="bi bi-box-arrow-left me-2"></i> Logout
                        </a>
                    </li>
                </ul>
            </div>
            
            <div class="text-muted small">
                &copy; <?= date('Y'); ?> UMKM Hub
            </div>
        </div>

        <div class="col-md-9 col-lg-10 p-5">
            <div class="d-flex justify-content-between align-items-center mb-5">
                <div>
                    <h3 class="fw-bold text-dark mb-1">Manajemen Data Entri</h3>
                    <p class="text-muted mb-0">Kelola informasi katalog produk unit usaha UMKM binaan.</p>
                </div>
                <div class="d-flex align-items-center gap-3">
                    <div class="text-end d-none d-sm-block">
                        <small class="text-muted d-block">Masuk sebagai:</small>
                        <span class="fw-semibold text-dark"><i class="bi bi-person-circle me-1"></i> Admin</span>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-end gap-2 mb-4">
                <a href="/tambah" class="btn btn-primary px-3 py-2 rounded-3 fw-semibold shadow-sm">
                    <i class="bi bi-plus-lg me-1"></i> Tambah Produk
                </a>
                <a href="/cetak_pdf" target="_blank" class="btn btn-info text-white px-3 py-2 rounded-3 fw-semibold shadow-sm">
                    <i class="bi bi-file-earmark-pdf me-1"></i> Cetak PDF
                </a>
            </div>

            <div class="card main-card p-4">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light text-secondary small text-uppercase">
                            <tr>
                                <th width="50">No</th>
                                <th width="80">Preview</th>
                                <th>Nama Produk</th>
                                <th>Kategori</th>
                                <th>Harga Jual</th>
                                <th>Stok Sisa</th>
                                <th>Nama UMKM</th>
                                <th width="150" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="small">
                            <?php $no = 1; while($row = mysqli_fetch_assoc($result)): ?>
                            <tr>
                                <td class="fw-medium text-secondary"><?= $no++; ?></td>
                                <td>
                                    <?php if(!empty($row['gambar'])): ?>
                                        <img src="/baca_gambar?file=<?= $row['gambar']; ?>" width="50" height="50" alt="Produk">
                                    <?php else: ?>
                                        <div class="bg-light rounded d-flex align-items-center justify-content-center" style="width:50px; height:50px;">
                                            <i class="bi bi-image text-muted"></i>
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td class="fw-semibold text-dark"><?= htmlspecialchars($row['nama_produk']); ?></td>
                                <td>
                                    <span class="badge bg-light text-primary border border-primary-subtle px-2 py-1">
                                        <?= htmlspecialchars($row['kategori']); ?>
                                    </span>
                                </td>
                                <td class="fw-bold text-success">Rp <?= number_format($row['harga'], 0, ',', '.'); ?></td>
                                <td><?= $row['stok']; ?> Pcs</td>
                                <td class="text-secondary"><?= htmlspecialchars($row['nama_umkm']); ?></td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-1">
                                        <a href="/edit?id=<?= $row['id']; ?>" class="btn btn-sm btn-outline-warning fw-medium px-2 py-1">
                                            <i class="bi bi-pencil-square"></i> Edit
                                        </a>
                                        <a href="/hapus?id=<?= $row['id']; ?>" onclick="return confirm('Apakah Anda yakin ingin menghapus produk ini?');" class="btn btn-sm btn-outline-danger fw-medium px-2 py-1">
                                            <i class="bi bi-trash"></i> Hapus
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <?php endwhile; if(mysqli_num_rows($result) == 0): ?>
                            <tr>
                                <td colspan="8" class="text-center text-muted py-5">
                                    <i class="bi bi-box-open fs-2 d-block mb-2"></i>
                                    Belum ada data produk komoditas.
                                </td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
