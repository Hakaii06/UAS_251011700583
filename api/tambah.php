<?php
// Jalur absolut ke config.php
include dirname(__DIR__) . '/config.php';

// PROTEKSI HALAMAN: Cek apakah Cookie login TIDAK ADA atau TIDAK VALID
if (!isset($_COOKIE['login_user']) || $_COOKIE['login_user'] !== 'aktif') { 
    header("Location: /login"); 
    exit; 
}

$error = "";

// Proses ketika tombol simpan ditekan
if (isset($_POST['simpan'])) {
    $nama_produk = mysqli_real_escape_string($conn, $_POST['nama_produk']);
    $kategori    = mysqli_real_escape_string($conn, $_POST['kategori']);
    $harga       = (int)$_POST['harga'];
    $stok        = (int)$_POST['stok'];
    $nama_umkm   = mysqli_real_escape_string($conn, $_POST['nama_umkm']);
    $deskripsi   = mysqli_real_escape_string($conn, $_POST['deskripsi']);

    // LOGIKA UPLOAD GAMBAR
    $nama_gambar = $_FILES['gambar']['name'];
    $ukuran_file = $_FILES['gambar']['size'];
    $error_file  = $_FILES['gambar']['error'];
    $tmp_name    = $_FILES['gambar']['tmp_name'];

    // Cek apakah ada gambar yang diupload
    if ($error_file === 0) {
        $ekstensi_valid = ['jpg', 'jpeg', 'png', 'webp'];
        $ekstensi_gambar = explode('.', $nama_gambar);
        $ekstensi_gambar = strtolower(end($ekstensi_gambar));

        // Validasi ekstensi file
        if (in_array($ekstensi_gambar, $ekstensi_valid)) {
            // Validasi ukuran (maksimal 2MB)
            if ($ukuran_file <= 2000000) {
                // Generate nama file baru yang unik
                $nama_file_baru = uniqid() . '.' . $ekstensi_gambar;
                
                // SOLUSI VERCEL: Simpan file fisik ke folder /tmp yang diizinkan sistem serverless
                $target_upload = '/tmp/' . $nama_file_baru;

                if (move_uploaded_file($tmp_name, $target_upload)) {
                    // Masukkan nama filenya saja ke database TiDB Cloud
                    $query = "INSERT INTO produk (nama_produk, kategori, harga, stok, nama_umkm, deskripsi, gambar) 
                              VALUES ('$nama_produk', '$kategori', $harga, $stok, '$nama_umkm', '$deskripsi', '$nama_file_baru')";
                    
                    if (mysqli_query($conn, $query)) {
                        header("Location: /index");
                        exit;
                    } else {
                        $error = "Gagal menyimpan data ke database: " . mysqli_error($conn);
                    }
                } else {
                    $error = "Gagal memindahkan file gambar ke folder /tmp server.";
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Produk - UMKM Hub Portal</title>
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
        <h4 class="fw-bold text-dark mb-1">Tambah Komoditas Produk</h4>
        <p class="text-muted small">Lengkapi formulir di bawah ini untuk menambahkan produk UMKM baru ke sistem.</p>
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
                    <input type="text" name="nama_produk" class="form-control py-2" placeholder="Contoh: Kemeja Batik Cap Premium Pekalongan" required autocomplete="off">
                </div>

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

                <div class="col-md-6 mb-3">
                    <label class="form-label small fw-semibold text-secondary">Nama UMKM Produsen</label>
                    <input type="text" name="nama_umkm" class="form-control py-2" placeholder="Contoh: Galeri Batik Solo" required autocomplete="off">
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label small fw-semibold text-secondary">Harga Jual (Rp)</label>
                    <input type="number" name="harga" class="form-control py-2" placeholder="Contoh: 15000" required>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label small fw-semibold text-secondary">Stok Sisa (Pcs)</label>
                    <input type="number" name="stok" class="form-control py-2" placeholder="Contoh: 100" required>
                </div>

                <div class="col-12 mb-3">
                    <label class="form-label small fw-semibold text-secondary">Deskripsi Singkat</label>
                    <textarea name="deskripsi" class="form-control" rows="3" placeholder="Tuliskan keterangan deskripsi produk secara singkat di sini..." required></textarea>
                </div>

                <div class="col-12 mb-4">
                    <label class="form-label small fw-semibold text-secondary">Gambar / Preview Produk</label>
                    <input type="file" name="gambar" class="form-control py-2" accept="image/*" required>
                    <div class="form-text text-muted small">Format diperbolehkan: PNG, JPG, JPEG, WEBP. Maksimal ukuran 2MB.</div>
                </div>

                <div class="col-12">
                    <button type="submit" name="simpan" class="btn btn-primary w-100 py-2 rounded-3 fw-semibold shadow-sm">
                        <i class="bi bi-cloud-arrow-up-fill me-1"></i> Simpan ke Katalog
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
