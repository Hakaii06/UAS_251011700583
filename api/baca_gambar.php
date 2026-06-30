<?php
$file = isset($_GET['file']) ? basename($_GET['file']) : '';

// 1. Cek di folder /tmp (untuk gambar yang baru diupload)
$path_tmp = '/tmp/' . $file;
// 2. Cek di folder uploads root (untuk gambar bawaan dari GitHub)
$path_uploads = dirname(__DIR__) . '/uploads/' . $file;

if (!empty($file) && file_exists($path_tmp)) {
    $path_final = $path_tmp;
} elseif (!empty($file) && file_exists($path_uploads)) {
    $path_final = $path_uploads;
} else {
    $path_final = '';
}

if (!empty($path_final)) {
    $ext = strtolower(pathinfo($path_final, PATHINFO_EXTENSION));
    $mime = ($ext == 'png') ? 'image/png' : (($ext == 'webp') ? 'image/webp' : 'image/jpeg');
    
    header('Content-Type: ' . $mime);
    readfile($path_final);
    exit;
} else {
    // Jika di kedua tempat tidak ada, tampilkan gambar transparan kosong agar tidak kelihatan rusak
    header('Content-Type: image/png');
    echo base64_decode('iVBOR0w0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNkYAAAAAYAAjCB0C8AAAAASUVORK5CYII=');
    exit;
}
