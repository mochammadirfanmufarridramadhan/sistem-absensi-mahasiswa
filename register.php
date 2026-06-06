<?php
require_once __DIR__ . '/functions.php';
$message = ''; $error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nim = getPostValue($_POST, 'nim');
    $nama = getPostValue($_POST, 'nama');
    $email = getPostValue($_POST, 'email');
    $password = getPostValue($_POST, 'password');
    $confirm = getPostValue($_POST, 'confirm_password');

    if (!$nim || !$nama || !$email || !$password || !$confirm) {
        $error = 'Semua field wajib diisi.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Email tidak valid.';
    } elseif ($password !== $confirm) {
        $error = 'Password dan konfirmasi password tidak sama.';
    } elseif (strlen($password) < 6) {
        $error = 'Password minimal 6 karakter.';
    } else {
        try {
            $pdo = getConnection();
            ensureUsersTable($pdo);
            $stmt = $pdo->prepare('INSERT INTO users (nim, nama, email, password, role) VALUES (:nim, :nama, :email, :password, :role)');
            $stmt->execute([
                ':nim' => $nim,
                ':nama' => $nama,
                ':email' => $email,
                ':password' => password_hash($password, PASSWORD_DEFAULT),
                ':role' => 'mahasiswa'
            ]);
            $message = 'Registrasi berhasil. Silakan login atau lanjutkan input data mahasiswa.';
        } catch (PDOException $e) {
            if ($e->getCode() === '23000') {
                $error = 'NIM atau email sudah terdaftar.';
            } else {
                $error = 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage();
            }
        }
    }
}
?><!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Register Mahasiswa</title>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="style.css">
</head>
<body>
<div id="loginPage">
  <div class="login-card auth-card">
    <div class="login-logo">🎓</div>
    <h2>Register Mahasiswa</h2>
    <p class="sub">Buat akun mahasiswa baru dan mulai akses sistem.</p>
    <?php if ($message): ?><div class="message success"><?php echo htmlspecialchars($message, ENT_QUOTES | ENT_SUBSTITUTE); ?></div><?php endif; ?>
    <?php if ($error): ?><div class="message error"><?php echo htmlspecialchars($error, ENT_QUOTES | ENT_SUBSTITUTE); ?></div><?php endif; ?>
    <form method="post" action="">
      <div class="form-group"><label>NIM</label><input type="text" name="nim" placeholder="NIM" value="<?php echo htmlspecialchars($nim ?? '', ENT_QUOTES); ?>"></div>
    <div class="form-group"><label>Nama Lengkap</label><input type="text" name="nama" placeholder="Nama Lengkap" value="<?php echo htmlspecialchars($nama ?? '', ENT_QUOTES); ?>"></div>
    <div class="form-group"><label>Email</label><input type="email" name="email" placeholder="Email" value="<?php echo htmlspecialchars($email ?? '', ENT_QUOTES); ?>"></div>
    <div class="form-group"><label>Password</label><input type="password" name="password" placeholder="Password"></div>
    <div class="form-group"><label>Konfirmasi Password</label><input type="password" name="confirm_password" placeholder="Konfirmasi Password"></div>
    <button type="submit">Daftar</button>
  </form>
  <p class="forgot">Sudah punya akun? <a href="index.php">Login di sini</a></p>
  <p class="forgot" style="margin-top:8px;color:var(--text2);">Kembali ke <a href="index.php">Dashboard</a></p>
</div>
</body>
</html>
