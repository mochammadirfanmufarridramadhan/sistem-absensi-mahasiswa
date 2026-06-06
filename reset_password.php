<?php
require_once __DIR__ . '/functions.php';
$message = ''; $showForm = false; $token = $_GET['token'] ?? '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['token'] ?? '';
    $pw = $_POST['password'] ?? '';
    $pw2 = $_POST['confirm_password'] ?? '';
    if (!$token || !$pw || !$pw2) {
        $message = 'Semua field wajib diisi.';
    } elseif ($pw !== $pw2) {
        $message = 'Password dan konfirmasi tidak sama.';
    } elseif (strlen($pw) < 6) {
        $message = 'Password minimal 6 karakter.';
    } else {
        try {
            $pdo = getConnection();
            ensureUsersTable($pdo);
            $stmt = $pdo->prepare('SELECT id,reset_expires FROM users WHERE reset_token = :t LIMIT 1');
            $stmt->execute([':t'=>$token]);
            $u = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$u) { $message = 'Token tidak valid.'; }
            else {
                $expires = new DateTime($u['reset_expires']);
                $now = new DateTime();
                if ($expires < $now) { $message = 'Token sudah kadaluarsa.'; }
                else {
                    $hash = password_hash($pw, PASSWORD_DEFAULT);
                    $upd = $pdo->prepare('UPDATE users SET password=:pw, reset_token=NULL, reset_expires=NULL WHERE id=:id');
                    $upd->execute([':pw'=>$hash, ':id'=>$u['id']]);
                    $message = 'Password berhasil direset. Silakan login.';
                }
            }
        } catch (PDOException $e) { $message = 'Terjadi kesalahan: ' . $e->getMessage(); }
    }
} else {
    if ($token) {
        try {
            $pdo = getConnection(); ensureUsersTable($pdo);
            $stmt = $pdo->prepare('SELECT id,reset_expires FROM users WHERE reset_token = :t LIMIT 1'); $stmt->execute([':t'=>$token]); $u = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($u) {
                $expires = new DateTime($u['reset_expires']);
                if ($expires >= new DateTime()) $showForm = true;
                else $message = 'Token sudah kadaluarsa.';
            } else {
                $message = 'Token tidak valid.';
            }
        } catch (PDOException $e) { $message = 'Terjadi kesalahan: ' . $e->getMessage(); }
    }
}
?><!DOCTYPE html>
<html lang="id"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Reset Password</title><link rel="stylesheet" href="style.css"></head><body>
<div id="loginPage">
  <div class="login-card auth-card">
    <div class="login-logo">🔐</div>
    <h2>Reset Password</h2>
    <p class="sub">Masukkan email terdaftar untuk mengganti kata sandi.</p>
    <?php if ($message): ?><div class="message success"><?php echo htmlspecialchars($message, ENT_QUOTES); ?></div><?php endif; ?>
    <?php if ($showForm): ?>
    <form method="post" action="">
      <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
      <div class="form-group"><label>Password Baru</label><input type="password" name="password" placeholder="Password baru"></div>
      <div class="form-group"><label>Konfirmasi Password</label><input type="password" name="confirm_password" placeholder="Konfirmasi password"></div>
      <button type="submit">Simpan Password Baru</button>
    </form>
    <?php else: ?>
    <p>Jika Anda memiliki token reset, buka link yang dikirim email. Atau gunakan halaman <a href="forget_password.php">Lupa Password</a> untuk membuat token baru.</p>
    <?php endif; ?>
    <p class="forgot">Kembali ke <a href="index.php">Login</a></p>
  </div>
</div>
</body></html>