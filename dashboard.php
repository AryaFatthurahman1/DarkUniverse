<?php
require 'db.php';
require_login();
$userId = $_SESSION['user_id'];

$info = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        $action = $_POST['action'];
        if ($action === 'income' || $action === 'payment') {
            $amount = floatval($_POST['amount'] ?? 0);
            $fee = floatval($_POST['fee'] ?? 0);
            $desc = trim($_POST['description'] ?? '');
            if ($amount <= 0) {
                $info = 'Nominal harus lebih besar dari 0.';
            } else {
                $stmt = $pdo->prepare('INSERT INTO transactions (user_id, type, amount, fee, description) VALUES (?, ?, ?, ?, ?)');
                $stmt->execute([$userId, $action, $amount, $fee, $desc]);
                $info = ucfirst($action) . ' berhasil ditambahkan.';
            }
        }
        if ($action === 'expense') {
            $category = trim($_POST['category'] ?? 'Umum');
            $amount = floatval($_POST['amount_expense'] ?? 0);
            $desc = trim($_POST['description_expense'] ?? '');
            if ($amount <= 0) {
                $info = 'Nominal biaya harus lebih besar dari 0.';
            } else {
                $stmt = $pdo->prepare('INSERT INTO expenses (user_id, category, amount, description) VALUES (?, ?, ?, ?)');
                $stmt->execute([$userId, $category, $amount, $desc]);
                $info = 'Biaya berhasil ditambahkan.';
            }
        }
        if ($action === 'job') {
            $title = trim($_POST['title'] ?? 'Lowongan');
            $location = trim($_POST['location'] ?? 'Indonesia');
            $positions = max(1, intval($_POST['positions'] ?? 1));
            $cost_per = floatval($_POST['cost_per'] ?? 0);
            if ($cost_per < 0) { $info = 'Biaya per posisi tidak valid.'; }
            else {
                $stmt = $pdo->prepare('INSERT INTO jobs (user_id, title, location, positions, cost_per_position) VALUES (?, ?, ?, ?, ?)');
                $stmt->execute([$userId, $title, $location, $positions, $cost_per]);
                $info = 'Lowongan pekerjaan berhasil ditambahkan.';
            }
        }
        if ($action === 'employee') {
            $name = trim($_POST['employee_name'] ?? '');
            $jobId = intval($_POST['job_id'] ?? 0);
            $salary = floatval($_POST['salary'] ?? 0);
            if (!$name || $salary <= 0) { $info = 'Nama dan gaji harus diisi.'; }
            else {
                $stmt = $pdo->prepare('INSERT INTO employees (user_id, name, job_id, salary) VALUES (?, ?, ?, ?)');
                $stmt->execute([$userId, $name, $jobId > 0 ? $jobId : null, $salary]);
                $info = 'Karyawan berhasil ditambahkan.';
            }
        }
        if ($action === 'cancel') {
            $txId = intval($_POST['tx_id'] ?? 0);
            $stmt = $pdo->prepare('UPDATE transactions SET status = "cancelled" WHERE id = ? AND user_id = ? AND status = "completed"');
            $stmt->execute([$txId, $userId]);
            if ($stmt->rowCount() > 0) {
                $info = 'Transaksi dibatalkan.';
            } else {
                $info = 'Transaksi tidak ditemukan atau sudah dibatalkan.';
            }
        }
    }
}

$stmt = $pdo->prepare('SELECT * FROM jobs WHERE user_id = ? ORDER BY created_at DESC');
$stmt->execute([$userId]);
$jobs = $stmt->fetchAll();

$stmt = $pdo->prepare('SELECT e.*, j.title AS job_title FROM employees e LEFT JOIN jobs j ON e.job_id = j.id WHERE e.user_id = ? ORDER BY e.created_at DESC');
$stmt->execute([$userId]);
$employees = $stmt->fetchAll();

$stmt = $pdo->prepare('SELECT * FROM transactions WHERE user_id = ? ORDER BY created_at DESC LIMIT 15');
$stmt->execute([$userId]);
$transactions = $stmt->fetchAll();

$stmt = $pdo->prepare('SELECT * FROM expenses WHERE user_id = ? ORDER BY created_at DESC LIMIT 15');
$stmt->execute([$userId]);
$expenses = $stmt->fetchAll();

$stmt = $pdo->prepare('SELECT COALESCE(SUM(amount - fee),0) AS s FROM transactions WHERE user_id = ? AND type = "income" AND status = "completed"');
$stmt->execute([$userId]);
$totIncome = floatval($stmt->fetchColumn());

