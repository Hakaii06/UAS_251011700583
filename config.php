<?php
// Parameter Koneksi Resmi dari TiDB Cloud Kamu
$host = 'gateway01.ap-southeast-1.prod.alicloud.tidbcloud.com';
$user = '2gxBZgfUU1M5kJm.root';
$pass = 'lrWTxGzj2UJP9nRF';
$db   = 'dbumkmhub';
$port = 4000;

// Inisialisasi MySQLi untuk mengaktifkan fitur SSL/TLS
$conn = mysqli_init();

if (!$conn) {
    die("mysqli_init gagal dimuat");
}

// Menghubungkan secara aman menggunakan flag SSL (Wajib untuk TiDB Serverless)
mysqli_real_connect($conn, $host, $user, $pass, $db, $port, null, MYSQLI_CLIENT_SSL);

// Cek apakah koneksi ke cloud berhasil atau gagal
if (mysqli_connect_errno()) {
    die("Koneksi database TiDB Cloud gagal: " . mysqli_connect_error());
}
?>
