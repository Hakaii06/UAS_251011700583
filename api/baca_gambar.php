<?php
// Mengambil nama file gambar secara aman dari URL (?file=nama_file.jpg)
$file = isset($_GET['file']) ? basename($_GET['file']) : '';

if (empty($file)) {
    display_placeholder();
}

// 1. Jalur ke folder /tmp/ (untuk file hasil tambah/edit produk saat runtime di Vercel)
$path_tmp = '/tmp/' . $file;

// 2. Jalur ke folder uploads/ yang berada di dalam folder api/ (sejajar dengan file ini)
$path_uploads = __DIR__ . '/uploads/' . $file;

// Logika penentuan lokasi file gambar yang valid
if (file_exists($path_tmp) && is_file($path_tmp)) {
    $path_final = $path_tmp;
} elseif (file_exists($path_uploads) && is_file($path_uploads)) {
    $path_final = $path_uploads;
} else {
    $path_final = '';
}

// Jika gambar ditemukan di salah satu folder, kirimkan langsung ke browser
if (!empty($path_final)) {
    $ext = strtolower(pathinfo($path_final, PATHINFO_EXTENSION));
    $mime = ($ext === 'png') ? 'image/png' : (($ext === 'webp') ? 'image/webp' : 'image/jpeg');
    
    header('Content-Type: ' . $mime);
    header('Content-Length: ' . filesize($path_final));
    readfile($path_final);
    exit;
} else {
    display_placeholder();
}

// Fungsi pembantu jika gambar tidak ditemukan fisik datanya
function display_placeholder() {
    header('Content-Type: image/png');
    echo base64_decode('iVBOR0w0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNkYAAAAAYAAjCB0C8AAAAASUVORK5CYII=');
    exit;
}