$stmt = $pdo->prepare('SELECT COALESCE(SUM(amount + fee),0) AS s FROM transactions WHERE user_id = ? AND type = "payment" AND status = "completed"');
$stmt->execute([$userId]);
$totPayment = floatval($stmt->fetchColumn());

$stmt = $pdo->prepare('SELECT COALESCE(SUM(amount),0) AS s FROM expenses WHERE user_id = ?');
$stmt->execute([$userId]);
$totExpenses = floatval($stmt->fetchColumn());

$balance = $totIncome - $totPayment - $totExpenses;

$totalJobBudget = 0;
foreach ($jobs as $job) {
    $totalJobBudget += $job['positions'] * $job['cost_per_position'];
}

$totalSalary = 0;
foreach ($employees as $e) { $totalSalary += $e['salary']; }

$transactionFees = $pdo->prepare('SELECT COALESCE(SUM(fee),0) FROM transactions WHERE user_id = ? AND status = "completed"');
$transactionFees->execute([$userId]);
$transactionFees = $transactionFees->fetchColumn();

?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Dashboard - DarkBank Pro</title>
<script src="https://cdn.tailwindcss.com"></script>
<style>
  @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;900&display=swap');
  body { background-color: #0f172a; color: #f8fafc; font-family: 'Inter', sans-serif; }
  .glass { background: rgba(30, 41, 59, 0.5); backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.05); }
  .table-row-hover:hover { background: rgba(255,255,255,0.02); }
</style>
</head>
<body class="min-h-screen bg-slate-900">

<nav class="glass border-b border-slate-800 sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-black text-transparent bg-clip-text bg-gradient-to-r from-cyan-400 to-blue-500 tracking-wider">DarkBank Pro</h1>
            <p class="text-xs text-slate-400 font-medium uppercase tracking-widest mt-1">Dashboard Operasional</p>
        </div>
        <div class="flex items-center gap-6">
            <span class="text-slate-300">Halo, <strong class="text-white"><?=htmlspecialchars($_SESSION['user_name'])?></strong></span>
            <a href="/" class="text-sm text-cyan-400 hover:text-cyan-300 font-medium">Main Hub</a>
            <a href="logout.php" class="px-4 py-2 rounded-lg bg-rose-500/10 text-rose-400 border border-rose-500/20 hover:bg-rose-500 hover:text-white transition-all text-sm font-bold">Logout</a>
        </div>
    </div>
</nav>

