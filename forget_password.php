<?php
require_once __DIR__ . '/functions.php';
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_var(trim($_POST['email'] ?? ''), FILTER_VALIDATE_EMAIL);
    if (!$email) {
        $message = 'Masukkan email yang valid.';
    } else {
        try {
            $pdo = getConnection();
            ensureUsersTable($pdo);
            $stmt = $pdo->prepare('SELECT id,nama FROM users WHERE email = :email LIMIT 1');
            $stmt->execute([':email'=>$email]);
            $u = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$u) {
                $message = 'Email tidak ditemukan.';
            } else {
                $token = bin2hex(random_bytes(16));
                $expires = (new DateTime('+1 hour'))->format('Y-m-d H:i:s');
                $upd = $pdo->prepare('UPDATE users SET reset_token=:t, reset_expires=:e WHERE id=:id');
                $upd->execute([':t'=>$token,':e'=>$expires,':id'=>$u['id']]);
                // Try to send email (may not work on local). Also show token with instructions.
                $resetLink = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['REQUEST_URI']) . "/reset_password.php?token={$token}";
                $message = 'Link reset dibuat. Jika email tidak berjalan di localhost, buka link ini: <a href="' . htmlspecialchars($resetLink) . '">' . htmlspecialchars($resetLink) . '</a>';
            }
        } catch (PDOException $e) {
            $message = 'Terjadi kesalahan: ' . $e->getMessage();
        }
    }
}
?><!DOCTYPE html>
<html lang="id"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Lupa Password</title><link rel="stylesheet" href="style.css"></head><body>
<div id="loginPage">
  <div class="login-card auth-card">
    <div class="login-logo">🔐</div>
    <h2>Reset Password</h2>
    <p class="sub">Masukkan email terdaftar untuk menerima link reset.</p>
    <?php if ($message): ?><div class="message success"><?php echo $message; ?></div><?php endif; ?>
    <form method="post" action="">
      <div class="form-group"><label>Email</label><input type="email" name="email" placeholder="Email terdaftar"></div>
      <button type="submit">Kirim Link Reset</button>
    </form>
    <p class="forgot">Kembali ke <a href="index.php">Login</a></p>
  </div>
</div>
</body></html>