<?php
$host = getenv('DB_HOST');
$user = getenv('DB_USER');
$pass = getenv('DB_PASSWORD');
$db   = getenv('DB_NAME');
$port = 4000;

if (!$host) {
    // Setingan lokal untuk XAMPP (Tanpa SSL)
    $host = 'localhost';
    $user = 'root';
    $pass = '';
    $db   = 'db_umkm';
    $port = 3306;

    $conn = mysqli_connect($host, $user, $pass, $db, $port);
} else {
    // Setingan Produksi untuk Vercel + TiDB Cloud (Wajib SSL)
    $host = 'gateway01.ap-southeast-1.prod.alicloud.tidbcloud.com';
    $user = '2b18rGRFVFYTq3z.root';
    $pass = 'IGc4z17ERYTUhQ7v';
    $db   = 'db_umkm';
    $port = 4000;

    // Inisialisasi MySQLi untuk mengaktifkan SSL
    $conn = mysqli_init();
    
    if (!$conn) {
        die("mysqli_init gagal");
    }

    // Mengaktifkan flag SSL sebelum melakukan koneksi
    mysqli_real_connect($conn, $host, $user, $pass, $db, $port, null, MYSQLI_CLIENT_SSL);
}

// Cek apakah koneksi berhasil
if (mysqli_connect_errno()) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}
?>