<main class="max-w-7xl mx-auto px-6 py-8">
    <?php if ($info): ?>
        <div class="bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 px-6 py-4 rounded-2xl mb-8 flex items-center gap-3">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            <?=htmlspecialchars($info)?>
        </div>
    <?php endif; ?>

    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
        <div class="glass p-6 rounded-2xl border-l-4 border-cyan-500">
            <p class="text-sm text-slate-400 font-medium mb-1">Saldo Saat Ini</p>
            <h3 class="text-3xl font-black text-cyan-400">Rp <?=number_format($balance,0,',','.')?></h3>
        </div>
        <div class="glass p-6 rounded-2xl border-l-4 border-emerald-500">
            <p class="text-sm text-slate-400 font-medium mb-1">Pemasukan (Net)</p>
            <h3 class="text-2xl font-bold text-emerald-400">Rp <?=number_format($totIncome,0,',','.')?></h3>
        </div>
        <div class="glass p-6 rounded-2xl border-l-4 border-rose-500">
            <p class="text-sm text-slate-400 font-medium mb-1">Pembayaran & Pengeluaran</p>
            <h3 class="text-2xl font-bold text-rose-400">Rp <?=number_format($totPayment + $totExpenses,0,',','.')?></h3>
        </div>
        <div class="glass p-6 rounded-2xl border-l-4 border-amber-500">
            <p class="text-sm text-slate-400 font-medium mb-1">Beban Gaji & Job</p>
            <h3 class="text-2xl font-bold text-amber-400">Rp <?=number_format($totalSalary + $totalJobBudget,0,',','.')?></h3>
        </div>
    </div>

    <div class="grid md:grid-cols-3 gap-6 mb-8">
        <div class="glass p-6 rounded-2xl border-t-4 border-emerald-500 shadow-lg shadow-emerald-500/5">
            <h3 class="text-lg font-bold text-white mb-4">Kas & Bank</h3>
            <div class="flex gap-2 mb-4 border-b border-slate-700 pb-4">
                <button onclick="document.getElementById('form-income').style.display='block';document.getElementById('form-payment').style.display='none'" class="flex-1 bg-emerald-500/20 text-emerald-400 py-2 rounded-lg text-sm font-bold hover:bg-emerald-500 hover:text-white transition-all">Pemasukan</button>
                <button onclick="document.getElementById('form-income').style.display='none';document.getElementById('form-payment').style.display='block'" class="flex-1 bg-rose-500/20 text-rose-400 py-2 rounded-lg text-sm font-bold hover:bg-rose-500 hover:text-white transition-all">Pengeluaran</button>
            </div>
            
            <form id="form-income" method="post" class="space-y-4">
                <input type="hidden" name="action" value="income">
                <div>
                    <label class="block text-xs text-slate-400 mb-1">Nominal (Rp)</label>
                    <input type="number" name="amount" step="0.01" required class="w-full bg-slate-900 border border-slate-700 rounded-lg px-3 py-2 text-white focus:outline-none focus:border-emerald-500">
                </div>
                <div>
                    <label class="block text-xs text-slate-400 mb-1">Fee Bank (Rp)</label>
                    <input type="number" name="fee" step="0.01" value="0" class="w-full bg-slate-900 border border-slate-700 rounded-lg px-3 py-2 text-white focus:outline-none focus:border-emerald-500">
                </div>
                <div>
                    <label class="block text-xs text-slate-400 mb-1">Keterangan</label>
                    <input type="text" name="description" class="w-full bg-slate-900 border border-slate-700 rounded-lg px-3 py-2 text-white focus:outline-none focus:border-emerald-500">
                </div>
                <button type="submit" class="w-full bg-emerald-500 hover:bg-emerald-400 text-slate-900 font-bold py-2 rounded-lg transition-all mt-2">Simpan Pemasukan</button>
            </form>

            <form id="form-payment" method="post" class="space-y-4" style="display:none;">
                <input type="hidden" name="action" value="payment">
                <div>
                    <label class="block text-xs text-slate-400 mb-1">Nominal (Rp)</label>
                    <input type="number" name="amount" step="0.01" required class="w-full bg-slate-900 border border-slate-700 rounded-lg px-3 py-2 text-white focus:outline-none focus:border-rose-500">
                </div>
                <div>
                    <label class="block text-xs text-slate-400 mb-1">Biaya Tambahan (Rp)</label>
                    <input type="number" name="fee" step="0.01" value="0" class="w-full bg-slate-900 border border-slate-700 rounded-lg px-3 py-2 text-white focus:outline-none focus:border-rose-500">
                </div>
                <div>
                    <label class="block text-xs text-slate-400 mb-1">Keterangan</label>
                    <input type="text" name="description" class="w-full bg-slate-900 border border-slate-700 rounded-lg px-3 py-2 text-white focus:outline-none focus:border-rose-500">
                </div>
                <button type="submit" class="w-full bg-rose-500 hover:bg-rose-400 text-white font-bold py-2 rounded-lg transition-all mt-2">Simpan Pengeluaran</button>
            </form>
        </div>

        <div class="glass p-6 rounded-2xl border-t-4 border-amber-500 shadow-lg shadow-amber-500/5">
            <h3 class="text-lg font-bold text-white mb-4">Biaya Operasional</h3>
            <form method="post" class="space-y-4">
                <input type="hidden" name="action" value="expense">
                <div>
                    <label class="block text-xs text-slate-400 mb-1">Kategori</label>
                    <input type="text" name="category" value="Operational" required class="w-full bg-slate-900 border border-slate-700 rounded-lg px-3 py-2 text-white focus:outline-none focus:border-amber-500">
                </div>
                <div>
                    <label class="block text-xs text-slate-400 mb-1">Nominal (Rp)</label>
                    <input type="number" name="amount_expense" step="0.01" required class="w-full bg-slate-900 border border-slate-700 rounded-lg px-3 py-2 text-white focus:outline-none focus:border-amber-500">
                </div>
                <div>
                    <label class="block text-xs text-slate-400 mb-1">Keterangan</label>
                    <textarea name="description_expense" rows="2" class="w-full bg-slate-900 border border-slate-700 rounded-lg px-3 py-2 text-white focus:outline-none focus:border-amber-500"></textarea>
                </div>
                <button type="submit" class="w-full bg-amber-500 hover:bg-amber-400 text-slate-900 font-bold py-2 rounded-lg transition-all mt-2">Catat Biaya</button>
            </form>
        </div>

        <div class="glass p-6 rounded-2xl border-t-4 border-blue-500 shadow-lg shadow-blue-500/5">
            <h3 class="text-lg font-bold text-white mb-4">Rekrutmen Karyawan</h3>
            <form method="post" class="space-y-4">
                <input type="hidden" name="action" value="employee">
                <div>
                    <label class="block text-xs text-slate-400 mb-1">Nama Lengkap</label>
                    <input type="text" name="employee_name" required class="w-full bg-slate-900 border border-slate-700 rounded-lg px-3 py-2 text-white focus:outline-none focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-xs text-slate-400 mb-1">Penempatan Job</label>
                    <select name="job_id" class="w-full bg-slate-900 border border-slate-700 rounded-lg px-3 py-2 text-white focus:outline-none focus:border-blue-500">
                        <option value="0">Staff Umum</option>
                        <?php foreach ($jobs as $job): ?>
                            <option value="<?=$job['id']?>"><?=htmlspecialchars($job['title'])?> (<?=htmlspecialchars($job['location'])?>)</option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label class="block text-xs text-slate-400 mb-1">Gaji Bulanan (Rp)</label>
                    <input type="number" name="salary" step="0.01" required class="w-full bg-slate-900 border border-slate-700 rounded-lg px-3 py-2 text-white focus:outline-none focus:border-blue-500">
                </div>
                <button type="submit" class="w-full bg-blue-500 hover:bg-blue-400 text-white font-bold py-2 rounded-lg transition-all mt-2">Daftarkan Karyawan</button>
            </form>
        </div>
    </div>

    <div class="grid md:grid-cols-2 gap-6 mb-8">
        <div class="glass rounded-2xl overflow-hidden flex flex-col h-96">
            <div class="px-6 py-4 border-b border-slate-700 bg-slate-800/50">
                <h3 class="text-lg font-bold">Riwayat Transaksi Bank</h3>
            </div>
            <div class="overflow-y-auto flex-1 p-0">
                <table class="w-full text-left text-sm">
                    <thead class="bg-slate-900/50 text-slate-400 sticky top-0">
                        <tr><th class="px-6 py-3 font-medium">Jenis</th><th class="px-6 py-3 font-medium">Nominal</th><th class="px-6 py-3 font-medium">Status</th><th class="px-6 py-3 font-medium">Aksi</th></tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800">
                        <?php if(!$transactions): ?><tr><td colspan="4" class="px-6 py-8 text-center text-slate-500">Belum ada transaksi</td></tr><?php endif; ?>
                        <?php foreach ($transactions as $tx): ?>
                        <tr class="table-row-hover transition-colors">
                            <td class="px-6 py-3"><span class="px-2 py-1 rounded text-xs font-bold <?= $tx['type'] == 'income' ? 'bg-emerald-500/10 text-emerald-400' : 'bg-rose-500/10 text-rose-400' ?>"><?=strtoupper($tx['type'])?></span></td>
                            <td class="px-6 py-3 text-white">Rp <?=number_format($tx['amount'],0,',','.')?></td>
                            <td class="px-6 py-3">
                                <?php if($tx['status'] == 'completed'): ?>
                                    <span class="text-emerald-400 text-xs">Selesai</span>
                                <?php else: ?>
                                    <span class="text-slate-500 text-xs">Dibatalkan</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-3">
                                <?php if ($tx['status'] === 'completed'): ?>
                                <form method="post" style="margin:0;">
                                    <input type="hidden" name="action" value="cancel">
                                    <input type="hidden" name="tx_id" value="<?=$tx['id']?>">
                                    <button type="submit" class="text-rose-500 hover:text-rose-400 font-medium text-xs">Batalkan</button>
                                </form>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="glass rounded-2xl overflow-hidden flex flex-col h-96">
            <div class="px-6 py-4 border-b border-slate-700 bg-slate-800/50">
                <h3 class="text-lg font-bold">Daftar Karyawan Aktif</h3>
            </div>
            <div class="overflow-y-auto flex-1 p-0">
                <table class="w-full text-left text-sm">
                    <thead class="bg-slate-900/50 text-slate-400 sticky top-0">
                        <tr><th class="px-6 py-3 font-medium">Nama</th><th class="px-6 py-3 font-medium">Posisi Job</th><th class="px-6 py-3 font-medium">Gaji/Bulan</th></tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800">
                        <?php if(!$employees): ?><tr><td colspan="3" class="px-6 py-8 text-center text-slate-500">Belum ada data karyawan</td></tr><?php endif; ?>
                        <?php foreach ($employees as $e): ?>
                        <tr class="table-row-hover transition-colors">
                            <td class="px-6 py-3 font-bold text-white"><?=htmlspecialchars($e['name'])?></td>
                            <td class="px-6 py-3 text-slate-400"><?=htmlspecialchars($e['job_title'] ?: 'Staff Umum');?></td>
                            <td class="px-6 py-3 text-cyan-400 font-mono">Rp <?=number_format($e['salary'],0,',','.')?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>

</body>
</html>
