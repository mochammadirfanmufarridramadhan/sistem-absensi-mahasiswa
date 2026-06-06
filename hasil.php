<?php
require_once __DIR__ . '/functions.php';
$pdo = getConnection();
$stats = $pdo->query('SELECT COUNT(*) as total, AVG(ipk) as avg_ipk, AVG(absensi) as avg_absensi, AVG(pct) as avg_pct FROM mahasiswa')->fetch(PDO::FETCH_ASSOC);
$distribution = $pdo->query('SELECT risiko, COUNT(*) as total FROM mahasiswa GROUP BY risiko')->fetchAll(PDO::FETCH_ASSOC);
$modelStats = $pdo->query('SELECT model, COUNT(*) as total FROM mahasiswa GROUP BY model')->fetchAll(PDO::FETCH_ASSOC);
$topHigh = $pdo->query('SELECT nim, nama, prodi, ipk, absensi, risiko, pct, model FROM mahasiswa ORDER BY pct DESC LIMIT 8')->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Hasil Analisa Prediksi</title>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="style.css">
</head>
<body>
<div class="page">
  <h1>Hasil Analisa Sistem Prediksi</h1>
  <div class="grid">
    <div class="stat"><strong>Total Mahasiswa</strong><div style="font-size:2rem;margin-top:10px;"><?php echo (int)$stats['total']; ?></div></div>
    <div class="stat"><strong>Rata-rata IPK</strong><div style="font-size:2rem;margin-top:10px;"><?php echo $stats['avg_ipk'] !== null ? number_format($stats['avg_ipk'], 2) : '0.00'; ?></div></div>
    <div class="stat"><strong>Rata-rata Absensi</strong><div style="font-size:2rem;margin-top:10px;"><?php echo $stats['avg_absensi'] !== null ? number_format($stats['avg_absensi'], 1) . '%' : '0%'; ?></div></div>
    <div class="stat"><strong>Rata-rata Probabilitas</strong><div style="font-size:2rem;margin-top:10px;"><?php echo $stats['avg_pct'] !== null ? number_format($stats['avg_pct'], 1) . '%' : '0%'; ?></div></div>
  </div>

  <div class="card">
    <h2>Distribusi Risiko</h2>
    <?php foreach ($distribution as $row): ?>
      <?php $cls = strtolower($row['risiko']); ?>
      <span class="badge <?php echo htmlspecialchars($cls, ENT_QUOTES); ?>"><?php echo htmlspecialchars($row['risiko'], ENT_QUOTES); ?>: <?php echo intval($row['total']); ?></span>
    <?php endforeach; ?>
  </div>

  <div class="card">
    <h2>Model Prediksi</h2>
    <table class="table">
      <thead><tr><th>Model</th><th>Jumlah Data</th></tr></thead>
      <tbody>
        <?php foreach ($modelStats as $row): ?>
          <tr><td><?php echo htmlspecialchars($row['model'], ENT_QUOTES); ?></td><td><?php echo intval($row['total']); ?></td></tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>

  <div class="card">
    <h2>Top Mahasiswa Risiko Tertinggi</h2>
    <table class="table">
      <thead><tr><th>NIM</th><th>Nama</th><th>Prodi</th><th>IPK</th><th>Absensi</th><th>Risiko</th><th>Probabilitas</th><th>Model</th></tr></thead>
      <tbody>
        <?php foreach ($topHigh as $row): ?>
          <?php $badge = strtolower($row['risiko']); ?>
          <tr>
            <td><?php echo htmlspecialchars($row['nim'], ENT_QUOTES); ?></td>
            <td><?php echo htmlspecialchars($row['nama'], ENT_QUOTES); ?></td>
            <td><?php echo htmlspecialchars($row['prodi'], ENT_QUOTES); ?></td>
            <td><?php echo htmlspecialchars(number_format($row['ipk'],2), ENT_QUOTES); ?></td>
            <td><?php echo intval($row['absensi']); ?>%</td>
            <td><span class="badge <?php echo $badge; ?>"><?php echo htmlspecialchars($row['risiko'], ENT_QUOTES); ?></span></td>
            <td><?php echo intval($row['pct']); ?>%</td>
            <td><?php echo htmlspecialchars($row['model'], ENT_QUOTES); ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>

  <div class="card">
    <h2>Analisa Algoritma Prediksi</h2>
    <p>Prediksi risiko dropout menggunakan formula sederhana berdasarkan IPK, absensi, dan kondisi ekonomi:</p>
    <ul>
      <li>IPK lebih rendah meningkatkan skor risiko.</li>
      <li>Absensi rendah memberikan tambahan bobot risiko.</li>
      <li>Ekonomi rendah menambah faktor risiko karena tekanan finansial.</li>
    </ul>
    <p>Model yang digunakan untuk simulasi adalah Random Forest, XGBoost, dan Logistic Regression sebagai representasi model ML dalam dashboard.</p>
  </div>

  <p style="color:#8b949e;">Gunakan <a href="input.php">Input</a> untuk menambahkan data baru atau <a href="register.php">Register</a> mahasiswa.</p>
</div>
</body>
</html>
