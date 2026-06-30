<?php
// Jalur absolut ke config.php
include dirname(__DIR__) . '/config.php';

// PROTEKSI HALAMAN: Cek cookie login
if (!isset($_COOKIE['login_user']) || $_COOKIE['login_user'] !== 'aktif') { 
    header("Location: /login"); 
    exit; 
}

// Cek apakah ada ID yang dikirim melalui URL
if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];

    // 1. Ambil nama file gambar terlebih dahulu untuk dihapus dari server
    $result = mysqli_query($conn, "SELECT gambar FROM produk WHERE id = $id");
    if (mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);
        $gambar = $row['gambar'];

        // Hapus file gambar secara fisik dari folder uploads jika ada
        if (file_exists("uploads/" . $gambar) && !empty($gambar)) {
            unlink("uploads/" . $gambar);
        }
    }

    // 2. Hapus data produk dari database
    mysqli_query($conn, "DELETE FROM produk WHERE id = $id");
}

// Kembalikan pengguna ke halaman dashboard utama
header("Location: /index");
exit;
?>
