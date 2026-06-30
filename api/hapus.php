<?php
include dirname(__DIR__) . '/config.php';

if (!isset($_COOKIE['login_user']) || $_COOKIE['login_user'] !== 'aktif') { 
    header("Location: /login"); 
    exit; 
}

if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];

    // Hapus file gambar secara fisik dari folder uploads jika ada
    $result = mysqli_query($conn, "SELECT gambar FROM produk WHERE id = $id");
    if (mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);
        $gambar = $row['gambar'];
        $path_gambar = dirname(__DIR__) . '/uploads/' . $gambar;
        if (file_exists($path_gambar) && !empty($gambar)) {
            unlink($path_gambar);
        }
    }

    // Hapus data produk dari DB
    mysqli_query($conn, "DELETE FROM produk WHERE id = $id");
}

header("Location: /index");
exit;
