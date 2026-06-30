<?php
// Mengambil nama file gambar secara aman dari URL (?file=nama_file.jpg)
$file = isset($_GET['file']) ? basename($_GET['file']) : '';

if (empty($file)) {
    display_placeholder();
}

// 1. Jalur pencarian ke folder /tmp/ (untuk file baru hasil upload runtime)
$path_tmp = '/tmp/' . $file;

// 2. Jalur pencarian ke folder uploads/ di sebelah file ini (untuk file bawaan GitHub)
$path_uploads = __DIR__ . '/uploads/' . $file;

// Tentukan lokasi file akhir yang benar-asli ada fisiknya
if (file_exists($path_tmp) && is_file($path_tmp)) {
    $path_final = $path_tmp;
} elseif (file_exists($path_uploads) && is_file($path_uploads)) {
    $path_final = $path_uploads;
} else {
    $path_final = '';
}

// Jika gambar ditemukan di salah satu folder, kirimkan ke browser
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

// Fungsi pembantu untuk menampilkan blank placeholder jika gambar rusak/tidak ada
function display_placeholder() {
    header('Content-Type: image/png');
    echo base64_decode('iVBOR0w0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNkYAAAAAYAAjCB0C8AAAAASUVORK5CYII=');
    exit;
}
