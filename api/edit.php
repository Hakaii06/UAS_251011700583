<?php
// Jalur absolut ke config.php
include dirname(__DIR__) . '/config.php';

// PROTEKSI HALAMAN: Cek apakah Cookie login TIDAK ADA atau TIDAK VALID
if (!isset($_COOKIE['login_user']) || $_COOKIE['login_user'] !== 'aktif') { 
    header("Location: /login"); 
    exit; 
}

$error = "";

// Ambil data produk yang akan diedit berdasarkan ID di URL
if (!isset($_GET['id'])) {
    header("Location: /index");
    exit;
}

$id = (int)$_GET['id']; // <-- Sudah diperbaiki dari $GET menjadi $_GET
$result = mysqli_query($conn, "SELECT * FROM produk WHERE id = $id");

if (mysqli_num_rows($result) === 0) {
    header("Location: /index");
    exit;
}

$product = mysqli_fetch_assoc($result);

// Proses ketika tombol ubah/simpan ditekan
if (isset($_POST['ubah'])) {
    $nama_produk = mysqli_real_escape_string($conn, $_POST['nama_produk']);
    $kategori    = mysqli_real_escape_string($conn, $_POST['kategori']);
    $harga       = (int)$_POST['harga'];
    $stok        = (int)$_POST['stok'];
    $nama_umkm   = mysqli_real_escape_string($conn, $_POST['nama_umkm']);
    $deskripsi   = mysqli_real_escape_string($conn, $_POST['deskripsi']);
    
    $nama_gambar = $_FILES['gambar']['name'];
    $tmp_name    = $_FILES['gambar']['tmp_name'];

    // Jika pengguna mengunggah gambar baru
    if (!empty($nama_gambar)) {
        $ekstensi_valid = ['jpg', 'jpeg', 'png', 'webp'];
        $ekstensi_gambar = explode('.', $nama_gambar);
        $ekstensi_gambar = strtolower(end($ekstensi_gambar));

        if (in_array($ekstensi_gambar, $ekstensi_valid)) {
            $nama_file_baru = uniqid() . '.' . $ekstensi_gambar;
            $target_upload = dirname(__DIR__) . '/uploads/' . $nama_file_baru;

            if (move_uploaded_file($tmp_name, $target_upload)) {
                // Update database beserta gambar baru
                $query = "UPDATE produk SET 
                            nama_produk = '$nama_produk', 
                            kategori = '$kategori', 
                            harga = $harga, 
                            stok = $stok, 
                            nama_umkm = '$nama_umkm', 
                            deskripsi = '$deskripsi', 
                            gambar = '$nama_file_baru' 
                          WHERE id = $id";
            } else {
                $error = "Gagal mengunggah gambar baru ke folder uploads.";
            }
        } else {
            $error = "Format file gambar tidak didukung!";
        }
    } else {
        // Jika pengguna TIDAK mengubah gambar, pertahankan nama gambar lama
        $query = "UPDATE produk SET 
                    nama_produk = '$nama_produk', 
                    kategori = '$kategori', 
                    harga = $harga, 
                    stok = $stok, 
                    nama_umkm = '$nama_umkm', 
                    deskripsi = '$deskripsi' 
                  WHERE id = $id";
    }

    // Eksekusi query jika tidak ada pesan error di atas
    if (empty($error)) {
        if (mysqli_query($conn, $query)) {
            header("Location: /index");
            exit;
        } else {
            $error = "Gagal memperbarui data di database: " . mysqli_error($conn);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Produk - UMKM Hub Portal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f8fafc; }
        .form-card { background: white; border-radius: 16px; box-shadow: 0 4px 20px rgba(0,0,0,0.02); border: none; }
    </style>
</head>
<body>

<div class="container my-5" style="max-width: 700px;">
    <div class="mb-4">
        <a href="/index" class="btn btn-light text-secondary border fw-medium btn-sm rounded-2">
            <i class="bi bi-arrow-left me-1"></i> Kembali ke Dashboard
        </a>
    </div>

    <div class="mb-4">
        <h4 class="fw-bold text-dark mb-1">Edit Komoditas Produk</h4>
        <p class="text-muted small">Perbarui data informasi katalog produk UMKM binaan terpilih.</p>
    </div>

    <?php if (!empty($error)) : ?>
        <div class="alert alert-danger p-3 small rounded-3" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i> <?= $error; ?>
        </div>
    <?php endif; ?>

    <div class="card form-card p-4 p-sm-5">
        <form action="" method="POST" enctype="multipart/form-data">
            <div class="row">
                <div class="col-12 mb-3">
                    <label class="form-label small fw-semibold text-secondary">Nama Produk</label>
                    <input type="text" name="nama_produk" class="form-control py-2" value="<?= htmlspecialchars($product['nama_produk']); ?>" required>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label small fw-semibold text-secondary">Kategori</label>
                    <select name="kategori" class="form-select py-2" required>
                        <option value="Fashion" <?= $product['kategori'] == 'Fashion' ? 'selected' : ''; ?>>Fashion</option>
                        <option value="Kerajinan" <?= $product['kategori'] == 'Kerajinan' ? 'selected' : ''; ?>>Kerajinan</option>
                        <option value="Makanan" <?= $product['kategori'] == 'Makanan' ? 'selected' : ''; ?>>Makanan</option>
                        <option value="Minuman" <?= $product['kategori'] == 'Minuman' ? 'selected' : ''; ?>>Minuman</option>
                        <option value="Lainnya" <?= $product['kategori'] == 'Lainnya' ? 'selected' : ''; ?>>Lainnya</option>
                    </select>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label small fw-semibold text-secondary">Nama UMKM Produsen</label>
                    <input type="text" name="nama_umkm" class="form-control py-2" value="<?= htmlspecialchars($product['nama_umkm']); ?>" required>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label small fw-semibold text-secondary">Harga Jual (Rp)</label>
                    <input type="number" name="harga" class="form-control py-2" value="<?= $product['harga']; ?>" required>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label small fw-semibold text-secondary">Stok Sisa (Pcs)</label>
                    <input type="number" name="stok" class="form-control py-2" value="<?= $product['stok']; ?>" required>
                </div>

                <div class="col-12 mb-3">
                    <label class="form-label small fw-semibold text-secondary">Deskripsi Singkat</label>
                    <textarea name="deskripsi" class="form-control" rows="3" required><?= htmlspecialchars($product['deskripsi']); ?></textarea>
                </div>

                <div class="col-12 mb-4">
                    <label class="form-label small fw-semibold text-secondary">Gambar / Preview Produk</label>
                    <div class="mb-2">
                        <small class="text-muted d-block mb-1">Gambar saat ini:</small>
                        <?php if(!empty($product['gambar']) && file_exists(dirname(__DIR__) . '/uploads/' . $product['gambar'])): ?>
                            <img src="uploads/<?= $product['gambar']; ?>" width="65" height="65" class="rounded border object-fit-cover" alt="Current Image">
                        <?php else: ?>
                            <div class="bg-light rounded d-flex align-items-center justify-content-center border" style="width:65px; height:65px;">
                                <i class="bi bi-image text-muted"></i>
                            </div>
                        <?php endif; ?>
                    </div>
                    <input type="file" name="gambar" class="form-control py-2" accept="image/*">
                    <div class="form-text text-muted small">Kosongkan jika tidak ingin mengubah foto produk.</div>
                </div>

                <div class="col-12">
                    <button type="submit" name="ubah" class="btn btn-warning w-100 py-2 rounded-3 fw-semibold shadow-sm text-dark">
                        <i class="bi bi-check-circle-fill me-1"></i> Simpan Perubahan
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
