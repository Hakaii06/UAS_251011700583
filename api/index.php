<?php
session_start();
if (!isset($_SESSION['login'])) { header("Location: login.php"); exit; }
include 'config.php';

if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    $get_img = mysqli_query($conn, "SELECT gambar FROM produk WHERE id = $id");
    $img_data = mysqli_fetch_assoc($get_img);
    if ($img_data && file_exists("uploads/" . $img_data['gambar'])) {
        unlink("uploads/" . $img_data['gambar']);
    }
    mysqli_query($conn, "DELETE FROM produk WHERE id = $id");
    header("Location: index.php");
    exit;
}
$result = mysqli_query($conn, "SELECT * FROM produk ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - UMKM Hub</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root { --bg-primary: #f8fafc; --sidebar-bg: #0f172a; --accent-color: #4f46e5; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: var(--bg-primary); color: #1e293b; }
        .top-navbar { background: rgba(255, 255, 255, 0.8); backdrop-filter: blur(10px); border-bottom: 1px solid #e2e8f0; padding: 15px 30px; position: sticky; top: 0; z-index: 100; }
        .sidebar { background-color: var(--sidebar-bg); min-height: calc(100vh - 62px); padding: 25px 15px; }
        .sidebar-title { color: #64748b; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 1px; padding-left: 15px; margin-bottom: 15px; font-weight: 700; }
        .sidebar a { color: #94a3b8; text-decoration: none; display: flex; align-items: center; padding: 12px 15px; border-radius: 10px; font-weight: 500; margin-bottom: 8px; transition: all 0.3s; }
        .sidebar a i { margin-right: 12px; font-size: 1.1rem; }
        .sidebar a:hover, .sidebar a.active { background-color: var(--accent-color); color: white; box-shadow: 0 10px 15px -3px rgba(79, 70, 229, 0.4); transform: translateX(4px); }
        .sidebar a.btn-logout:hover { background-color: #ef4444; box-shadow: 0 10px 15px -3px rgba(239, 68, 68, 0.4); }
        .main-content { padding: 40px; }
        .data-card { background: #ffffff; border: none; border-radius: 16px; box-shadow: 0 4px 15px rgba(0,0,0,0.03); padding: 24px; }
        .btn-custom-add { background-color: var(--accent-color); color: white; border-radius: 10px; font-weight: 600; box-shadow: 0 4px 14px rgba(79, 70, 229, 0.3); transition: all 0.2s; }
        .btn-custom-add:hover { background-color: #4338ca; color: white; transform: translateY(-2px); }
        .btn-custom-pdf { background-color: #0ea5e9; color: white; border-radius: 10px; font-weight: 600; box-shadow: 0 4px 14px rgba(14, 165, 233, 0.3); transition: all 0.2s; }
        .btn-custom-pdf:hover { background-color: #0284c7; color: white; transform: translateY(-2px); }
        .table tbody tr { transition: all 0.2s; }
        .table tbody tr:hover { transform: scale(1.005); background-color: #f8fafc; }
        .badge-kategori { background-color: #e0e7ff; color: #4338ca; padding: 6px 12px; border-radius: 20px; font-weight: 600; font-size: 0.75rem; }
        .img-product { border-radius: 8px; object-fit: cover; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
    </style>
</head>
<body>

<div class="top-navbar d-flex justify-content-between align-items-center">
    <div class="fw-bold text-primary fs-5"><i class="fa-solid fa-layer-group me-2"></i>UMKM Hub Portal</div>
    <div class="bg-light px-3 py-1 rounded-pill small text-secondary">
        <i class="fa-regular fa-user me-2 text-primary"></i>Admin: <strong class="text-dark"><?= $_SESSION['username']; ?></strong>
    </div>
</div>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-2 px-0 sidebar">
            <div class="sidebar-title">Menu Navigasi</div>
            <a href="index.php" class="active"><i class="fa-solid fa-box"></i> Produk UMKM</a>
            <div class="sidebar-title mt-4">Sesi</div>
            <a href="logout.php" class="btn-logout" onclick="return confirm('Apakah Anda ingin keluar?')"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>
        </div>

        <div class="col-md-10 main-content">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h3 class="fw-bold mb-0">Manajemen Data Entri</h3>
                    <p class="text-muted small mb-0">Kelola informasi katalog produk unit usaha UMKM binaan.</p>
                </div>
                <div>
                    <a href="tambah.php" class="btn btn-custom-add py-2 px-3 me-2"><i class="fa-solid fa-plus me-2"></i>Tambah Produk</a>
                    <a href="cetak_pdf.php" target="_blank" class="btn btn-custom-pdf py-2 px-3"><i class="fa-solid fa-file-pdf me-2"></i>Cetak PDF</a>
                </div>
            </div>

            <div class="data-card">
                <div class="table-responsive">
                    <table class="table align-middle text-center table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Preview</th>
                                <th>Nama Produk</th>
                                <th>Kategori</th>
                                <th>Harga Jual</th>
                                <th>Stok Sisa</th>
                                <th>Nama UMKM</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(mysqli_num_rows($result) == 0): ?>
                                <tr><td colspan="8" class="text-muted py-4">Data entri kosong. Silakan tambahkan data produk baru!</td></tr>
                            <?php endif; ?>
                            <?php $no = 1; while ($row = mysqli_fetch_assoc($result)) : ?>
                            <tr>
                                <td class="fw-bold text-secondary"><?= $no++; ?></td>
                                <td><img src="uploads/<?= $row['gambar']; ?>" width="50" height="50" class="img-product"></td>
                                <td class="fw-semibold text-dark"><?= $row['nama_produk']; ?></td>
                                <td><span class="badge-kategori"><?= $row['kategori']; ?></span></td>
                                <td class="fw-bold text-success">Rp <?= number_format($row['harga'], 0, ',', '.'); ?></td>
                                <td class="text-secondary"><?= $row['stok']; ?> Pcs</td>
                                <td class="text-muted small"><?= $row['nama_umkm']; ?></td>
                                <td>
                                    <a href="edit.php?id=<?= $row['id']; ?>" class="btn btn-sm btn-outline-warning rounded-pill px-3 me-1"><i class="fa-regular fa-pen-to-square me-1"></i>Edit</a>
                                    <a href="index.php?hapus=<?= $row['id']; ?>" class="btn btn-sm btn-outline-danger rounded-pill px-3" onclick="return confirm('Hapus produk ini?')"><i class="fa-regular fa-trash-can me-1"></i>Hapus</a>
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
