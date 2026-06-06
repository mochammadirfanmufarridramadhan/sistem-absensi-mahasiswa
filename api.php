<?php
require_once __DIR__ . '/functions.php';

function respond(array $payload): void {
    header('Content-Type: application/json; charset=UTF-8');
    echo json_encode($payload, JSON_UNESCAPED_UNICODE);
    exit;
}

$action = $_GET['action'] ?? '';
$body = json_decode(file_get_contents('php://input'), true);
$params = is_array($body) ? $body : $_POST;

try {
    $pdo = getConnection();
} catch (PDOException $e) {
    respond(['success' => false, 'message' => 'Koneksi database gagal: ' . $e->getMessage()]);
}

switch ($action) {
    case 'login':
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') respond(['success' => false, 'message' => 'Metode harus POST.']);
        $user = trim((string)($params['user'] ?? ''));
        $pass = (string)($params['pass'] ?? '');
        if (!$user || !$pass) respond(['success' => false, 'message' => 'User dan password wajib diisi.']);

        // built-in admin/dosen
        if (($user === 'admin' || $user === 'dosen') && $pass === '1234') {
            $role = $user === 'admin' ? 'Administrator' : 'Dosen Wali';
            respond(['success' => true, 'user' => ['name' => $role === 'Administrator' ? 'Admin Sistem' : 'Dr. Budi Santoso', 'role' => $role, 'avatar' => $user === 'admin' ? 'AD' : 'DS']]);
        }

        // lookup user by nim or email
        try {
            ensureUsersTable($pdo);
            $stmt = $pdo->prepare('SELECT id,nim,nama,email,password,role FROM users WHERE nim = :u OR email = :u LIMIT 1');
            $stmt->execute([':u' => $user]);
            $urow = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$urow || !password_verify($pass, $urow['password'])) respond(['success' => false, 'message' => 'Username atau password salah.']);
            unset($urow['password']);
            respond(['success' => true, 'user' => $urow]);
        } catch (PDOException $e) {
            respond(['success' => false, 'message' => 'Gagal autentikasi: ' . $e->getMessage()]);
        }
        break;
    case 'list':
        $rows = $pdo->query('SELECT id, nim, nama, prodi, ipk, absensi, ekonomi, pekerjaan, semester, sks, beasiswa, risiko, pct, model FROM mahasiswa ORDER BY id DESC')->fetchAll();
        $tindakan = $pdo->query('SELECT id, tanggal, nim, nama, jenis, dosen, status, catatan FROM tindakan ORDER BY tanggal DESC')->fetchAll();
        $notif = $pdo->query('SELECT id, icon, msg, time, is_read FROM notif ORDER BY id DESC')->fetchAll();
        respond(['success' => true, 'records' => $rows, 'mahasiswa' => $rows, 'tindakan' => $tindakan, 'notif' => $notif]);
        break;

    case 'add':
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            respond(['success' => false, 'message' => 'Metode harus POST.']);
        }
        $required = ['nim','nama','prodi','ipk','absensi','ekonomi'];
        foreach ($required as $field) {
            if (!isset($params[$field]) || trim((string)$params[$field]) === '') {
                respond(['success' => false, 'message' => 'Field ' . $field . ' wajib diisi.']);
            }
        }
        $nim = trim((string)$params['nim']);
        $nama = trim((string)$params['nama']);
        $prodi = trim((string)$params['prodi']);
        $ipk = (float)$params['ipk'];
        $absensi = (int)$params['absensi'];
        $ekonomi = trim((string)$params['ekonomi']);
        $pekerjaan = trim((string)($params['pekerjaan'] ?? '')); 
        $semester = isset($params['semester']) ? (int)$params['semester'] : 1;
        $sks = isset($params['sks']) ? (int)$params['sks'] : 0;
        $beasiswa = trim((string)($params['beasiswa'] ?? 'Tidak'));

        if ($ipk < 0 || $ipk > 4 || $absensi < 0 || $absensi > 100) {
            respond(['success' => false, 'message' => 'Nilai IPK atau absensi tidak valid.']);
        }

        $pct = calculateRisk($ipk, $absensi, $ekonomi);
        $risiko = $pct >= 65 ? 'Tinggi' : ($pct >= 40 ? 'Sedang' : 'Rendah');
        $models = ['Random Forest', 'XGBoost', 'Logistic Regression'];
        $model = $models[array_rand($models)];

        try {
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
            $id = (int)$pdo->lastInsertId();
            $record = ['id' => $id,'nim' => $nim,'nama' => $nama,'prodi' => $prodi,'ipk' => $ipk,'absensi' => $absensi,'ekonomi' => $ekonomi,'pekerjaan' => $pekerjaan,'semester' => $semester,'sks' => $sks,'beasiswa' => $beasiswa,'risiko' => $risiko,'pct' => $pct,'model' => $model];
            respond(['success' => true, 'record' => $record]);
        } catch (PDOException $e) {
            if ($e->getCode() === '23000') {
                respond(['success' => false, 'message' => 'NIM sudah terdaftar.']);
            }
            respond(['success' => false, 'message' => 'Gagal menyimpan data: ' . $e->getMessage()]);
        }
        break;

    case 'reset_request':
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') respond(['success' => false, 'message' => 'Metode harus POST.']);
        $email = trim((string)($params['email'] ?? ''));
        if (!$email) respond(['success' => false, 'message' => 'Email wajib diisi.']);
        try {
            ensureUsersTable($pdo);
            $stmt = $pdo->prepare('SELECT id,nama FROM users WHERE email = :email LIMIT 1');
            $stmt->execute([':email'=>$email]);
            $u = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$u) respond(['success' => false, 'message' => 'Email tidak ditemukan.']);
            $token = bin2hex(random_bytes(16));
            $expires = (new DateTime('+1 hour'))->format('Y-m-d H:i:s');
            $upd = $pdo->prepare('UPDATE users SET reset_token=:t, reset_expires=:e WHERE id=:id');
            $upd->execute([':t'=>$token,':e'=>$expires,':id'=>$u['id']]);
            respond(['success'=>true,'token'=>$token]);
        } catch (PDOException $e) { respond(['success'=>false,'message'=>'Gagal: '.$e->getMessage()]); }
        break;

    case 'update':
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            respond(['success' => false, 'message' => 'Metode harus POST.']);
        }
        $id = isset($params['id']) ? (int)$params['id'] : 0;
        if (!$id) {
            respond(['success' => false, 'message' => 'ID mahasiswa tidak ditemukan.']);
        }
        $required = ['nim','nama','prodi','ipk','absensi','ekonomi'];
        foreach ($required as $field) {
            if (!isset($params[$field]) || trim((string)$params[$field]) === '') {
                respond(['success' => false, 'message' => 'Field ' . $field . ' wajib diisi.']);
            }
        }
        $nim = trim((string)$params['nim']);
        $nama = trim((string)$params['nama']);
        $prodi = trim((string)$params['prodi']);
        $ipk = (float)$params['ipk'];
        $absensi = (int)$params['absensi'];
        $ekonomi = trim((string)$params['ekonomi']);
        $pekerjaan = trim((string)($params['pekerjaan'] ?? ''));
        $semester = isset($params['semester']) ? (int)$params['semester'] : 1;
        $sks = isset($params['sks']) ? (int)$params['sks'] : 0;
        $beasiswa = trim((string)($params['beasiswa'] ?? 'Tidak'));

        if ($ipk < 0 || $ipk > 4 || $absensi < 0 || $absensi > 100) {
            respond(['success' => false, 'message' => 'Nilai IPK atau absensi tidak valid.']);
        }

        $pct = calculateRisk($ipk, $absensi, $ekonomi);
        $risiko = $pct >= 65 ? 'Tinggi' : ($pct >= 40 ? 'Sedang' : 'Rendah');

        try {
            $stmt = $pdo->prepare('UPDATE mahasiswa SET nim=:nim,nama=:nama,prodi=:prodi,ipk=:ipk,absensi=:absensi,ekonomi=:ekonomi,pekerjaan=:pekerjaan,semester=:semester,sks=:sks,beasiswa=:beasiswa,risiko=:risiko,pct=:pct,model=:model WHERE id=:id');
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
                ':model' => trim((string)($params['model'] ?? 'Random Forest')),
                ':id' => $id,
            ]);
            $record = ['id'=>$id,'nim'=>$nim,'nama'=>$nama,'prodi'=>$prodi,'ipk'=>$ipk,'absensi'=>$absensi,'ekonomi'=>$ekonomi,'pekerjaan'=>$pekerjaan,'semester'=>$semester,'sks'=>$sks,'beasiswa'=>$beasiswa,'risiko'=>$risiko,'pct'=>$pct,'model'=>trim((string)($params['model'] ?? 'Random Forest'))];
            respond(['success' => true, 'record' => $record]);
        } catch (PDOException $e) {
            if ($e->getCode() === '23000') {
                respond(['success' => false, 'message' => 'NIM sudah digunakan oleh mahasiswa lain.']);
            }
            respond(['success' => false, 'message' => 'Gagal memperbarui data: ' . $e->getMessage()]);
        }
        break;

    case 'delete':
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            respond(['success' => false, 'message' => 'Metode harus POST.']);
        }
        $id = isset($params['id']) ? (int)$params['id'] : 0;
        if (!$id) {
            respond(['success' => false, 'message' => 'ID mahasiswa tidak ditemukan.']);
        }
        try {
            $stmt = $pdo->prepare('DELETE FROM mahasiswa WHERE id = :id');
            $stmt->execute([':id' => $id]);
            respond(['success' => true]);
        } catch (PDOException $e) {
            respond(['success' => false, 'message' => 'Gagal menghapus data: ' . $e->getMessage()]);
        }
        break;

    default:
        respond(['success' => false, 'message' => 'Aksi API tidak dikenali.']);
}
