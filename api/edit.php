<?php
include dirname(__DIR__) . '/config.php';

if (!isset($_COOKIE['login_user']) || $_COOKIE['login_user'] !== 'aktif') { 
    header("Location: /login"); 
    exit; 
}

$error = "";

if (!isset($_GET['id'])) {
    header("Location: /index");
    exit;
}

$id = (int)$_GET['id'];
$result = mysqli_query($conn, "SELECT * FROM produk WHERE id = $id");

if (mysqli_num_rows($result) === 0) {
    header("Location: /index");
    exit;
}

$product = mysqli_fetch_assoc($result);

if (isset($_POST['ubah'])) {
    $nama_produk = mysqli_real_escape_string($conn, $_POST['nama_produk']);
    $kategori    = mysqli_real_escape_string($conn, $_POST['kategori']);
    $harga       = (int)$_POST['harga'];
    $stok        = (int)$_POST['stok'];
    $nama_umkm   = mysqli_real_escape_string($conn, $_POST['nama_umkm']);
    $deskripsi   = mysqli_real_escape_string($conn, $_POST['deskripsi']);
    
    $nama_gambar = $_FILES['gambar']['name'];
    $tmp_name    = $_FILES['gambar']['tmp_name'];

    if (!empty($nama_gambar)) {
        $ekstensi_valid = ['jpg', 'jpeg', 'png', 'webp'];
        $ekstensi_gambar = explode('.', $nama_gambar);
        $ekstensi_gambar = strtolower(end($ekstensi_gambar));

        if (in_array($ekstensi_gambar, $ekstensi_valid)) {
            $nama_file_baru = uniqid() . '.' . $ekstensi_gambar;
            // Ditulis ke /tmp/ agar Vercel tidak mengeluarkan error Read-Only
            $target_upload = '/tmp/' . $nama_file_baru;

            if (move_uploaded_file($tmp_name, $target_upload)) {
                $query = "UPDATE produk SET nama_produk='$nama_produk', kategori='$kategori', harga=$harga, stok=$stok, nama_umkm='$nama_umkm', deskripsi='$deskripsi', gambar='$nama_file_baru' WHERE id=$id";
            } else {
                $error = "Gagal mengunggah gambar baru.";
            }
        } else {
            $error = "Format file gambar tidak didukung!";
        }
    } else {
        $query = "UPDATE produk SET nama_produk='$nama_produk', kategori='$kategori', harga=$harga, stok=$stok, nama_umkm='$nama_umkm', deskripsi='$deskripsi' WHERE id=$id";
    }

    if (empty($error) && mysqli_query($conn, $query)) {
        header("Location: /index");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Produk - UMKM Hub Portal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f8fafc; }</style>
</head>
<body>
<div class="container my-5" style="max-width: 700px;">
    <div class="card p-5 shadow-sm border-0 rounded-4">
        <h4 class="fw-bold mb-4">Edit Komoditas Produk</h4>
        <?php if(!empty($error)): ?><div class="alert alert-danger"><?= $error; ?></div><?php endif; ?>
        <form action="" method="POST" enctype="multipart/form-data">
            <div class="mb-3"><label class="form-label small fw-semibold">Nama Produk</label><input type="text" name="nama_produk" class="form-control" value="<?= htmlspecialchars($product['nama_produk']); ?>" required></div>
            <div class="mb-3">
                <label class="form-label small fw-semibold">Kategori</label>
                <select name="kategori" class="form-select" required>
                    <option value="Fashion" <?= $product['kategori'] == 'Fashion' ? 'selected' : ''; ?>>Fashion</option>
                    <option value="Kerajinan" <?= $product['kategori'] == 'Kerajinan' ? 'selected' : ''; ?>>Kerajinan</option>
                    <option value="Makanan" <?= $product['kategori'] == 'Makanan' ? 'selected' : ''; ?>>Makanan</option>
                    <option value="Minuman" <?= $product['kategori'] == 'Minuman' ? 'selected' : ''; ?>>Minuman</option>
                </select>
            </div>
            <div class="mb-3"><label class="form-label small fw-semibold">Nama UMKM</label><input type="text" name="nama_umkm" class="form-control" value="<?= htmlspecialchars($product['nama_umkm']); ?>" required></div>
            <div class="mb-3"><label class="form-label small fw-semibold">Harga Jual</label><input type="number" name="harga" class="form-control" value="<?= $product['harga']; ?>" required></div>
            <div class="mb-3"><label class="form-label small fw-semibold">Stok Sisa</label><input type="number" name="stok" class="form-control" value="<?= $product['stok']; ?>" required></div>
            <div class="mb-3"><label class="form-label small fw-semibold">Deskripsi</label><textarea name="deskripsi" class="form-control" rows="3" required><?= htmlspecialchars($product['deskripsi']); ?></textarea></div>
            <div class="mb-4">
                <label class="form-label small fw-semibold">Gambar Saat Ini</label>
                <div class="mb-2"><img src="/baca_gambar?file=<?= $product['gambar']; ?>" width="65" height="65" style="object-fit:cover;" class="rounded border"></div>
                <input type="file" name="gambar" class="form-control" accept="image/*">
            </div>
            <button type="submit" name="ubah" class="btn btn-warning w-100 fw-semibold text-dark">Simpan Perubahan</button>
        </form>
    </div>
</div>
</body>
</html>
