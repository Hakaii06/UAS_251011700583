<?php
// Menggunakan jalur absolut agar aman di serverless Vercel
include dirname(__DIR__) . '/config.php';

// CEK COOKIE: Jika cookie login ada dan valid, langsung lempar ke index
if (isset($_COOKIE['login_user']) && $_COOKIE['login_user'] === 'aktif') {
    header("Location: /index");
    exit;
}

$error = false;
if (isset($_POST['login'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];

    $result = mysqli_query($conn, "SELECT * FROM users WHERE username = '$username'");
    if (mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);
        
        if ($password === $row['password']) {
            // LOGIN BERHASIL: Buat cookie berlaku selama 1 jam (3600 detik)
            // Menggunakan opsi secure dan httponly agar aman di Vercel (HTTPS)
            setcookie('login_user', 'aktif', [
                'expires' => time() + 3600,
                'path' => '/',
                'secure' => true,
                'httponly' => true,
                'samesite' => 'Lax'
            ]);

            header("Location: /index");
            exit;
        }
    }
    $error = true;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - UMKM Hub</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f8fafc; height: 100vh; }
        .login-box { background: white; border-radius: 16px; box-shadow: 0 4px 25px rgba(0,0,0,0.05); overflow: hidden; max-width: 850px; width: 100%; }
        .brand-side { background: linear-gradient(135deg, #4f46e5, #3b82f6); color: white; display: flex; flex-direction: column; justify-content: center; align-items: center; padding: 3rem; }
    </style>
</head>
<body class="d-flex align-items-center justify-content-center p-3">
<div class="login-box row g-0">
    <div class="col-md-6 brand-side text-center d-none d-md-flex">
        <h2 class="fw-bold mb-2">UMKM Hub</h2>
        <p class="small opacity-75 mb-0">Platform Manajemen Data Komoditas UMKM Unggulan</p>
    </div>
    <div class="col-md-6 p-4 p-sm-5 d-flex flex-column justify-content-center">
        <div class="mb-4 text-center text-md-start">
            <h4 class="fw-bold text-dark mb-1">Selamat Datang</h4>
            <p class="text-muted small">Silakan masuk menggunakan akun administrasi Anda</p>
        </div>
        
        <?php if ($error): ?>
            <div class="alert alert-danger py-2 small" role="alert">Username atau password salah!</div>
        <?php endif; ?>

        <form action="" method="POST">
            <div class="mb-3">
                <label class="form-label small fw-semibold text-secondary">Username</label>
                <input type="text" name="username" class="form-control" placeholder="Masukkan username" required autocomplete="off">
            </div>
            <div class="mb-4">
                <label class="form-label small fw-semibold text-secondary">Password</label>
                <input type="password" name="password" class="form-control" placeholder="••••••••" required>
            </div>
            <button type="submit" name="login" class="btn btn-primary w-100 fw-semibold py-2 rounded-3 shadow-sm">Masuk ke Dashboard</button>
        </form>
    </div>
</div>
</body>
</html>
