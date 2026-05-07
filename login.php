<?php
require 'db.php';

if (is_logged_in()) {
    header('Location: dashboard.php');
    exit;
}
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    if (!$email || !$password) {
        $error = 'Email dan password diperlukan.';
    } else {
        $stmt = $pdo->prepare('SELECT id, name, password FROM users WHERE email = ? LIMIT 1');
        $stmt->execute([$email]);
        $user = $stmt->fetch();
        if (!$user || !password_verify($password, $user['password'])) {
            $error = 'Email atau password salah.';
        } else {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            header('Location: dashboard.php');
            exit;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login - DarkBank Pro</title>
<link rel="stylesheet" href="../shared/css/luxury.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
  body { background-color: var(--lux-bg); color: var(--lux-text); font-family: 'Plus Jakarta Sans', sans-serif; }
  .lux-login-card { max-width: 1000px; width: 100%; display: grid; grid-template-columns: 1fr 1fr; gap: 48px; padding: 48px; }
  @media (max-width: 768px) { .lux-login-card { grid-template-columns: 1fr; } }
</style>
</head>
<div class="lux-mesh"></div>
<div class="lux-glass lux-login-card animate-slide-up">
    <div class="flex flex-col justify-center">
      <p class="lux-tag">DarkUniverse Pro</p>
      <h1 class="lux-h1" style="font-size: 48px;">Login <br/><span class="lux-grad-text">Systems.</span></h1>
      <p style="color: var(--lux-text-dim); margin: 24px 0 40px; line-height: 1.8;">
        Sistem ini menggabungkan transaksi bank, biaya operasional, lowongan kerja,
        dan manajemen karyawan dalam satu dashboard eksklusif bertenaga PHP dan MySQL.
      </p>
      <div style="display: flex; gap: 16px;">
        <a href="/" class="lux-btn lux-btn-outline">Kembali ke Hub</a>
        <a href="register.php" class="lux-btn">Buat Akun</a>
      </div>
    </div>

    <div class="lux-card" style="padding: 40px;">
      <p style="color: var(--lux-text-dark); font-size: 11px; text-transform: uppercase; letter-spacing: 2px; margin-bottom: 8px;">Otoritas</p>
      <h2 style="font-size: 28px; font-weight: 800; margin-bottom: 32px;">Akses Dashboard</h2>
      
      <?php if ($error): ?>
        <div style="background: rgba(244, 63, 94, 0.1); border: 1px solid var(--lux-rose); color: var(--lux-rose); padding: 16px; border-radius: 12px; margin-bottom: 24px; font-size: 13px;">
          <?=htmlspecialchars($error)?>
        </div>
      <?php endif; ?>
      
      <form method="post">
        <div style="margin-bottom: 20px;">
            <label style="display: block; font-size: 12px; color: var(--lux-text-dim); margin-bottom: 8px; text-transform: uppercase; letter-spacing: 1px;">Email Address</label>
            <input type="email" name="email" required style="width: 100%; background: rgba(255,255,255,0.03); border: 1px solid var(--lux-border); padding: 14px; border-radius: 12px; color: #fff; outline: none; transition: var(--lux-transition);">
        </div>
        <div style="margin-bottom: 32px;">
            <label style="display: block; font-size: 12px; color: var(--lux-text-dim); margin-bottom: 8px; text-transform: uppercase; letter-spacing: 1px;">Security Password</label>
            <input type="password" name="password" required style="width: 100%; background: rgba(255,255,255,0.03); border: 1px solid var(--lux-border); padding: 14px; border-radius: 12px; color: #fff; outline: none; transition: var(--lux-transition);">
        </div>
        <button type="submit" class="lux-btn" style="width: 100%; justify-content: center; background: var(--lux-cyan); color: #000;">
            Akses Masuk
        </button>
      </form>
      <p style="text-align: center; font-size: 13px; color: var(--lux-text-dim); margin-top: 24px;">Belum punya akun? <a href="register.php" style="color: var(--lux-cyan); text-decoration: none; font-weight: 700;">Daftar sekarang</a></p>
    </div>
</div>
</body>
</html>
