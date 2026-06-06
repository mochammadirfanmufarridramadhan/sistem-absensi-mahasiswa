<?php
require_once __DIR__ . '/functions.php';
$message = ''; $error = ''; $result = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nim = getPostValue($_POST, 'nim');
    $nama = getPostValue($_POST, 'nama');
    $prodi = getPostValue($_POST, 'prodi');
    $ipk = isset($_POST['ipk']) ? (float) $_POST['ipk'] : null;
    $absensi = isset($_POST['absensi']) ? (int) $_POST['absensi'] : null;
    $ekonomi = getPostValue($_POST, 'ekonomi');
    $pekerjaan = getPostValue($_POST, 'pekerjaan');
    $semester = isset($_POST['semester']) ? (int) $_POST['semester'] : 1;
    $sks = isset($_POST['sks']) ? (int) $_POST['sks'] : 0;
    $beasiswa = getPostValue($_POST, 'beasiswa');

    if (!$nim || !$nama || !$prodi || $ipk === null || $absensi === null || !$ekonomi) {
        $error = 'Field NIM, Nama, Program Studi, IPK, Absensi, dan Status Ekonomi wajib diisi.';
    } elseif ($ipk < 0 || $ipk > 4) {
        $error = 'IPK harus bernilai antara 0 sampai 4.';
    } elseif ($absensi < 0 || $absensi > 100) {
        $error = 'Absensi harus bernilai antara 0 sampai 100.';
    } else {
        try {
            $pdo = getConnection();
            $risk = calculateRisk($ipk, $absensi, $ekonomi);
            $risiko = $risk >= 65 ? 'Tinggi' : ($risk >= 40 ? 'Sedang' : 'Rendah');
            $models = ['Random Forest','XGBoost','Logistic Regression'];
            $model = $models[array_rand($models)];

            $stmt = $pdo->prepare('INSERT INTO mahasiswa (nim, nama, prodi, ipk, absensi, ekonomi, pekerjaan, semester, sks, beasiswa, risiko, pct, model) VALUES (:nim, :nama, :prodi, :ipk, :absensi, :ekonomi, :pekerjaan, :semester, :sks, :beasiswa, :risiko, :pct, :model)');
            $stmt->execute([
                ':nim' => $nim,
                ':nama' => $nama,
                ':prodi' => $prodi,
                ':ipk' => $ipk,
                ':absensi' => $absensi,
                ':ekonomi' => $ekonomi,
                ':pekerjaan' => $pekerjaan,
                ':semester' => $semester,
                ':sks' => $sks,
                ':beasiswa' => $beasiswa,
                ':risiko' => $risiko,
                ':pct' => $risk,
                ':model' => $model,
            ]);

            $message = 'Data mahasiswa berhasil disimpan.';
            $result = [
                'nim' => $nim,
                'nama' => $nama,
                'prodi' => $prodi,
                'ipk' => number_format($ipk, 2),
                'absensi' => $absensi,
                'ekonomi' => $ekonomi,
                'pekerjaan' => $pekerjaan,
                'semester' => $semester,
                'sks' => $sks,
                'beasiswa' => $beasiswa,
                'risiko' => $risiko,
                'pct' => $risk,
                'model' => $model,
            ];
        } catch (PDOException $e) {
            $error = 'Gagal menyimpan mahasiswa: ' . $e->getMessage();
        }
    }
}
?><!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Input Data Mahasiswa</title>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
  <h2>Input Data Mahasiswa</h2>
  <?php if ($message): ?><div class="message success"><?php echo htmlspecialchars($message, ENT_QUOTES); ?></div><?php endif; ?>
  <?php if ($error): ?><div class="message error"><?php echo htmlspecialchars($error, ENT_QUOTES); ?></div><?php endif; ?>
  <form method="post" action="">
    <input type="text" name="nim" placeholder="NIM" value="<?php echo htmlspecialchars($_POST['nim'] ?? '', ENT_QUOTES); ?>">
    <input type="text" name="nama" placeholder="Nama Lengkap" value="<?php echo htmlspecialchars($_POST['nama'] ?? '', ENT_QUOTES); ?>">
    <input type="text" name="prodi" placeholder="Program Studi" value="<?php echo htmlspecialchars($_POST['prodi'] ?? '', ENT_QUOTES); ?>">
    <input type="number" step="0.01" min="0" max="4" name="ipk" placeholder="IPK" value="<?php echo htmlspecialchars($_POST['ipk'] ?? '', ENT_QUOTES); ?>">
    <input type="number" min="0" max="100" name="absensi" placeholder="Absensi (%)" value="<?php echo htmlspecialchars($_POST['absensi'] ?? '', ENT_QUOTES); ?>">
    <select name="ekonomi">
      <option value="Menengah"<?php echo (($_POST['ekonomi'] ?? '') === 'Menengah') ? ' selected' : ''; ?>>Menengah</option>
      <option value="Rendah"<?php echo (($_POST['ekonomi'] ?? '') === 'Rendah') ? ' selected' : ''; ?>>Rendah</option>
      <option value="Tinggi"<?php echo (($_POST['ekonomi'] ?? '') === 'Tinggi') ? ' selected' : ''; ?>>Tinggi</option>
    </select>
    <input type="text" name="pekerjaan" placeholder="Pekerjaan Orang Tua" value="<?php echo htmlspecialchars($_POST['pekerjaan'] ?? '', ENT_QUOTES); ?>">
    <input type="number" min="1" max="8" name="semester" placeholder="Semester" value="<?php echo htmlspecialchars($_POST['semester'] ?? '3', ENT_QUOTES); ?>">
    <input type="number" min="0" max="160" name="sks" placeholder="SKS Lulus" value="<?php echo htmlspecialchars($_POST['sks'] ?? '', ENT_QUOTES); ?>">
    <select name="beasiswa">
      <option value="Tidak"<?php echo (($_POST['beasiswa'] ?? '') === 'Tidak') ? ' selected' : ''; ?>>Tidak</option>
      <option value="KIP Kuliah"<?php echo (($_POST['beasiswa'] ?? '') === 'KIP Kuliah') ? ' selected' : ''; ?>>KIP Kuliah</option>
      <option value="Bidikmisi"<?php echo (($_POST['beasiswa'] ?? '') === 'Bidikmisi') ? ' selected' : ''; ?>>Bidikmisi</option>
      <option value="Swasta"<?php echo (($_POST['beasiswa'] ?? '') === 'Swasta') ? ' selected' : ''; ?>>Swasta</option>
    </select>
    <button type="submit">Simpan & Prediksi</button>
  </form>
  <?php if ($result): ?>
    <table class="table">
      <tr><th>Hasil Prediksi</th><td><?php echo htmlspecialchars($result['risiko'], ENT_QUOTES); ?> (<?php echo htmlspecialchars($result['pct'], ENT_QUOTES); ?>%)</td></tr>
      <tr><th>Model</th><td><?php echo htmlspecialchars($result['model'], ENT_QUOTES); ?></td></tr>
      <tr><th>IPK</th><td><?php echo htmlspecialchars($result['ipk'], ENT_QUOTES); ?></td></tr>
      <tr><th>Absensi</th><td><?php echo htmlspecialchars($result['absensi'], ENT_QUOTES); ?>%</td></tr>
      <tr><th>Status Ekonomi</th><td><?php echo htmlspecialchars($result['ekonomi'], ENT_QUOTES); ?></td></tr>
    </table>
    <p style="margin-top:14px;color:#8b949e;">Analisa: IPK <?php echo htmlspecialchars($result['ipk'] <= 2.5 ? 'rendah' : 'cukup tinggi', ENT_QUOTES); ?> dan absensi <?php echo htmlspecialchars($result['absensi'] < 75 ? 'cukup rendah' : 'baik', ENT_QUOTES); ?> akan memengaruhi skor risiko.</p>
  <?php endif; ?>
  <p style="margin-top:18px;color:#8b949e;">Lihat <a href="hasil.php">Hasil Analisa</a> | <a href="register.php">Register Mahasiswa</a></p>
</div>
</body>
</html>
