<?php
// 1. Hapus cookie login dengan mengatur waktu kedaluwarsa ke masa lalu
if (isset($_COOKIE['login_user'])) {
    setcookie('login_user', '', time() - 3600, '/'); 
}

// 2. Tetap bersihkan session jika sewaktu-waktu digunakan di halaman lain
session_start();
$_SESSION = [];
session_unset();
session_destroy();

// 3. Alihkan ke halaman login (gunakan '/' jika routing Vercel Anda menggunakan Clean URLs)
header("Location: /login");
exit;
