<?php
require_once __DIR__ . '/functions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    respondJson(['success' => false, 'message' => 'Endpoint hanya menerima metode POST.']);
}

$body = getJsonInput();
$params = !empty($body) ? $body : $_POST;

$nim = getPostValue($params, 'nim');
$nama = getPostValue($params, 'nama');
$prodi = getPostValue($params, 'prodi');
$ipk = isset($params['ipk']) ? (float) $params['ipk'] : null;
$absensi = isset($params['absensi']) ? (int) $params['absensi'] : null;
$ekonomi = getPostValue($params, 'ekonomi');
$pekerjaan = getPostValue($params, 'pekerjaan');
$semester = isset($params['semester']) ? (int) $params['semester'] : 1;
$sks = isset($params['sks']) ? (int) $params['sks'] : 0;
$beasiswa = getPostValue($params, 'beasiswa');
$save = isset($params['save']) && ((string) $params['save'] === '1' || strtolower((string) $params['save']) === 'true');
$model = getPostValue($params, 'model');

if (!$nim || !$nama || !$prodi || $ipk === null || $absensi === null || !$ekonomi) {
    respondJson(['success' => false, 'message' => 'Data prediksi tidak lengkap.']);
}

if ($ipk < 0 || $ipk > 4 || $absensi < 0 || $absensi > 100) {
    respondJson(['success' => false, 'message' => 'Nilai IPK atau absensi tidak valid.']);
}

if (!$model) {
    $models = ['Random Forest', 'XGBoost', 'Logistic Regression'];
    $model = $models[array_rand($models)];
}

$pct = calculateRisk($ipk, $absensi, $ekonomi);
$risiko = $pct >= 65 ? 'Tinggi' : ($pct >= 40 ? 'Sedang' : 'Rendah');

$record = [
    'nim' => $nim,
    'nama' => $nama,
    'prodi' => $prodi,
    'ipk' => $ipk,
    'absensi' => $absensi,
    'ekonomi' => $ekonomi,
    'pekerjaan' => $pekerjaan,
    'semester' => $semester,
    'sks' => $sks,
    'beasiswa' => $beasiswa,
    'risiko' => $risiko,
    'pct' => $pct,
    'model' => $model,
];

if ($save) {
    try {
        $pdo = getConnection();
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
            ':pct' => $pct,
            ':model' => $model,
        ]);
        $record['saved'] = true;
        $record['id'] = (int)$pdo->lastInsertId();
    } catch (PDOException $e) {
        respondJson(['success' => false, 'message' => 'Gagal menyimpan data: ' . $e->getMessage()]);
    }
}

respondJson(['success' => true, 'message' => 'Prediksi berhasil', 'record' => $record]);
