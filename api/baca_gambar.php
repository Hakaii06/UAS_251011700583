<?php
// Ambil nama file dari parameter URL aman (?file=nama_foto.png)
$file = isset($_GET['file']) ? basename($_GET['file']) : '';
$path = '/tmp/' . $file;

if (!empty($file) && file_exists($path)) {
    // Cari tahu tipe file gambar
    $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
    $mime = ($ext == 'png') ? 'image/png' : (($ext == 'webp') ? 'image/webp' : 'image/jpeg');
    
    // Kirim header gambar dan baca filenya
    header('Content-Type: ' . $mime);
    readfile($path);
    exit;
} else {
    // Jika gambar hilang (karena serverless function di-reset otomatis oleh Vercel), tampilkan gambar transparan mini
    header('Content-Type: image/png');
    echo base64_decode('iVBOR0w0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNkYAAAAAYAAjCB0C8AAAAASUVORK5CYII=');
    exit;
}
