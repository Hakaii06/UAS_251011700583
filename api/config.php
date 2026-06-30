<?php
// Mengambil data dari Environment Variables Vercel
$host = getenv('DB_HOST');
$user = getenv('DB_USER');
$pass = getenv('DB_PASSWORD');
$db   = getenv('DB_NAME');
$port = 4000; // Port default TiDB Cloud sesuai screenshot

// Jika dibuka di hosting (Vercel), fungsi getenv() otomatis terisi dari panel Environment Variables.
// Jika variabel $host kosong (artinya kamu sedang menjalankan project di laptop/XAMPP lokal), 
// maka otomatis menggunakan setingan localhost di bawah ini:
if (!$host) {
    $host = 'localhost';
    $user = 'root';
    $pass = '';
    $db   = 'db_umkm';
    $port = 3306; // Port default MySQL di XAMPP
} else {
    // Jika berjalan di Vercel, kita paksa datanya menggunakan parameter asli dari TiDB Cloud kamu
    $host = 'gateway01.ap-southeast-1.prod.alicloud.tidbcloud.com';
    $user = '2b18rGRFVFYTq3z.root';
    $pass = 'IGc4z17ERYTUhQ7v'; // Sesuai dengan password yang baru digenerate di gambar
    $db   = 'db_umkm'; // Tetap arahkan ke database project kamu yang sudah di-run struktur tabelnya
}

// Hubungkan ke database dengan menyertakan port
$conn = mysqli_connect($host, $user, $pass, $db, $port);

if (!$conn) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}
?>
