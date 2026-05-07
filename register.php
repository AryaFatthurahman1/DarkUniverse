<?php
require 'db.php';

if (is_logged_in()) {
    header('Location: dashboard.php');
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['confirm'] ?? '';

    if (!$name || !$email || !$password || !$confirm) {
        $error = 'Semua bidang wajib diisi.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Email tidak valid.';
    } elseif ($password !== $confirm) {
        $error = 'Password tidak cocok.';
    } else {
        $stmt = $pdo->prepare('SELECT id FROM users WHERE email = ? LIMIT 1');
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $error = 'Email sudah terdaftar.';
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare('INSERT INTO users (name, email, password) VALUES (?, ?, ?)');
            $stmt->execute([$name, $email, $hash]);
            $_SESSION['user_id'] = $pdo->lastInsertId();
            $_SESSION['user_name'] = $name;
            header('Location: dashboard.php');
            exit;
        }
    }
}
$nameValue = htmlspecialchars($name ?? '');
$emailValue = htmlspecialchars($email ?? '');
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Daftar - DarkBank Pro</title>
<script src="https://cdn.tailwindcss.com"></script>
<style>
  @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700;900&display=swap');
  body { background-color: #0f172a; color: #f8fafc; font-family: 'Inter', sans-serif; }
  .glass { background: rgba(30, 41, 59, 0.7); backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.1); }
</style>
</head>
<body class="min-h-screen flex items-center justify-center bg-slate-900 p-4 py-12">
<div class="w-full max-w-4xl grid md:grid-cols-2 gap-8 glass rounded-3xl p-8 shadow-2xl">
    <div class="flex flex-col justify-center">
      <p class="text-cyan-400 font-bold tracking-widest uppercase text-sm mb-2">DarkBank Pro</p>
      <h1 class="text-4xl md:text-5xl font-black text-white mb-6 leading-tight">Mulai kelola <br/><span class="text-transparent bg-clip-text bg-gradient-to-r from-cyan-400 to-blue-500">transaksi.</span></h1>
      <p class="text-slate-400 mb-8 leading-relaxed">
        Registrasi ini akan membuka akses ke dashboard operasional, laporan laba rugi,
        dan pengelolaan karyawan.
      </p>
      <div class="flex gap-4">
        <a href="/" class="px-6 py-3 rounded-xl border border-slate-700 hover:border-cyan-400 hover:bg-slate-800 transition-all text-slate-300">Kembali ke Hub</a>
        <a href="login.php" class="px-6 py-3 rounded-xl bg-slate-800 hover:bg-slate-700 text-white font-bold transition-all border border-slate-700">Sudah Punya Akun</a>
      </div>
    </div>

    <div class="bg-slate-800/50 p-8 rounded-2xl border border-slate-700">
      <p class="text-slate-400 text-sm mb-2 uppercase tracking-wide">Daftar</p>
      <h2 class="text-3xl font-bold text-white mb-6">Buat Akun Bank</h2>
      
      <?php if ($error): ?>
        <div class="bg-red-500/10 border border-red-500 text-red-400 p-4 rounded-xl mb-6">
          <?=htmlspecialchars($error)?>
        </div>
      <?php endif; ?>
      
      <form method="post" class="space-y-4">
        <div>
            <label class="block text-slate-300 mb-1 text-sm">Nama Lengkap</label>
            <input type="text" name="name" value="<?= $nameValue ?>" required class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 transition-all">
        </div>
        <div>
            <label class="block text-slate-300 mb-1 text-sm">Email</label>
            <input type="email" name="email" value="<?= $emailValue ?>" required class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 transition-all">
        </div>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-slate-300 mb-1 text-sm">Password</label>
                <input type="password" name="password" required class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 transition-all">
            </div>
            <div>
                <label class="block text-slate-300 mb-1 text-sm">Konfirmasi</label>
                <input type="password" name="confirm" required class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 transition-all">
            </div>
        </div>
        <button type="submit" class="w-full bg-gradient-to-r from-cyan-500 to-blue-600 hover:from-cyan-400 hover:to-blue-500 text-white font-bold py-3 rounded-xl shadow-lg hover:shadow-cyan-500/25 transition-all mt-6">
            Daftar Sekarang
        </button>
      </form>
    </div>
</div>
</body>
</html>
