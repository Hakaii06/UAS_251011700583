<?php
session_start();
include 'config.php';

// Jika user sudah login, langsung dialihkan ke halaman utama (index.php)
if (isset($_SESSION['login'])) {
    header("Location: index.php");
    exit;
}

if (isset($_POST['submit'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];

    // Mencari user berdasarkan username di database
    $result = mysqli_query($conn, "SELECT * FROM users WHERE username = '$username'");
    
    if (mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);
        
        // MENYELARASKAN DENGAN SQL ANDA: Menggunakan pencocokan teks biasa (plain-text)
        if ($password === $row['password']) {
            // Set session jika password cocok
            $_SESSION['login'] = true;
            $_SESSION['username'] = $row['username'];
            
            header("Location: index.php");
            exit;
        }
    }
    // Jika salah, aktifkan status error
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
        }
        .login-box {
            width: 100%;
            max-width: 400px;
            padding: 40px 30px;
            background: #ffffff;
            border-radius: 16px;
            box-shadow: 0 10px 25px -5px rgba(0,0,0,0.05), 0 8px 10px -6px rgba(0,0,0,0.05);
        }
        .login-box h4 {
            color: #4f46e5;
            font-weight: 700;
            text-align: center;
            margin-bottom: 5px;
            letter-spacing: -0.5px;
        }
        .btn-login {
            background-color: #4f46e5;
            color: white;
            font-weight: 600;
            border: none;
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.3);
            transition: all 0.2s;
        }
        .btn-login:hover {
            background-color: #4338ca;
            color: white;
            transform: translateY(-1px);
        }
    </style>
</head>
<body>

<div class="login-box">
    <h4><i class="fa-solid fa-layer-group me-2"></i>UMKM Hub</h4>
    <p class="text-center text-muted small mb-4">Masukkan hak akses administrasi Anda</p>
    
    <?php if (isset($error)) : ?>
        <div class="alert alert-danger py-2 small text-center rounded-3">Username atau password salah!</div>
    <?php endif; ?>
    
    <form action="" method="POST">
        <div class="mb-3">
            <label class="form-label small fw-semibold text-secondary">Username</label>
            <div class="input-group input-group-sm">
                <span class="input-group-text bg-light text-muted border-end-0"><i class="fa-regular fa-user"></i></span>
                <input type="text" name="username" class="form-control bg-light border-start-0" placeholder="admin" required autocomplete="off">
            </div>
        </div>
        <div class="mb-4">
            <label class="form-label small fw-semibold text-secondary">Password</label>
            <div class="input-group input-group-sm">
                <span class="input-group-text bg-light text-muted border-end-0"><i class="fa-solid fa-lock"></i></span>
                <input type="password" name="password" class="form-control bg-light border-start-0" placeholder="••••••••" required>
            </div>
        </div>
        <button type="submit" name="submit" class="btn btn-login w-100 py-2 rounded-3 mb-3">Masuk ke Dashboard</button>
        <div class="text-center small text-muted" style="font-size: 11px;">
            Belum punya akun? <a href="#" class="text-decoration-none text-primary fw-medium">Hubungi Admin Utama</a>
        </div>
    </form>
</div>

</body>
</html>