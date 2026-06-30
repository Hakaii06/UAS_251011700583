<?php
include dirname(__DIR__) . '/config.php';

// Proteksi halaman dashboard
if (!isset($_COOKIE['login_user']) || $_COOKIE['login_user'] !== 'aktif') { 
    header("Location: /login"); 
    exit; 
}

$result = mysqli_query($conn, "SELECT * FROM produk ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - UMKM Hub Portal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f8fafc; }
        .sidebar { background-color: #1e293b; min-height: 100vh; color: #cbd5e1; }
        .sidebar .nav-link { color: #94a3b8; font-weight: 500; border-radius: 8px; margin-bottom: 5px; }
        .sidebar .nav-link.active { background-color: #4f46e5; color: #fff; }
        .main-card { background: white; border-radius: 16px; border: none; }
    </style>
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-3 col-lg-2 sidebar p-4 d-flex flex-column justify-content-between">
            <div>
                <div class="d-flex align-items-center gap-2 mb-4">
                    <i class="bi bi-box-seam-fill text-primary fs-3"></i>
                    <h5 class="fw-bold text-white mb-0">UMKM Hub</h5>
                </div>
                <ul class="nav flex-column">
                    <li class="nav-item"><a class="nav-link active" href="/index"><i class="bi bi-grid-1x2-fill me-2"></i> Produk UMKM</a></li>
                    <li class="nav-item"><a class="nav-link text-danger" href="/logout"><i class="bi bi-box-arrow-left me-2"></i> Logout</a></li>
                </ul>
            </div>
            <div class="text-muted small">&copy; <?= date('Y'); ?> UMKM Hub</div>
        </div>

        <div class="col-md-9 col-lg-10 p-5">
            <div class="d-flex justify-content-between align-items-center mb-5">
                <div>
                    <h3 class="fw-bold text-dark mb-1">Manajemen Data Entri</h3>
                    <p class="text-muted mb-0">Kelola informasi katalog produk unit usaha UMKM binaan.</p>
                </div>
            </div>

            <div class="d-flex justify-content-end gap-2 mb-4">
                <a href="/tambah" class="btn btn-primary fw-semibold"><i class="bi bi-plus-lg me-1"></i> Tambah Produk</a>
                <a href="/cetak_pdf" target="_blank" class="btn btn-info text-white fw-semibold"><i class="bi bi-file-earmark-pdf me-1"></i> Cetak PDF</a>
            </div>

            <div class="card main-card p-4 shadow-sm">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light text-secondary small text-uppercase">
                            <tr>
                                <th>No</th>
                                <th>Preview</th>
                                <th>Nama Produk</th>
                                <th>Kategori</th>
                                <th>Harga Jual</th>
                                <th>Stok</th>
                                <th>Nama UMKM</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="small">
                            <?php $no = 1; while($row = mysqli_fetch_assoc($result)): ?>
                            <tr>
                                <td><?= $no++; ?></td>
                                <td>
                                    <?php if(!empty($row['gambar'])): ?>
                                        <img src="/api/baca_gambar?file=<?= urlencode($row['gambar']); ?>" width="55" height="55" style="object-fit: cover; border-radius: 12px;" class="border shadow-sm" alt="Preview">
                                    <?php else: ?>
                                        <div class="bg-light rounded-3 d-flex align-items-center justify-content-center border" style="width:55px; height:55px;">
                                            <i class="bi bi-image text-muted" style="font-size: 1.2rem;"></i>
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td class="fw-semibold text-dark"><?= htmlspecialchars($row['nama_produk']); ?></td>
                                <td><span class="badge bg-light text-primary border px-2 py-1.5"><?= htmlspecialchars($row['kategori']); ?></span></td>
                                <td class="fw-bold text-success">Rp <?= number_format($row['harga'], 0, ',', '.'); ?></td>
                                <td><span class="fw-medium"><?= $row['stok']; ?></span> Pcs</td>
                                <td><?= htmlspecialchars($row['nama_umkm']); ?></td>
                                <td class="text-center">
                                    <a href="/edit?id=<?= $row['id']; ?>" class="btn btn-sm btn-outline-warning fw-medium rounded-2">Edit</a>
                                    <a href="/hapus?id=<?= $row['id']; ?>" onclick="return confirm('Yakin hapus produk ini?');" class="btn btn-sm btn-outline-danger rounded-2">Hapus</a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
