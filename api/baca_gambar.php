<?php
// Mengambil nama file gambar secara aman dari URL
$file = isset($_GET['file']) ? basename($_GET['file']) : '';

// 1. Jalur pencarian ke folder /tmp/ (untuk file baru hasil tambah/edit produk)
$path_tmp = '/tmp/' . $file;

// 2. Jalur pencarian ke folder api/uploads/ yang ada di GitHub (untuk gambar bawaan)
$path_uploads = __DIR__ . '/uploads/' . $file;

// Logika penentuan lokasi file gambar yang valid
if (!empty($file) && file_exists($path_tmp)) {
    $path_final = $path_tmp;
} elseif (!empty($file) && file_exists($path_uploads)) {
    $path_final = $path_uploads;
} else {
    $path_final = '';
}

// Jika gambar ditemukan di salah satu folder, kirimkan langsung ke browser
if (!empty($path_final)) {
    $ext = strtolower(pathinfo($path_final, PATHINFO_EXTENSION));
    $mime = ($ext === 'png') ? 'image/png' : (($ext === 'webp') ? 'image/webp' : 'image/jpeg');
    
    header('Content-Type: ' . $mime);
    readfile($path_final);
    exit;
} else {
    // Jika data gambar di database tidak ada fisiknya, tampilkan placeholder transparan
    header('Content-Type: image/png');
    echo base64_decode('iVBOR0w0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNkYAAAAAYAAjCB0C8AAAAASUVORK5CYII=');
    exit;
}
