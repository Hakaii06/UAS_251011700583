<?php
include dirname(__DIR__) . '/config.php';

if (!isset($_COOKIE['login_user']) || $_COOKIE['login_user'] !== 'aktif') { 
    header("Location: /login"); 
    exit; 
}

$error = "";

if (isset($_POST['simpan'])) {
    $nama_produk = mysqli_real_escape_string($conn, $_POST['nama_produk']);
    $kategori    = mysqli_real_escape_string($conn, $_POST['kategori']);
    $harga       = (int)$_POST['harga'];
    $stok        = (int)$_POST['stok'];
    $nama_umkm   = mysqli_real_escape_string($conn, $_POST['nama_umkm']);
    $deskripsi   = mysqli_real_escape_string($conn, $_POST['deskripsi']);

    $nama_gambar = $_FILES['gambar']['name'];
    $ukuran_file = $_FILES['gambar']['size'];
    $error_file  = $_FILES['gambar']['error'];
    $tmp_name    = $_FILES['gambar']['tmp_name'];

    if ($error_file === 0) {
        $ekstensi_valid = ['jpg', 'jpeg', 'png', 'webp'];
        $ekstensi_gambar = explode('.', $nama_gambar);
        $ekstensi_gambar = strtolower(end($ekstensi_gambar));

        if (in_array($ekstensi_gambar, $ekstensi_valid)) {
            if ($ukuran_file <= 2000000) {
                $nama_file_baru = uniqid() . '.' . $ekstensi_gambar;
                // Target mengarah ke folder /uploads root proyek
                $target_upload = dirname(__DIR__) . '/uploads/' . $nama_file_baru;

                if (move_uploaded_file($tmp_name, $target_upload)) {
                    $query = "INSERT INTO produk (nama_produk, kategori, harga, stok, nama_umkm, deskripsi, gambar) 
                              VALUES ('$nama_produk', '$kategori', $harga, $stok, '$nama_umkm', '$deskripsi', '$nama_file_baru')";
                    if (mysqli_query($conn, $query)) {
                        header("Location: /index");
                        exit;
                    } else {
                        $error = "Gagal menyimpan data ke database: " . mysqli_error($conn);
                    }
                } else {
                    $error = "Gagal memindahkan file gambar ke folder uploads.";
                }
            } else {
                $error = "Ukuran gambar terlalu besar! Maksimal 2MB.";
            }
        } else {
            $error = "Format file tidak didukung! Pilih JPG, JPEG, PNG, atau WEBP.";
        }
    } else {
        $error = "Silakan pilih dan upload gambar produk terlebih dahulu.";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Produk - UMKM Hub Portal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f8fafc; } .form-card { background: white; border-radius: 16px; box-shadow: 0 4px 20px rgba(0,0,0,0.02); border: none; }</style>
</head>
<body>
<div class="container my-5" style="max-width: 700px;">
    <div class="mb-4"><a href="/index" class="btn btn-light text-secondary border fw-medium btn-sm rounded-2"><i class="bi bi-arrow-left me-1"></i> Kembali</a></div>
    <div class="mb-4">
        <h4 class="fw-bold text-dark mb-1">Tambah Komoditas Produk</h4>
        <p class="text-muted small">Lengkapi formulir untuk menambahkan produk UMKM baru.</p>
    </div>
    <?php if (!empty($error)) : ?><div class="alert alert-danger p-3 small rounded-3"><?= $error; ?></div><?php endif; ?>
    <div class="card form-card p-5">
        <form action="" method="POST" enctype="multipart/form-data">
            <div class="row">
                <div class="col-12 mb-3"><label class="form-label small fw-semibold text-secondary">Nama Produk</label><input type="text" name="nama_produk" class="form-control py-2" required autocomplete="off"></div>
                <div class="col-md-6 mb-3">
                    <label class="form-label small fw-semibold text-secondary">Kategori</label>
                    <select name="kategori" class="form-select py-2" required>
                        <option value="" disabled selected>Pilih Kategori</option>
                        <option value="Fashion">Fashion</option>
                        <option value="Kerajinan">Kerajinan</option>
                        <option value="Makanan">Makanan</option>
                        <option value="Minuman">Minuman</option>
                        <option value="Lainnya">Lainnya</option>
                    </select>
                </div>
                <div class="col-md-6 mb-3"><label class="form-label small fw-semibold text-secondary">Nama UMKM Produsen</label><input type="text" name="nama_umkm" class="form-control py-2" required autocomplete="off"></div>
                <div class="col-md-6 mb-3"><label class="form-label small fw-semibold text-secondary">Harga Jual (Rp)</label><input type="number" name="harga" class="form-control py-2" required></div>
                <div class="col-md-6 mb-3"><label class="form-label small fw-semibold text-secondary">Stok Sisa (Pcs)</label><input type="number" name="stok" class="form-control py-2" required></div>
                <div class="col-12 mb-3"><label class="form-label small fw-semibold text-secondary">Deskripsi Singkat</label><textarea name="deskripsi" class="form-control" rows="3" required></textarea></div>
                <div class="col-12 mb-4"><label class="form-label small fw-semibold text-secondary">Gambar Produk</label><input type="file" name="gambar" class="form-control py-2" accept="image/*" required></div>
                <div class="col-12"><button type="submit" name="simpan" class="btn btn-primary w-100 py-2 rounded-3 fw-semibold"><i class="bi bi-cloud-arrow-up-fill me-1"></i> Simpan ke Katalog</button></div>
            </div>
        </form>
    </div>
</div>
</body>
</html>
