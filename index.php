<?php
require_once __DIR__ . '/config.php';

$dbError = null;
$mahasiswa = [];
$tindakan = [];
$notif = [];
try {
    $pdo = getConnection();
    $mahasiswa = $pdo->query('SELECT id, nim, nama, prodi, ipk, absensi, ekonomi, pekerjaan, semester, sks, beasiswa, risiko, pct, model FROM mahasiswa ORDER BY id DESC')->fetchAll();
    $tindakan = $pdo->query('SELECT id, tanggal, nim, nama, jenis, dosen, status, catatan FROM tindakan ORDER BY tanggal DESC')->fetchAll();
    $notif = $pdo->query('SELECT icon, msg, time, is_read FROM notif ORDER BY id DESC')->fetchAll();
} catch (PDOException $e) {
    $dbError = $e->getMessage();
    $mahasiswa = [
        ['nim'=>'2021101001','nama'=>'Andi Saputra','prodi'=>'Sistem Informasi','ipk'=>2.75,'absensi'=>72,'ekonomi'=>'Menengah','pekerjaan'=>'Wiraswasta','semester'=>3,'sks'=>40,'beasiswa'=>'Tidak','risiko'=>'Tinggi','pct'=>85,'model'=>'Random Forest'],
        ['nim'=>'2021101002','nama'=>'Budi Santoso','prodi'=>'Teknik Informatika','ipk'=>3.10,'absensi'=>80,'ekonomi'=>'Tinggi','pekerjaan'=>'PNS','semester'=>4,'sks'=>55,'beasiswa'=>'Tidak','risiko'=>'Sedang','pct'=>60,'model'=>'XGBoost'],
        ['nim'=>'2021101003','nama'=>'Citra Lestari','prodi'=>'Manajemen','ipk'=>3.70,'absensi'=>92,'ekonomi'=>'Tinggi','pekerjaan'=>'PNS','semester'=>5,'sks'=>72,'beasiswa'=>'Tidak','risiko'=>'Rendah','pct'=>20,'model'=>'Random Forest'],
        ['nim'=>'2021101004','nama'=>'Dwi Kurniawan','prodi'=>'Sistem Informasi','ipk'=>2.40,'absensi'=>68,'ekonomi'=>'Rendah','pekerjaan'=>'Buruh','semester'=>3,'sks'=>38,'beasiswa'=>'KIP Kuliah','risiko'=>'Tinggi','pct'=>75,'model'=>'XGBoost'],
        ['nim'=>'2021101005','nama'=>'Eka Pratama','prodi'=>'Teknik Informatika','ipk'=>3.80,'absensi'=>95,'ekonomi'=>'Menengah','pekerjaan'=>'Wiraswasta','semester'=>6,'sks'=>88,'beasiswa'=>'Tidak','risiko'=>'Rendah','pct'=>15,'model'=>'Random Forest'],
        ['nim'=>'2021101006','nama'=>'Fani Rahayu','prodi'=>'Manajemen','ipk'=>2.90,'absensi'=>75,'ekonomi'=>'Menengah','pekerjaan'=>'TNI/Polri','semester'=>4,'sks'=>52,'beasiswa'=>'Bidikmisi','risiko'=>'Sedang','pct'=>55,'model'=>'Random Forest'],
        ['nim'=>'2021101007','nama'=>'Gilang Permana','prodi'=>'Akuntansi','ipk'=>3.50,'absensi'=>88,'ekonomi'=>'Tinggi','pekerjaan'=>'Wiraswasta','semester'=>5,'sks'=>70,'beasiswa'=>'Tidak','risiko'=>'Rendah','pct'=>22,'model'=>'XGBoost'],
        ['nim'=>'2021101008','nama'=>'Hana Pertiwi','prodi'=>'Sistem Informasi','ipk'=>2.20,'absensi'=>61,'ekonomi'=>'Rendah','pekerjaan'=>'Buruh','semester'=>3,'sks'=>32,'beasiswa'=>'KIP Kuliah','risiko'=>'Tinggi','pct'=>88,'model'=>'Random Forest'],
        ['nim'=>'2021101009','nama'=>'Irfan Hakim','prodi'=>'Teknik Informatika','ipk'=>3.25,'absensi'=>82,'ekonomi'=>'Menengah','pekerjaan'=>'PNS','semester'=>4,'sks'=>58,'beasiswa'=>'Tidak','risiko'=>'Rendah','pct'=>32,'model'=>'Logistic Regression'],
        ['nim'=>'2021101010','nama'=>'Julia Safitri','prodi'=>'Manajemen','ipk'=>2.65,'absensi'=>70,'ekonomi'=>'Rendah','pekerjaan'=>'Buruh','semester'=>4,'sks'=>48,'beasiswa'=>'Bidikmisi','risiko'=>'Sedang','pct'=>62,'model'=>'XGBoost'],
    ];
    $tindakan = [
        ['tanggal'=>'2024-05-10','nim'=>'2021101001','nama'=>'Andi Saputra','jenis'=>'Konseling','dosen'=>'Dr. Ahmad','status'=>'Selesai','catatan'=>'Mahasiswa diberikan arahan karier.'],
        ['tanggal'=>'2024-04-20','nim'=>'2021101004','nama'=>'Dwi Kurniawan','jenis'=>'Monitoring Akademik','dosen'=>'Dr. Siti','status'=>'Selesai','catatan'=>'Monitor nilai mid semester.'],
        ['tanggal'=>'2024-05-18','nim'=>'2021101008','nama'=>'Hana Pertiwi','jenis'=>'Bantuan Beasiswa','dosen'=>'Dr. Budi','status'=>'Dijadwalkan','catatan'=>'Pengajuan tambahan beasiswa.'],
    ];
    $notif = [
        ['icon'=>'🚨','msg'=>'Hana Pertiwi (88%) memerlukan intervensi segera','time'=>'5 menit lalu','is_read'=>0],
        ['icon'=>'🤖','msg'=>'Prediksi batch untuk 1.250 mahasiswa telah selesai','time'=>'2 jam lalu','is_read'=>0],
        ['icon'=>'🛡️','msg'=>'Tindakan konseling Andi Saputra telah disimpan','time'=>'1 hari lalu','is_read'=>1],
        ['icon'=>'🔄','msg'=>'Model ML diperbarui, akurasi 91.4%','time'=>'2 hari lalu','is_read'=>1],
    ];
}
?><!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Sistem Prediksi Dropout Mahasiswa</title>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="style.css">
</head>
<body>

<!-- ════════════ LOGIN PAGE ════════════ -->
<div id="loginPage">
  <div class="login-card">
    <div class="login-logo">🎓</div>
    <h2>Sistem Prediksi Dropout</h2>
    <p class="sub">Mahasiswa · Berbasis Machine Learning</p>
    <div class="login-hint">
      Login sebagai <strong>admin / 1234</strong> atau <strong>dosen / 1234</strong>
    </div>
    <div class="form-group">
      <label>Username</label>
      <input type="text" id="loginUser" placeholder="nim atau email" value="admin">
    </div>
    <div class="form-group">
      <label>Password</label>
      <input type="password" id="loginPass" placeholder="••••••••" value="1234" onkeydown="if(event.key==='Enter')doLogin()">
    </div>
    <button class="btn-login" onclick="doLogin()">Masuk ke Sistem</button>
    <p class="forgot">Lupa Password? <a href="forget_password.php">Reset di sini</a></p>
  </div>
</div>

<!-- ════════════ MAIN APP ════════════ -->
<div id="app">

  <!-- SIDEBAR -->
  <aside class="sidebar">
    <div class="sidebar-brand">
      <div class="brand-icon">🎓</div>
      <div class="brand-text">Dropout<br>Predictor <span>v2.0 ML</span></div>
    </div>
    <nav class="sidebar-nav">
      <div class="nav-label">Menu Utama</div>
      <div class="nav-item active" data-page="dashboard" onclick="showPage('dashboard',this)"><span class="icon">📊</span><span>Dashboard</span></div>
      <div class="nav-item" data-page="dataMahasiswa" onclick="showPage('dataMahasiswa',this)"><span class="icon">👥</span><span>Data Mahasiswa</span></div>
      <div class="nav-item" data-page="inputData" onclick="showPage('inputData',this)"><span class="icon">📝</span><span>Input Data</span></div>
      <div class="nav-label">Machine Learning</div>
      <div class="nav-item" data-page="prediksi" onclick="showPage('prediksi',this)"><span class="icon">🤖</span><span>Prediksi</span></div>
      <div class="nav-item" data-page="visualisasi" onclick="showPage('visualisasi',this)"><span class="icon">📈</span><span>Visualisasi</span></div>
      <div class="nav-label">Tindakan</div>
      <div class="nav-item" data-page="rekomendasi" onclick="showPage('rekomendasi',this)"><span class="icon">💡</span><span>Rekomendasi</span></div>
      <div class="nav-item" data-page="tindakan" onclick="showPage('tindakan',this)"><span class="icon">🛡️</span><span>Tindakan Preventif</span></div>
      <div class="nav-item" data-page="feedback" onclick="showPage('feedback',this)"><span class="icon">🔄</span><span>Feedback & Update</span></div>
    </nav>
    <div class="sidebar-footer">
      <div class="user-card" onclick="showToast('ℹ️ Profil pengguna: '+currentUser.name)">
        <div class="avatar" id="userAvatar">AD</div>
        <div class="user-info">
          <div class="name" id="userName">Admin Sistem</div>
          <div class="role" id="userRole">Administrator</div>
        </div>
      </div>
      <div class="nav-item" onclick="doLogout()" style="margin-top:8px;color:#ef4444"><span class="icon">🚪</span><span>Logout</span></div>
    </div>
  </aside>

  <!-- MAIN CONTENT -->
  <main class="main">
    <div class="topbar">
      <h1 id="pageTitle">Dashboard Utama</h1>
      <div class="topbar-right">
        <span style="font-size:.8rem;color:var(--text2)">Selamat Datang, <strong id="topbarUser" style="color:var(--accent)">Admin</strong></span>
        <div class="notif-badge" style="font-size:20px" onclick="openNotifModal()">🔔<div class="notif-dot" id="notifDot"></div></div>
        <button class="btn-sm btn-primary" onclick="showPage('inputData', document.querySelector('[data-page=inputData]'))">+ Tambah Data</button>
      </div>
    </div>

    <div class="content">

      <!-- ━━━ PAGE: DASHBOARD ━━━ -->
      <div id="page-dashboard" class="page active">
        <div class="stats-grid">
          <div class="stat-card total" onclick="showPage('dataMahasiswa', document.querySelector('[data-page=dataMahasiswa]'))">
            <div class="label">Total Mahasiswa</div>
            <div class="value" style="color:var(--accent2)" id="statTotal">1,250</div>
            <div class="sub">↑ 12 mahasiswa baru bulan ini</div>
            <div class="icon-bg">👥</div>
          </div>
          <div class="stat-card danger" onclick="filterDashboard('tinggi')">
            <div class="label">Berisiko Tinggi</div>
            <div class="value" style="color:var(--danger)" id="statTinggi">125</div>
            <div class="sub">10% dari total mahasiswa</div>
            <div class="icon-bg">⚠️</div>
          </div>
          <div class="stat-card warning" onclick="filterDashboard('sedang')">
            <div class="label">Berisiko Sedang</div>
            <div class="value" style="color:var(--warning)" id="statSedang">210</div>
            <div class="sub">17% dari total mahasiswa</div>
            <div class="icon-bg">📢</div>
          </div>
          <div class="stat-card success" onclick="filterDashboard('rendah')">
            <div class="label">Risiko Rendah</div>
            <div class="value" style="color:var(--success)" id="statRendah">915</div>
            <div class="sub">73% dari total mahasiswa</div>
            <div class="icon-bg">✅</div>
          </div>
        </div>

        <div class="grid-2">
          <div class="card">
            <div class="card-header"><span class="card-title">Distribusi Risiko Dropout</span></div>
            <div class="card-body" style="display:flex;gap:24px;align-items:center">
              <div class="donut-wrap">
                <svg viewBox="0 0 140 140" width="140" height="140">
                  <circle cx="70" cy="70" r="54" fill="none" stroke="#22c55e" stroke-width="18" stroke-dasharray="247.6 91.1" stroke-linecap="butt"/>
                  <circle cx="70" cy="70" r="54" fill="none" stroke="#f59e0b" stroke-width="18" stroke-dasharray="57.6 281.1" stroke-dashoffset="-247.6" stroke-linecap="butt"/>
                  <circle cx="70" cy="70" r="54" fill="none" stroke="#ef4444" stroke-width="18" stroke-dasharray="33.9 305" stroke-dashoffset="-305.2" stroke-linecap="butt"/>
                  <circle cx="70" cy="70" r="44" fill="#161b22"/>
                </svg>
                <div class="donut-center"><div class="num">1,250</div><div class="lbl">Total</div></div>
              </div>
              <div class="legend" style="flex:1">
                <div class="legend-item"><div class="legend-dot" style="background:#ef4444"></div><span>Tinggi — 125 (10%)</span></div>
                <div class="legend-item"><div class="legend-dot" style="background:#f59e0b"></div><span>Sedang — 210 (17%)</span></div>
                <div class="legend-item"><div class="legend-dot" style="background:#22c55e"></div><span>Rendah — 915 (73%)</span></div>
              </div>
            </div>
          </div>

          <div class="card">
            <div class="card-header"><span class="card-title">Aktivitas Terbaru</span><button class="btn-sm btn-secondary" onclick="openNotifModal()">Lihat Semua</button></div>
            <div class="card-body">
              <div id="activityList">
                <div class="activity-item">
                  <span style="font-size:18px">🤖</span>
                  <div><div style="font-size:.83rem;font-weight:600">Prediksi batch selesai</div><div style="font-size:.76rem;color:var(--text2)">125 mahasiswa dianalisis · 2 menit lalu</div></div>
                </div>
                <div class="activity-item">
                  <span style="font-size:18px">📝</span>
                  <div><div style="font-size:.83rem;font-weight:600">Data mahasiswa diperbarui</div><div style="font-size:.76rem;color:var(--text2)">Andi Saputra · 15 menit lalu</div></div>
                </div>
                <div class="activity-item">
                  <span style="font-size:18px">🛡️</span>
                  <div><div style="font-size:.83rem;font-weight:600">Tindakan preventif direkam</div><div style="font-size:.76rem;color:var(--text2)">Konseling untuk 3 mahasiswa · 1 jam lalu</div></div>
                </div>
                <div class="activity-item">
                  <span style="font-size:18px">🔄</span>
                  <div><div style="font-size:.83rem;font-weight:600">Model diperbarui otomatis</div><div style="font-size:.76rem;color:var(--text2)">Akurasi meningkat ke 91.4% · kemarin</div></div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="card" style="margin-top:16px">
          <div class="card-header">
            <span class="card-title">Mahasiswa Prioritas Intervensi</span>
            <div style="display:flex;gap:8px">
              <button class="btn-sm btn-secondary" onclick="exportData('dashboard')">📥 Export</button>
              <button class="btn-sm btn-primary" onclick="showPage('prediksi', document.querySelector('[data-page=prediksi]'))">Lihat Semua →</button>
            </div>
          </div>
          <div class="card-body">
            <div class="table-wrap">
              <table>
                <thead><tr><th>NIM</th><th>Nama</th><th>Program Studi</th><th>IPK</th><th>Absensi</th><th>Risiko</th><th>%</th><th>Aksi</th></tr></thead>
                <tbody id="dashboardTable">
                  <tr><td style="font-family:monospace">2021101001</td><td>Andi Saputra</td><td>Sistem Informasi</td><td>2.75</td><td>72%</td><td><span class="badge tinggi">Tinggi</span></td><td><strong style="color:var(--danger)">85%</strong></td><td><button class="btn-sm btn-secondary" onclick="openDetailModal(0)">Detail</button></td></tr>
                  <tr><td style="font-family:monospace">2021101004</td><td>Dwi Kurniawan</td><td>Sistem Informasi</td><td>2.40</td><td>68%</td><td><span class="badge tinggi">Tinggi</span></td><td><strong style="color:var(--danger)">75%</strong></td><td><button class="btn-sm btn-secondary" onclick="openDetailModal(3)">Detail</button></td></tr>
                  <tr><td style="font-family:monospace">2021101006</td><td>Fani Rahayu</td><td>Manajemen</td><td>2.90</td><td>75%</td><td><span class="badge sedang">Sedang</span></td><td><strong style="color:var(--warning)">55%</strong></td><td><button class="btn-sm btn-secondary" onclick="openDetailModal(5)">Detail</button></td></tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>

      <!-- ━━━ PAGE: DATA MAHASISWA ━━━ -->
      <div id="page-dataMahasiswa" class="page">
        <div class="card" style="margin-bottom:16px">
          <div class="card-body">
            <div class="filter-row">
              <div class="search-bar" style="flex:1;min-width:200px">
                <span>🔍</span>
                <input type="text" id="searchMahasiswa" placeholder="Cari NIM / Nama mahasiswa..." oninput="filterMahasiswaTable()">
              </div>
              <select class="filter-select" id="filterProdi" onchange="filterMahasiswaTable()">
                <option value="">Semua Program Studi</option>
                <option value="Sistem Informasi">Sistem Informasi</option>
                <option value="Teknik Informatika">Teknik Informatika</option>
                <option value="Manajemen">Manajemen</option>
                <option value="Akuntansi">Akuntansi</option>
              </select>
              <select class="filter-select" id="filterRisiko" onchange="filterMahasiswaTable()">
                <option value="">Semua Risiko</option>
                <option value="Tinggi">Risiko Tinggi</option>
                <option value="Sedang">Risiko Sedang</option>
                <option value="Rendah">Risiko Rendah</option>
              </select>
              <button class="btn-sm btn-secondary" onclick="resetFilter()">↺ Reset</button>
              <button class="btn-sm btn-primary" onclick="showPage('inputData', document.querySelector('[data-page=inputData]'))">+ Tambah Data</button>
              <button class="btn-sm btn-secondary" onclick="exportData('mahasiswa')">📥 Export CSV</button>
            </div>
          </div>
        </div>
        <div class="card">
          <div class="card-header">
            <span class="card-title">Daftar Mahasiswa</span>
            <span style="font-size:.8rem;color:var(--text2)" id="mahasiswaCount">1.250 mahasiswa</span>
          </div>
          <div class="card-body">
            <div class="table-wrap">
              <table>
                <thead>
                  <tr>
                    <th><input type="checkbox" id="checkAll" onchange="toggleAllCheck(this)"></th>
                    <th onclick="sortTable('nim')" style="cursor:pointer">NIM ↕</th>
                    <th onclick="sortTable('nama')" style="cursor:pointer">Nama ↕</th>
                    <th>Program Studi</th>
                    <th onclick="sortTable('ipk')" style="cursor:pointer">IPK ↕</th>
                    <th>Absensi</th>
                    <th onclick="sortTable('risiko')" style="cursor:pointer">Risiko ↕</th>
                    <th>%</th>
                    <th>Aksi</th>
                  </tr>
                </thead>
                <tbody id="mahasiswaTableBody"></tbody>
              </table>
            </div>
            <div style="display:flex;align-items:center;justify-content:space-between;margin-top:12px;flex-wrap:wrap;gap:8px">
              <div class="pagination" id="mahasiswaPagination"></div>
              <span class="page-info" id="mahasiswaPageInfo"></span>
            </div>
          </div>
        </div>
      </div>

      <!-- ━━━ PAGE: INPUT DATA ━━━ -->
      <div id="page-inputData" class="page">
        <div class="card">
          <div class="card-header">
            <span class="card-title">Input Data Mahasiswa Baru</span>
            <button class="btn-sm btn-secondary" onclick="resetInputForm()">↺ Reset Form</button>
          </div>
          <div class="card-body">
            <div class="form-grid">
              <div class="form-field">
                <label>NIM <span style="color:var(--danger)">*</span></label>
                <input type="text" id="inputNIM" placeholder="Contoh: 2021101010">
              </div>
              <div class="form-field">
                <label>Nama Lengkap <span style="color:var(--danger)">*</span></label>
                <input type="text" id="inputNama" placeholder="Nama lengkap mahasiswa">
              </div>
              <div class="form-field">
                <label>Program Studi</label>
                <select id="inputProdi">
                  <option>Sistem Informasi</option>
                  <option>Teknik Informatika</option>
                  <option>Manajemen</option>
                  <option>Akuntansi</option>
                </select>
              </div>
              <div class="form-field">
                <label>IPK (0–4) <span style="color:var(--danger)">*</span></label>
                <input type="number" id="inputIPK" step="0.01" min="0" max="4" placeholder="0.00 – 4.00">
              </div>
              <div class="form-field">
                <label>Absensi (%) <span style="color:var(--danger)">*</span></label>
                <input type="number" id="inputAbsensi" min="0" max="100" placeholder="0 – 100">
              </div>
              <div class="form-field">
                <label>Status Ekonomi</label>
                <select id="inputEkonomi">
                  <option>Menengah</option>
                  <option>Rendah</option>
                  <option>Tinggi</option>
                </select>
              </div>
              <div class="form-field">
                <label>Pekerjaan Orang Tua</label>
                <select id="inputPekerjaan">
                  <option>Wiraswasta</option>
                  <option>PNS</option>
                  <option>Buruh</option>
                  <option>TNI/Polri</option>
                  <option>Lainnya</option>
                </select>
              </div>
              <div class="form-field">
                <label>Semester Aktif</label>
                <select id="inputSemester">
                  <option value="1">1</option><option value="2">2</option>
                  <option value="3" selected>3</option><option value="4">4</option>
                  <option value="5">5</option><option value="6">6</option>
                  <option value="7">7</option><option value="8">8</option>
                </select>
              </div>
              <div class="form-field">
                <label>SKS Lulus</label>
                <input type="number" id="inputSKS" min="0" max="160" placeholder="Jumlah SKS lulus">
              </div>
              <div class="form-field">
                <label>Status Beasiswa</label>
                <select id="inputBeasiswa">
                  <option>Tidak</option>
                  <option>KIP Kuliah</option>
                  <option>Bidikmisi</option>
                  <option>Swasta</option>
                </select>
              </div>
            </div>
            <div class="form-actions">
              <button class="btn-sm btn-primary" style="padding:10px 24px" onclick="simpanData()">💾 Simpan & Prediksi</button>
              <button class="btn-sm btn-secondary" style="padding:10px 24px" onclick="resetInputForm()">✕ Reset</button>
            </div>
          </div>
        </div>

        <!-- Preprocessing & ML -->
        <div id="preprocessSection" style="display:none;margin-top:16px">
          <div class="grid-2">
            <div class="card">
              <div class="card-header"><span class="card-title">⚙️ Preprocessing Data</span></div>
              <div class="card-body">
                <div class="process-stage">
                  <div class="process-icon">🗄️</div>
                  <div class="process-status" id="prepStatus">Memulai preprocessing...</div>
                  <div class="process-sub" id="prepSub">Cleaning & Encoding data</div>
                  <div class="process-pct" id="prepPct">0%</div>
                  <div class="progress-bar"><div class="progress-fill blue" id="prepBar" style="width:0%"></div></div>
                  <div id="prepLog" style="margin-top:12px;font-size:.72rem;color:var(--text2);text-align:left;background:var(--surface2);border-radius:8px;padding:10px;min-height:60px;font-family:'JetBrains Mono',monospace;line-height:1.8"></div>
                </div>
              </div>
            </div>
            <div class="card">
              <div class="card-header"><span class="card-title">🤖 Machine Learning</span></div>
              <div class="card-body">
                <div class="process-stage">
                  <div class="process-icon">🧠</div>
                  <div class="process-status" id="mlStatus">Menunggu preprocessing...</div>
                  <div class="process-sub" id="mlSub">Random Forest, XGBoost, dll</div>
                  <div class="process-pct" id="mlPct">0%</div>
                  <div class="progress-bar"><div class="progress-fill accent" id="mlBar" style="width:0%"></div></div>
                  <div id="mlLog" style="margin-top:12px;font-size:.72rem;color:var(--text2);text-align:left;background:var(--surface2);border-radius:8px;padding:10px;min-height:60px;font-family:'JetBrains Mono',monospace;line-height:1.8"></div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- ━━━ PAGE: PREDIKSI ━━━ -->
      <div id="page-prediksi" class="page">
        <div class="card" style="margin-bottom:16px">
          <div class="card-header">
            <span class="card-title">Hasil Prediksi Risiko Dropout</span>
            <div style="display:flex;gap:8px;flex-wrap:wrap">
              <button class="btn-sm btn-secondary" onclick="exportData('prediksi')">📥 Export</button>
              <button class="btn-sm btn-primary" onclick="runBatchPrediction()">🤖 Jalankan Prediksi Batch</button>
            </div>
          </div>
          <div class="card-body">
            <div class="filter-row" style="margin-bottom:14px">
              <div class="search-bar" style="flex:1;min-width:200px">
                <span>🔍</span>
                <input type="text" id="searchPrediksi" placeholder="Cari NIM / Nama..." oninput="filterPrediksiTable()">
              </div>
              <select class="filter-select" id="filterPrediksiRisiko" onchange="filterPrediksiTable()">
                <option value="">Semua Risiko</option>
                <option value="Tinggi">Risiko Tinggi</option>
                <option value="Sedang">Risiko Sedang</option>
                <option value="Rendah">Risiko Rendah</option>
              </select>
              <select class="filter-select" id="filterPrediksiModel" onchange="filterPrediksiTable()">
                <option value="">Semua Model</option>
                <option value="Random Forest">Random Forest</option>
                <option value="XGBoost">XGBoost</option>
                <option value="Logistic Regression">Logistic Regression</option>
              </select>
            </div>
            <div class="table-wrap">
              <table>
                <thead>
                  <tr>
                    <th>NIM</th><th>Nama</th><th>IPK</th><th>Absensi</th>
                    <th>Risiko</th><th>Persentase</th><th>Model</th><th>Aksi</th>
                  </tr>
                </thead>
                <tbody id="prediksiTableBody"></tbody>
              </table>
            </div>
            <div style="display:flex;align-items:center;justify-content:space-between;margin-top:12px;flex-wrap:wrap;gap:8px">
              <div class="pagination" id="prediksiPagination"></div>
              <span class="page-info" id="prediksiPageInfo"></span>
            </div>
          </div>
        </div>

        <!-- Batch progress -->
        <div id="batchSection" style="display:none">
          <div class="card">
            <div class="card-header"><span class="card-title">🤖 Progress Prediksi Batch</span></div>
            <div class="card-body">
              <div style="display:flex;flex-direction:column;gap:12px">
                <div><div style="display:flex;justify-content:space-between;font-size:.82rem;margin-bottom:6px"><span>Random Forest</span><span id="batchRF" style="color:var(--accent);font-weight:700">0%</span></div><div class="progress-bar"><div class="progress-fill accent" id="batchRFBar" style="width:0%"></div></div></div>
                <div><div style="display:flex;justify-content:space-between;font-size:.82rem;margin-bottom:6px"><span>XGBoost</span><span id="batchXG" style="color:var(--accent2);font-weight:700">0%</span></div><div class="progress-bar"><div class="progress-fill blue" id="batchXGBar" style="width:0%"></div></div></div>
                <div><div style="display:flex;justify-content:space-between;font-size:.82rem;margin-bottom:6px"><span>Ensemble Voting</span><span id="batchEns" style="color:var(--success);font-weight:700">0%</span></div><div class="progress-bar"><div class="progress-fill green" id="batchEnsBar" style="width:0%"></div></div></div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- ━━━ PAGE: VISUALISASI ━━━ -->
      <div id="page-visualisasi" class="page">
        <div class="tab-bar">
          <button class="tab-btn active" onclick="switchVisTab('distribusi',this)">📊 Distribusi Risiko</button>
          <button class="tab-btn" onclick="switchVisTab('akurasi',this)">🎯 Akurasi Model</button>
          <button class="tab-btn" onclick="switchVisTab('tren',this)">📈 Tren Risiko</button>
          <button class="tab-btn" onclick="switchVisTab('faktor',this)">🔍 Faktor Risiko</button>
        </div>

        <!-- Tab Distribusi -->
        <div id="visTab-distribusi" class="vis-tab">
          <div class="grid-2" style="margin-bottom:16px">
            <div class="card">
              <div class="card-header"><span class="card-title">Distribusi Risiko</span></div>
              <div class="card-body" style="display:flex;gap:20px;align-items:center">
                <div class="donut-wrap">
                  <svg viewBox="0 0 140 140" width="140" height="140">
                    <circle cx="70" cy="70" r="54" fill="none" stroke="#22c55e" stroke-width="18" stroke-dasharray="247.6 91.1"/>
                    <circle cx="70" cy="70" r="54" fill="none" stroke="#f59e0b" stroke-width="18" stroke-dasharray="57.6 281.1" stroke-dashoffset="-247.6"/>
                    <circle cx="70" cy="70" r="54" fill="none" stroke="#ef4444" stroke-width="18" stroke-dasharray="33.9 305" stroke-dashoffset="-305.2"/>
                    <circle cx="70" cy="70" r="44" fill="#161b22"/>
                  </svg>
                  <div class="donut-center"><div class="num">1,250</div><div class="lbl">Total</div></div>
                </div>
                <div class="legend" style="flex:1">
                  <div class="legend-item"><div class="legend-dot" style="background:#ef4444"></div>Tinggi — 125 (10%)</div>
                  <div class="legend-item"><div class="legend-dot" style="background:#f59e0b"></div>Sedang — 210 (17%)</div>
                  <div class="legend-item"><div class="legend-dot" style="background:#22c55e"></div>Rendah — 915 (73%)</div>
                </div>
              </div>
            </div>
            <div class="card">
              <div class="card-header"><span class="card-title">Distribusi per Program Studi</span></div>
              <div class="card-body">
                <div style="display:flex;flex-direction:column;gap:14px">
                  <div>
                    <div style="display:flex;justify-content:space-between;font-size:.82rem;margin-bottom:4px"><span>Sistem Informasi</span><span style="color:var(--danger);font-weight:700">48 tinggi</span></div>
                    <div class="progress-bar"><div class="progress-fill red" style="width:72%"></div></div>
                  </div>
                  <div>
                    <div style="display:flex;justify-content:space-between;font-size:.82rem;margin-bottom:4px"><span>Teknik Informatika</span><span style="color:var(--warning);font-weight:700">32 tinggi</span></div>
                    <div class="progress-bar"><div class="progress-fill" style="width:54%;background:#f59e0b"></div></div>
                  </div>
                  <div>
                    <div style="display:flex;justify-content:space-between;font-size:.82rem;margin-bottom:4px"><span>Manajemen</span><span style="color:var(--warning);font-weight:700">28 tinggi</span></div>
                    <div class="progress-bar"><div class="progress-fill" style="width:42%;background:#f59e0b"></div></div>
                  </div>
                  <div>
                    <div style="display:flex;justify-content:space-between;font-size:.82rem;margin-bottom:4px"><span>Akuntansi</span><span style="color:var(--success);font-weight:700">17 tinggi</span></div>
                    <div class="progress-bar"><div class="progress-fill green" style="width:28%"></div></div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="grid-3">
            <div class="stat-mini"><div class="sm-label">Rata-rata IPK</div><div class="sm-val" style="color:var(--accent)">3.12</div></div>
            <div class="stat-mini"><div class="sm-label">Rata-rata Absensi</div><div class="sm-val" style="color:var(--accent2)">81%</div></div>
            <div class="stat-mini"><div class="sm-label">Sudah Ditindaklanjuti</div><div class="sm-val" style="color:var(--success)">67%</div></div>
          </div>
        </div>

        <!-- Tab Akurasi -->
        <div id="visTab-akurasi" class="vis-tab" style="display:none">
          <div class="grid-2">
            <div class="card">
              <div class="card-header"><span class="card-title">Performa Model</span></div>
              <div class="card-body">
                <div style="display:flex;flex-direction:column;gap:14px">
                  <div>
                    <div style="display:flex;justify-content:space-between;font-size:.82rem;margin-bottom:6px"><span>Random Forest</span><span style="color:var(--accent);font-weight:700">91.4%</span></div>
                    <div class="progress-bar"><div class="progress-fill accent" style="width:91.4%"></div></div>
                  </div>
                  <div>
                    <div style="display:flex;justify-content:space-between;font-size:.82rem;margin-bottom:6px"><span>XGBoost</span><span style="color:var(--accent2);font-weight:700">89.7%</span></div>
                    <div class="progress-bar"><div class="progress-fill blue" style="width:89.7%"></div></div>
                  </div>
                  <div>
                    <div style="display:flex;justify-content:space-between;font-size:.82rem;margin-bottom:6px"><span>Logistic Regression</span><span style="color:var(--warning);font-weight:700">82.1%</span></div>
                    <div class="progress-bar"><div class="progress-fill" style="width:82.1%;background:var(--warning)"></div></div>
                  </div>
                  <div>
                    <div style="display:flex;justify-content:space-between;font-size:.82rem;margin-bottom:6px"><span>SVM</span><span style="color:var(--text2);font-weight:700">79.3%</span></div>
                    <div class="progress-bar"><div class="progress-fill" style="width:79.3%;background:#6366f1"></div></div>
                  </div>
                </div>
              </div>
            </div>
            <div class="card">
              <div class="card-header"><span class="card-title">Confusion Matrix – Random Forest</span></div>
              <div class="card-body">
                <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:8px;text-align:center;font-size:.8rem">
                  <div></div>
                  <div style="color:var(--text2);font-weight:700;padding:6px">Pred. Positif</div>
                  <div style="color:var(--text2);font-weight:700;padding:6px">Pred. Negatif</div>
                  <div style="color:var(--text2);font-weight:700;padding:6px">Aktual Pos</div>
                  <div style="background:rgba(34,197,94,.15);border:1px solid rgba(34,197,94,.3);border-radius:8px;padding:12px;font-weight:800;color:var(--success)">112<br><span style="font-size:.7rem;font-weight:400">TP</span></div>
                  <div style="background:rgba(239,68,68,.1);border:1px solid rgba(239,68,68,.2);border-radius:8px;padding:12px;font-weight:800;color:var(--danger)">13<br><span style="font-size:.7rem;font-weight:400">FN</span></div>
                  <div style="color:var(--text2);font-weight:700;padding:6px">Aktual Neg</div>
                  <div style="background:rgba(239,68,68,.1);border:1px solid rgba(239,68,68,.2);border-radius:8px;padding:12px;font-weight:800;color:var(--danger)">8<br><span style="font-size:.7rem;font-weight:400">FP</span></div>
                  <div style="background:rgba(34,197,94,.15);border:1px solid rgba(34,197,94,.3);border-radius:8px;padding:12px;font-weight:800;color:var(--success)">117<br><span style="font-size:.7rem;font-weight:400">TN</span></div>
                </div>
                <div style="margin-top:14px;display:grid;grid-template-columns:1fr 1fr;gap:10px;font-size:.8rem">
                  <div class="stat-mini"><div class="sm-label">Precision</div><div class="sm-val" style="font-size:1rem;color:var(--accent)">93.3%</div></div>
                  <div class="stat-mini"><div class="sm-label">Recall</div><div class="sm-val" style="font-size:1rem;color:var(--accent2)">89.6%</div></div>
                  <div class="stat-mini"><div class="sm-label">F1-Score</div><div class="sm-val" style="font-size:1rem;color:var(--success)">91.4%</div></div>
                  <div class="stat-mini"><div class="sm-label">AUC-ROC</div><div class="sm-val" style="font-size:1rem;color:var(--warning)">0.947</div></div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Tab Tren -->
        <div id="visTab-tren" class="vis-tab" style="display:none">
          <div class="card">
            <div class="card-header">
              <span class="card-title">Tren Risiko Dropout (Jan–Jun 2024)</span>
              <div style="display:flex;gap:12px;align-items:center;flex-wrap:wrap">
                <div class="legend-item"><div class="legend-dot" style="background:#ef4444"></div><span style="font-size:.76rem">Tinggi</span></div>
                <div class="legend-item"><div class="legend-dot" style="background:#f59e0b"></div><span style="font-size:.76rem">Sedang</span></div>
                <div class="legend-item"><div class="legend-dot" style="background:#22c55e"></div><span style="font-size:.76rem">Rendah</span></div>
                <button class="btn-sm btn-secondary" onclick="exportData('tren')">📥 Export</button>
              </div>
            </div>
            <div class="card-body">
              <svg viewBox="0 0 700 220" width="100%" height="220" style="overflow:visible">
                <defs>
                  <linearGradient id="gRed" x1="0" x2="0" y1="0" y2="1"><stop offset="0%" stop-color="#ef4444" stop-opacity=".3"/><stop offset="100%" stop-color="#ef4444" stop-opacity="0"/></linearGradient>
                  <linearGradient id="gYellow" x1="0" x2="0" y1="0" y2="1"><stop offset="0%" stop-color="#f59e0b" stop-opacity=".2"/><stop offset="100%" stop-color="#f59e0b" stop-opacity="0"/></linearGradient>
                  <linearGradient id="gGreen" x1="0" x2="0" y1="0" y2="1"><stop offset="0%" stop-color="#22c55e" stop-opacity=".2"/><stop offset="100%" stop-color="#22c55e" stop-opacity="0"/></linearGradient>
                </defs>
                <!-- Grid -->
                <line x1="60" y1="20" x2="60" y2="180" stroke="#30363d" stroke-width="1"/>
                <line x1="60" y1="180" x2="690" y2="180" stroke="#30363d" stroke-width="1"/>
                <line x1="60" y1="20" x2="690" y2="20" stroke="#30363d" stroke-width=".5" stroke-dasharray="4"/>
                <line x1="60" y1="70" x2="690" y2="70" stroke="#30363d" stroke-width=".5" stroke-dasharray="4"/>
                <line x1="60" y1="125" x2="690" y2="125" stroke="#30363d" stroke-width=".5" stroke-dasharray="4"/>
                <!-- Y labels -->
                <text x="50" y="24" font-size="11" fill="#8b949e" text-anchor="end">200</text>
                <text x="50" y="74" font-size="11" fill="#8b949e" text-anchor="end">150</text>
                <text x="50" y="129" font-size="11" fill="#8b949e" text-anchor="end">100</text>
                <text x="50" y="184" font-size="11" fill="#8b949e" text-anchor="end">50</text>
                <!-- X labels -->
                <text x="120" y="200" font-size="11" fill="#8b949e" text-anchor="middle">Jan</text>
                <text x="228" y="200" font-size="11" fill="#8b949e" text-anchor="middle">Feb</text>
                <text x="336" y="200" font-size="11" fill="#8b949e" text-anchor="middle">Mar</text>
                <text x="444" y="200" font-size="11" fill="#8b949e" text-anchor="middle">Apr</text>
                <text x="552" y="200" font-size="11" fill="#8b949e" text-anchor="middle">Mei</text>
                <text x="660" y="200" font-size="11" fill="#8b949e" text-anchor="middle">Jun</text>
                <!-- Area Tinggi -->
                <polygon points="120,148 228,140 336,130 444,122 552,112 660,108 660,180 120,180" fill="url(#gRed)"/>
                <!-- Area Sedang -->
                <polygon points="120,105 228,112 336,100 444,92 552,86 660,89 660,180 120,180" fill="url(#gYellow)"/>
                <!-- Tinggi line -->
                <polyline points="120,148 228,140 336,130 444,122 552,112 660,108" fill="none" stroke="#ef4444" stroke-width="2.5" stroke-linejoin="round"/>
                <!-- Sedang line -->
                <polyline points="120,105 228,112 336,100 444,92 552,86 660,89" fill="none" stroke="#f59e0b" stroke-width="2.5" stroke-linejoin="round"/>
                <!-- Rendah line -->
                <polyline points="120,62 228,56 336,67 444,50 552,47 660,41" fill="none" stroke="#22c55e" stroke-width="2.5" stroke-linejoin="round"/>
                <!-- Dots -->
                <circle cx="120" cy="148" r="5" fill="#ef4444" stroke="#0d1117" stroke-width="2"/>
                <circle cx="228" cy="140" r="5" fill="#ef4444" stroke="#0d1117" stroke-width="2"/>
                <circle cx="336" cy="130" r="5" fill="#ef4444" stroke="#0d1117" stroke-width="2"/>
                <circle cx="444" cy="122" r="5" fill="#ef4444" stroke="#0d1117" stroke-width="2"/>
                <circle cx="552" cy="112" r="5" fill="#ef4444" stroke="#0d1117" stroke-width="2"/>
                <circle cx="660" cy="108" r="5" fill="#ef4444" stroke="#0d1117" stroke-width="2"/>
                <circle cx="120" cy="105" r="5" fill="#f59e0b" stroke="#0d1117" stroke-width="2"/>
                <circle cx="228" cy="112" r="5" fill="#f59e0b" stroke="#0d1117" stroke-width="2"/>
                <circle cx="336" cy="100" r="5" fill="#f59e0b" stroke="#0d1117" stroke-width="2"/>
                <circle cx="444" cy="92" r="5" fill="#f59e0b" stroke="#0d1117" stroke-width="2"/>
                <circle cx="552" cy="86" r="5" fill="#f59e0b" stroke="#0d1117" stroke-width="2"/>
                <circle cx="660" cy="89" r="5" fill="#f59e0b" stroke="#0d1117" stroke-width="2"/>
                <circle cx="120" cy="62" r="5" fill="#22c55e" stroke="#0d1117" stroke-width="2"/>
                <circle cx="228" cy="56" r="5" fill="#22c55e" stroke="#0d1117" stroke-width="2"/>
                <circle cx="336" cy="67" r="5" fill="#22c55e" stroke="#0d1117" stroke-width="2"/>
                <circle cx="444" cy="50" r="5" fill="#22c55e" stroke="#0d1117" stroke-width="2"/>
                <circle cx="552" cy="47" r="5" fill="#22c55e" stroke="#0d1117" stroke-width="2"/>
                <circle cx="660" cy="41" r="5" fill="#22c55e" stroke="#0d1117" stroke-width="2"/>
              </svg>
            </div>
          </div>
        </div>

        <!-- Tab Faktor -->
        <div id="visTab-faktor" class="vis-tab" style="display:none">
          <div class="grid-2">
            <div class="card">
              <div class="card-header"><span class="card-title">Feature Importance</span></div>
              <div class="card-body">
                <div style="display:flex;flex-direction:column;gap:12px">
                  <div><div style="display:flex;justify-content:space-between;font-size:.82rem;margin-bottom:5px"><span>IPK Mahasiswa</span><span style="font-weight:700;color:var(--danger)">34.2%</span></div><div class="progress-bar"><div class="progress-fill red" style="width:34.2%"></div></div></div>
                  <div><div style="display:flex;justify-content:space-between;font-size:.82rem;margin-bottom:5px"><span>Kehadiran (Absensi)</span><span style="font-weight:700;color:var(--warning)">28.7%</span></div><div class="progress-bar"><div class="progress-fill" style="width:28.7%;background:#f59e0b"></div></div></div>
                  <div><div style="display:flex;justify-content:space-between;font-size:.82rem;margin-bottom:5px"><span>Status Ekonomi</span><span style="font-weight:700;color:var(--accent2)">16.5%</span></div><div class="progress-bar"><div class="progress-fill blue" style="width:16.5%"></div></div></div>
                  <div><div style="display:flex;justify-content:space-between;font-size:.82rem;margin-bottom:5px"><span>Semester Aktif</span><span style="font-weight:700;color:var(--accent)">11.3%</span></div><div class="progress-bar"><div class="progress-fill accent" style="width:11.3%"></div></div></div>
                  <div><div style="display:flex;justify-content:space-between;font-size:.82rem;margin-bottom:5px"><span>Status Beasiswa</span><span style="font-weight:700;color:var(--success)">9.3%</span></div><div class="progress-bar"><div class="progress-fill green" style="width:9.3%"></div></div></div>
                </div>
              </div>
            </div>
            <div class="card">
              <div class="card-header"><span class="card-title">Korelasi Faktor Risiko</span></div>
              <div class="card-body">
                <div style="display:flex;flex-direction:column;gap:10px;font-size:.82rem">
                  <div style="background:rgba(239,68,68,.08);border:1px solid rgba(239,68,68,.2);border-radius:8px;padding:12px">
                    <div style="font-weight:700;color:var(--danger);margin-bottom:4px">🔴 IPK &lt; 2.5 + Absensi &lt; 70%</div>
                    <div style="color:var(--text2)">Kombinasi ini meningkatkan risiko dropout hingga <strong style="color:var(--text)">78%</strong>. Intervensi segera diperlukan.</div>
                  </div>
                  <div style="background:rgba(245,158,11,.08);border:1px solid rgba(245,158,11,.2);border-radius:8px;padding:12px">
                    <div style="font-weight:700;color:var(--warning);margin-bottom:4px">🟡 Ekonomi Rendah + Tidak Beasiswa</div>
                    <div style="color:var(--text2)">Risiko meningkat <strong style="color:var(--text)">45%</strong> jika tidak ada bantuan finansial.</div>
                  </div>
                  <div style="background:rgba(34,197,94,.08);border:1px solid rgba(34,197,94,.2);border-radius:8px;padding:12px">
                    <div style="font-weight:700;color:var(--success);margin-bottom:4px">🟢 IPK &gt; 3.5 + Absensi &gt; 85%</div>
                    <div style="color:var(--text2)">Risiko sangat rendah (<strong style="color:var(--text)">&lt;10%</strong>). Mahasiswa dalam kondisi akademik baik.</div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- ━━━ PAGE: REKOMENDASI ━━━ -->
      <div id="page-rekomendasi" class="page">
        <div class="card" style="margin-bottom:16px">
          <div class="card-header"><span class="card-title">Pilih Mahasiswa</span></div>
          <div class="card-body">
            <div class="filter-row">
              <div class="search-bar" style="flex:1;min-width:200px">
                <span>🔍</span>
                <input type="text" id="searchRekom" placeholder="Cari NIM / Nama..." oninput="filterRekomendasiList()">
              </div>
              <select class="filter-select" id="filterRekRisiko" onchange="filterRekomendasiList()">
                <option value="">Semua Risiko</option>
                <option value="Tinggi">Risiko Tinggi</option>
                <option value="Sedang">Risiko Sedang</option>
                <option value="Rendah">Risiko Rendah</option>
              </select>
            </div>
            <div id="rekomendasiList" style="margin-top:14px;display:flex;flex-direction:column;gap:8px"></div>
          </div>
        </div>

        <div id="rekomendasiDetail" style="display:none">
          <div class="grid-2" style="margin-bottom:16px">
            <div class="card">
              <div class="card-header"><span class="card-title">Mahasiswa yang Dipilih</span></div>
              <div class="card-body">
                <div class="info-row"><span class="lbl">NIM</span><span class="val" id="rekNIM" style="font-family:monospace">-</span></div>
                <div class="info-row"><span class="lbl">Nama</span><span class="val" id="rekNama">-</span></div>
                <div class="info-row"><span class="lbl">Program Studi</span><span class="val" id="rekProdi">-</span></div>
                <div class="info-row"><span class="lbl">IPK</span><span class="val" id="rekIPK">-</span></div>
                <div class="info-row"><span class="lbl">Absensi</span><span class="val" id="rekAbsensi">-</span></div>
                <div class="info-row"><span class="lbl">Risiko</span><span id="rekRisikoBadge">-</span></div>
                <div class="info-row"><span class="lbl">Probabilitas</span><span class="val" id="rekPct" style="font-size:1.4rem;font-weight:800;font-family:monospace">-</span></div>
              </div>
            </div>
            <div class="card">
              <div class="card-header"><span class="card-title">Faktor Risiko Utama</span></div>
              <div class="card-body" id="rekFaktor">
                <div style="display:flex;flex-direction:column;gap:10px">
                  <div><div style="display:flex;justify-content:space-between;font-size:.8rem;margin-bottom:4px"><span>IPK Rendah</span><span id="fIPK" style="color:var(--danger)">Tinggi</span></div><div class="progress-bar"><div class="progress-fill red" id="fIPKBar" style="width:80%"></div></div></div>
                  <div><div style="display:flex;justify-content:space-between;font-size:.8rem;margin-bottom:4px"><span>Absensi Buruk</span><span id="fAbsensi" style="color:var(--warning)">Sedang</span></div><div class="progress-bar"><div class="progress-fill" id="fAbsensiBar" style="width:60%;background:#f59e0b"></div></div></div>
                  <div><div style="display:flex;justify-content:space-between;font-size:.8rem;margin-bottom:4px"><span>Faktor Ekonomi</span><span id="fEkonomi" style="color:var(--text2)">Rendah</span></div><div class="progress-bar"><div class="progress-fill blue" id="fEkonomiBar" style="width:30%"></div></div></div>
                </div>
              </div>
            </div>
          </div>

          <div class="card">
            <div class="card-header"><span class="card-title">Rekomendasi Tindakan</span></div>
            <div class="card-body" id="rekContent">
              <!-- filled by JS -->
            </div>
          </div>
        </div>
      </div>

      <!-- ━━━ PAGE: TINDAKAN PREVENTIF ━━━ -->
      <div id="page-tindakan" class="page">
        <div class="grid-2" style="margin-bottom:16px">
          <div class="card">
            <div class="card-header"><span class="card-title">🛡️ Catat Tindakan Preventif</span></div>
            <div class="card-body">
              <div style="background:var(--surface2);border:1px solid var(--border);border-radius:10px;padding:14px;margin-bottom:18px">
                <div style="font-size:.78rem;color:var(--text2);margin-bottom:8px;font-weight:600">MAHASISWA YANG DITINDAKLANJUTI</div>
                <div style="display:flex;gap:10px;align-items:center;flex-wrap:wrap">
                  <select class="filter-select" id="tidSelectMhs" onchange="onTidMhsChange()" style="flex:1">
                    <!-- filled by JS -->
                  </select>
                </div>
                <div style="margin-top:10px;display:grid;grid-template-columns:1fr 1fr;gap:6px;font-size:.82rem">
                  <div><span style="color:var(--text2)">NIM: </span><strong id="tidNIM">-</strong></div>
                  <div><span style="color:var(--text2)">Nama: </span><strong id="tidNama">-</strong></div>
                  <div><span style="color:var(--text2)">Risiko: </span><span id="tidRisiko">-</span></div>
                  <div><span style="color:var(--text2)">Model: </span><span id="tidModel" style="font-size:.76rem;color:var(--text2)">Random Forest</span></div>
                </div>
              </div>

              <div class="form-grid">
                <div class="form-field">
                  <label>Jenis Tindakan</label>
                  <select id="tidJenis">
                    <option>Konseling</option>
                    <option>Monitoring Akademik</option>
                    <option>Bantuan Beasiswa</option>
                    <option>Bimbingan Belajar</option>
                    <option>Pendampingan Intensif</option>
                    <option>Surat Peringatan</option>
                    <option>Pertemuan Orang Tua</option>
                  </select>
                </div>
                <div class="form-field">
                  <label>Tanggal Tindakan</label>
                  <input type="date" id="tidTanggal">
                </div>
                <div class="form-field">
                  <label>Dilakukan Oleh</label>
                  <input type="text" id="tidDosen" placeholder="Nama dosen / konselor">
                </div>
                <div class="form-field">
                  <label>Status</label>
                  <select id="tidStatus">
                    <option>Dijadwalkan</option>
                    <option>Sedang Berlangsung</option>
                    <option>Selesai</option>
                  </select>
                </div>
              </div>
              <div class="form-field" style="margin-top:14px">
                <label>Catatan Tindakan</label>
                <textarea id="tidCatatan" placeholder="Tuliskan detail tindakan yang dilakukan..."></textarea>
              </div>
              <div class="form-actions">
                <button class="btn-sm btn-primary" style="padding:10px 28px" onclick="simpanTindakan()">💾 Simpan Tindakan</button>
                <button class="btn-sm btn-secondary" style="padding:10px 20px" onclick="resetTindakanForm()">↺ Reset</button>
              </div>
            </div>
          </div>

          <div class="card">
            <div class="card-header"><span class="card-title">Statistik Tindakan</span></div>
            <div class="card-body">
              <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-bottom:16px">
                <div class="stat-mini"><div class="sm-label">Total Tindakan</div><div class="sm-val" id="statTindakanTotal" style="color:var(--accent2)">8</div></div>
                <div class="stat-mini"><div class="sm-label">Selesai</div><div class="sm-val" id="statTindakanSelesai" style="color:var(--success)">6</div></div>
                <div class="stat-mini"><div class="sm-label">Dijadwalkan</div><div class="sm-val" id="statTindakanJadwal" style="color:var(--warning)">2</div></div>
                <div class="stat-mini"><div class="sm-label">Berhasil</div><div class="sm-val" style="color:var(--accent)">75%</div></div>
              </div>
              <div style="font-size:.82rem;font-weight:700;margin-bottom:8px;color:var(--text2)">Tindakan Terbanyak</div>
              <div style="display:flex;flex-direction:column;gap:8px">
                <div><div style="display:flex;justify-content:space-between;font-size:.8rem;margin-bottom:4px"><span>Konseling</span><span style="font-weight:700">38%</span></div><div class="progress-bar"><div class="progress-fill accent" style="width:38%"></div></div></div>
                <div><div style="display:flex;justify-content:space-between;font-size:.8rem;margin-bottom:4px"><span>Monitoring Akademik</span><span style="font-weight:700">27%</span></div><div class="progress-bar"><div class="progress-fill blue" style="width:27%"></div></div></div>
                <div><div style="display:flex;justify-content:space-between;font-size:.8rem;margin-bottom:4px"><span>Bantuan Beasiswa</span><span style="font-weight:700">20%</span></div><div class="progress-bar"><div class="progress-fill green" style="width:20%"></div></div></div>
              </div>
            </div>
          </div>
        </div>

        <div class="card">
          <div class="card-header">
            <span class="card-title">Riwayat Tindakan</span>
            <div style="display:flex;gap:8px">
              <select class="filter-select" id="filterTindakanStatus" onchange="renderRiwayat()">
                <option value="">Semua Status</option>
                <option value="Selesai">Selesai</option>
                <option value="Dijadwalkan">Dijadwalkan</option>
                <option value="Sedang Berlangsung">Sedang Berlangsung</option>
              </select>
              <button class="btn-sm btn-secondary" onclick="exportData('tindakan')">📥 Export</button>
            </div>
          </div>
          <div class="card-body">
            <div class="table-wrap">
              <table>
                <thead><tr><th>Tanggal</th><th>NIM</th><th>Mahasiswa</th><th>Tindakan</th><th>Dilakukan Oleh</th><th>Status</th><th>Aksi</th></tr></thead>
                <tbody id="riwayatTindakan"></tbody>
              </table>
            </div>
          </div>
        </div>
      </div>

      <!-- ━━━ PAGE: FEEDBACK & UPDATE ━━━ -->
      <div id="page-feedback" class="page">
        <div class="grid-2" style="margin-bottom:16px">
          <div class="card">
            <div class="card-header"><span class="card-title">🔄 Update Perkembangan Mahasiswa</span></div>
            <div class="card-body">
              <div style="margin-bottom:14px">
                <label style="font-size:.78rem;font-weight:600;color:var(--text2);text-transform:uppercase;letter-spacing:.05em;display:block;margin-bottom:6px">Pilih Mahasiswa</label>
                <select class="filter-select" id="fbSelectMhs" style="width:100%" onchange="onFbMhsChange()">
                  <!-- filled by JS -->
                </select>
              </div>
              <div style="background:var(--surface2);border:1px solid var(--border);border-radius:10px;padding:12px;margin-bottom:14px;display:grid;grid-template-columns:1fr 1fr;gap:6px;font-size:.82rem">
                <div><span style="color:var(--text2)">NIM: </span><strong id="fbNIM">-</strong></div>
                <div><span style="color:var(--text2)">Nama: </span><strong id="fbNama">-</strong></div>
                <div><span style="color:var(--text2)">Risiko Awal: </span><span id="fbRisikoAwal">-</span></div>
                <div><span style="color:var(--text2)">Probabilitas: </span><span id="fbProb" style="font-weight:700">-</span></div>
              </div>
              <div class="form-field" style="margin-bottom:12px">
                <label>IPK Terbaru</label>
                <input type="number" id="fbIPK" step="0.01" min="0" max="4" placeholder="0.00 – 4.00">
              </div>
              <div class="form-field" style="margin-bottom:12px">
                <label>Absensi Terbaru (%)</label>
                <input type="number" id="fbAbsensi" min="0" max="100" placeholder="0 – 100">
              </div>
              <div class="form-field" style="margin-bottom:12px">
                <label>Perkembangan</label>
                <select id="fbPerkembangan">
                  <option>Meningkat</option>
                  <option>Stagnan</option>
                  <option>Menurun</option>
                </select>
              </div>
              <div class="form-field" style="margin-bottom:14px">
                <label>Catatan Perkembangan</label>
                <textarea id="fbCatatan" placeholder="Tuliskan perkembangan mahasiswa..."></textarea>
              </div>
              <button class="btn-sm btn-primary" style="width:100%;padding:10px" onclick="updateFeedback()">🔄 Simpan & Update Model</button>
            </div>
          </div>

          <div class="card">
            <div class="card-header"><span class="card-title">📊 Learning Loop & Model Update</span></div>
            <div class="card-body">
              <div style="text-align:center;padding:10px 0 16px">
                <div style="font-size:3rem;margin-bottom:10px">🔁</div>
                <div style="font-size:.9rem;font-weight:700;margin-bottom:6px">Proses Peningkatan Model</div>
                <div style="font-size:.8rem;color:var(--text2);line-height:1.6;margin-bottom:16px">Data hasil tindakan dan perkembangan mahasiswa diumpan-balikkan ke model ML untuk meningkatkan akurasi prediksi secara berkelanjutan.</div>
              </div>
              <div style="display:flex;flex-direction:column;gap:10px">
                <div style="display:flex;justify-content:space-between;align-items:center;background:var(--surface2);border-radius:8px;padding:10px 14px">
                  <div style="font-size:.82rem">Akurasi Model Sebelumnya</div>
                  <div style="font-weight:700;color:var(--text2);font-family:monospace">88.2%</div>
                </div>
                <div style="display:flex;justify-content:space-between;align-items:center;background:rgba(0,212,170,.08);border:1px solid rgba(0,212,170,.2);border-radius:8px;padding:10px 14px">
                  <div style="font-size:.82rem">Akurasi Model Sekarang</div>
                  <div id="currentAccuracy" style="font-weight:800;color:var(--accent);font-family:monospace">91.4%</div>
                </div>
                <div style="display:flex;justify-content:space-between;align-items:center;background:rgba(34,197,94,.08);border:1px solid rgba(34,197,94,.2);border-radius:8px;padding:10px 14px">
                  <div style="font-size:.82rem">Peningkatan Total</div>
                  <div id="accuracyImprove" style="font-weight:800;color:var(--success);font-family:monospace">↑ +3.2%</div>
                </div>
                <div style="font-size:.76rem;color:var(--text2);text-align:center">Total data training: <strong id="trainingData" style="color:var(--text)">1.250 records</strong></div>
              </div>

              <!-- Model update progress (hidden) -->
              <div id="modelUpdateSection" style="display:none;margin-top:16px">
                <div style="font-size:.82rem;font-weight:600;margin-bottom:8px">🔄 Memperbarui Model...</div>
                <div class="progress-bar"><div class="progress-fill accent" id="modelUpdateBar" style="width:0%"></div></div>
                <div id="modelUpdatePct" style="font-size:.78rem;color:var(--accent);margin-top:4px;font-family:monospace">0%</div>
              </div>
            </div>
          </div>
        </div>

        <div class="card">
          <div class="card-header"><span class="card-title">Alur Proses Sistem (Learning Loop)</span></div>
          <div class="card-body">
            <div style="display:flex;align-items:center;justify-content:center;gap:0;flex-wrap:wrap;padding:10px 0">
              <div style="text-align:center;padding:10px 14px"><div style="font-size:28px;margin-bottom:6px">📝</div><div style="font-size:.72rem;font-weight:700;color:var(--accent)">Input Data</div></div>
              <div style="color:var(--text2);font-size:1.2rem">→</div>
              <div style="text-align:center;padding:10px 14px"><div style="font-size:28px;margin-bottom:6px">⚙️</div><div style="font-size:.72rem;font-weight:700;color:var(--accent2)">Preprocessing</div></div>
              <div style="color:var(--text2);font-size:1.2rem">→</div>
              <div style="text-align:center;padding:10px 14px"><div style="font-size:28px;margin-bottom:6px">🤖</div><div style="font-size:.72rem;font-weight:700;color:var(--warning)">ML Prediksi</div></div>
              <div style="color:var(--text2);font-size:1.2rem">→</div>
              <div style="text-align:center;padding:10px 14px"><div style="font-size:28px;margin-bottom:6px">💡</div><div style="font-size:.72rem;font-weight:700;color:var(--danger)">Rekomendasi</div></div>
              <div style="color:var(--text2);font-size:1.2rem">→</div>
              <div style="text-align:center;padding:10px 14px"><div style="font-size:28px;margin-bottom:6px">🛡️</div><div style="font-size:.72rem;font-weight:700;color:var(--success)">Tindakan</div></div>
              <div style="color:var(--text2);font-size:1.2rem">→</div>
              <div style="text-align:center;padding:10px 14px"><div style="font-size:28px;margin-bottom:6px">🔄</div><div style="font-size:.72rem;font-weight:700;color:var(--accent)">Feedback</div></div>
            </div>
            <div style="text-align:center;font-size:.78rem;color:var(--text2)">↺ <em>Proses berulang untuk meningkatkan akurasi prediksi secara otomatis</em></div>
          </div>
        </div>

        <div class="card" style="margin-top:16px">
          <div class="card-header"><span class="card-title">Log Feedback Tersimpan</span></div>
          <div class="card-body">
            <div class="table-wrap">
              <table>
                <thead><tr><th>Tanggal</th><th>NIM</th><th>Mahasiswa</th><th>IPK Baru</th><th>Absensi</th><th>Perkembangan</th><th>Dampak Model</th></tr></thead>
                <tbody id="feedbackLog">
                  <tr><td>15 Mei 2024</td><td style="font-family:monospace">2021101001</td><td>Andi Saputra</td><td>2.90</td><td>80%</td><td><span class="badge rendah">Meningkat</span></td><td><span style="color:var(--accent);font-weight:700">+0.3%</span></td></tr>
                  <tr><td>10 Mei 2024</td><td style="font-family:monospace">2021101004</td><td>Dwi Kurniawan</td><td>2.55</td><td>75%</td><td><span class="badge sedang">Stagnan</span></td><td><span style="color:var(--text2)">0.0%</span></td></tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>

    </div><!-- .content -->
  </main>
</div><!-- #app -->

<!-- ════════ MODALS ════════ -->

<!-- Modal: Detail Mahasiswa -->
<div id="modalDetail" class="modal-overlay" onclick="closeModal('modalDetail',event)">
  <div class="modal-box">
    <div class="modal-header">
      <span class="modal-title">Detail Mahasiswa</span>
      <button class="modal-close" onclick="document.getElementById('modalDetail').classList.remove('open')">✕</button>
    </div>
    <div class="modal-body">
      <div style="display:flex;align-items:center;gap:16px;margin-bottom:20px">
        <div style="width:56px;height:56px;border-radius:14px;background:linear-gradient(135deg,var(--accent),var(--accent2));display:flex;align-items:center;justify-content:center;font-size:24px;flex-shrink:0">👤</div>
        <div>
          <div style="font-size:1.1rem;font-weight:700" id="mdNama">-</div>
          <div style="font-size:.82rem;color:var(--text2)" id="mdNIM">-</div>
        </div>
        <div style="margin-left:auto" id="mdRisikoTag"></div>
      </div>
      <div class="info-row"><span class="lbl">Program Studi</span><span class="val" id="mdProdi">-</span></div>
      <div class="info-row"><span class="lbl">Semester Aktif</span><span class="val" id="mdSemester">-</span></div>
      <div class="info-row"><span class="lbl">IPK</span><span class="val" id="mdIPK">-</span></div>
      <div class="info-row"><span class="lbl">Absensi</span><span class="val" id="mdAbsensi">-</span></div>
      <div class="info-row"><span class="lbl">Status Ekonomi</span><span class="val" id="mdEkonomi">-</span></div>
      <div class="info-row"><span class="lbl">Status Beasiswa</span><span class="val" id="mdBeasiswa">-</span></div>
      <div class="info-row"><span class="lbl">Probabilitas Dropout</span><span class="val" id="mdPct" style="font-size:1.2rem;font-weight:800">-</span></div>
      <div class="info-row"><span class="lbl">Model Prediksi</span><span class="val" id="mdModel" style="font-size:.78rem;color:var(--text2)">-</span></div>
    </div>
    <div class="modal-footer">
      <button class="btn-sm btn-secondary" onclick="document.getElementById('modalDetail').classList.remove('open')">Tutup</button>
      <button class="btn-sm btn-warning" id="mdBtnRekom" onclick="">💡 Rekomendasikan</button>
      <button class="btn-sm btn-primary" id="mdBtnTindakan" onclick="">🛡️ Tindakan</button>
    </div>
  </div>
</div>

<!-- Modal: Notifikasi -->
<div id="modalNotif" class="modal-overlay" onclick="closeModal('modalNotif',event)">
  <div class="modal-box">
    <div class="modal-header">
      <span class="modal-title">🔔 Notifikasi & Aktivitas</span>
      <button class="modal-close" onclick="document.getElementById('modalNotif').classList.remove('open')">✕</button>
    </div>
    <div class="modal-body">
      <div id="notifList" style="display:flex;flex-direction:column;gap:8px"></div>
    </div>
    <div class="modal-footer">
      <button class="btn-sm btn-secondary" onclick="clearNotif()">Tandai Semua Dibaca</button>
      <button class="btn-sm btn-secondary" onclick="document.getElementById('modalNotif').classList.remove('open')">Tutup</button>
    </div>
  </div>
</div>

<!-- Modal: Konfirmasi Hapus -->
<div id="modalKonfirmasi" class="modal-overlay" onclick="closeModal('modalKonfirmasi',event)">
  <div class="modal-box" style="max-width:380px">
    <div class="modal-header"><span class="modal-title">⚠️ Konfirmasi</span><button class="modal-close" onclick="document.getElementById('modalKonfirmasi').classList.remove('open')">✕</button></div>
    <div class="modal-body">
      <div style="text-align:center;padding:10px 0">
        <div style="font-size:40px;margin-bottom:12px">🗑️</div>
        <div id="konfirmasiMsg" style="font-size:.9rem;margin-bottom:4px">Hapus data ini?</div>
        <div style="font-size:.82rem;color:var(--text2)">Tindakan ini tidak dapat dibatalkan.</div>
      </div>
    </div>
    <div class="modal-footer">
      <button class="btn-sm btn-secondary" onclick="document.getElementById('modalKonfirmasi').classList.remove('open')">Batal</button>
      <button class="btn-sm btn-danger" id="konfirmasiBtn">Hapus</button>
    </div>
  </div>
</div>

    <!-- Modal: Edit Mahasiswa -->
    <div id="modalEdit" class="modal-overlay" onclick="closeModal('modalEdit',event)">
      <div class="modal-box" style="max-width:640px">
        <div class="modal-header"><span class="modal-title">✏️ Edit Mahasiswa</span><button class="modal-close" onclick="document.getElementById('modalEdit').classList.remove('open')">✕</button></div>
        <div class="modal-body">
          <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px">
            <div><label>NIM</label><input id="editNIM" type="text"></div>
            <div><label>Nama</label><input id="editNama" type="text"></div>
            <div><label>Program Studi</label><select id="editProdi">${PRODI.map(p=>`<option value="${p}">${p}</option>`).join('')}</select></div>
            <div><label>IPK</label><input id="editIPK" type="number" step="0.01" min="0" max="4"></div>
            <div><label>Absensi (%)</label><input id="editAbsensi" type="number" min="0" max="100"></div>
            <div><label>Status Ekonomi</label><select id="editEkonomi"><option>Rendah</option><option>Menengah</option><option>Tinggi</option></select></div>
            <div><label>Pekerjaan</label><input id="editPekerjaan" type="text"></div>
            <div><label>Semester</label><input id="editSemester" type="number" min="1"></div>
            <div><label>SKS</label><input id="editSKS" type="number" min="0"></div>
            <div><label>Beasiswa</label><input id="editBeasiswa" type="text"></div>
            <div><label>Model</label><input id="editModel" type="text"></div>
          </div>
        </div>
        <div class="modal-footer">
          <button class="btn-sm btn-secondary" onclick="document.getElementById('modalEdit').classList.remove('open')">Batal</button>
          <button class="btn-sm btn-primary" id="saveEditBtn">Simpan Perubahan</button>
        </div>
      </div>
    </div>

<!-- TOAST -->
<div id="toast">✅ Berhasil!</div>

<script>
/* ════════════════════════════
   DATA STORE
════════════════════════════ */
const MODELS = ['Random Forest','XGBoost','Logistic Regression'];
const PRODI = ['Sistem Informasi','Teknik Informatika','Manajemen','Akuntansi'];

const DB_ERROR = <?php echo json_encode($dbError, JSON_UNESCAPED_UNICODE); ?>;
const mahasiswaDB = <?php echo json_encode($mahasiswa, JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_HEX_AMP|JSON_UNESCAPED_UNICODE); ?>;
const tindakanDB = <?php echo json_encode($tindakan, JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_HEX_AMP|JSON_UNESCAPED_UNICODE); ?>;
const notifDB = <?php echo json_encode($notif, JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_HEX_AMP|JSON_UNESCAPED_UNICODE); ?>;

let currentUser = { name:'Admin Sistem', role:'Administrator', avatar:'AD' };
let currentPage = 'dashboard';

// Pagination state
let mhsPage = 1; const MHS_PER_PAGE = 6;
let prediksiPage = 1; const PRED_PER_PAGE = 6;
let sortKey = '', sortDir = 1;
let filteredMhs = [...mahasiswaDB];
let filteredPrediksi = [...mahasiswaDB];
let selectedStudentIdx = 0;

/* ════════════════════════════
   LOGIN / LOGOUT
════════════════════════════ */
async function doLogin() {
  const u = document.getElementById('loginUser').value.trim();
  const p = document.getElementById('loginPass').value;
  const lu = u.toLowerCase();
  const usersStatic = { admin:{name:'Admin Sistem',role:'Administrator',avatar:'AD'}, dosen:{name:'Dr. Budi Santoso',role:'Dosen Wali',avatar:'DS'} };
  if (usersStatic[lu] && p === '1234') {
    currentUser = usersStatic[lu];
    document.getElementById('loginPage').style.display = 'none';
    document.getElementById('app').classList.add('active');
    document.getElementById('userAvatar').textContent = currentUser.avatar;
    document.getElementById('userName').textContent = currentUser.name;
    document.getElementById('userRole').textContent = currentUser.role;
    document.getElementById('topbarUser').textContent = currentUser.name;
    initAll();
    return;
  }

  try {
    const res = await fetch('api.php?action=login', { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify({ user: u, pass: p }) });
    const data = await res.json();
    if (!data.success) { showToast('❌ ' + (data.message||'Login gagal'), '#ef4444'); return; }
    const user = data.user;
    const initials = (user.nama || user.name || '').split(' ').map(s=>s[0]).slice(0,2).join('').toUpperCase() || 'MS';
    currentUser = { name: user.nama || user.name || user.nim, role: user.role || 'Mahasiswa', avatar: initials };
    document.getElementById('loginPage').style.display = 'none';
    document.getElementById('app').classList.add('active');
    document.getElementById('userAvatar').textContent = currentUser.avatar;
    document.getElementById('userName').textContent = currentUser.name;
    document.getElementById('userRole').textContent = currentUser.role;
    document.getElementById('topbarUser').textContent = currentUser.name;
    initAll();
  } catch (err) {
    console.error(err);
    showToast('❌ Gagal menghubungi server.', '#ef4444');
  }
}

function doLogout() {
  document.getElementById('app').classList.remove('active');
  document.getElementById('loginPage').style.display = 'flex';
  document.getElementById('loginPass').value = '';
  showToast('👋 Berhasil logout!');
}

/* ════════════════════════════
   PAGE NAVIGATION
════════════════════════════ */
const pageTitles = {
  dashboard:'Dashboard Utama', dataMahasiswa:'Data Mahasiswa', inputData:'Input Data Mahasiswa',
  prediksi:'Hasil Prediksi Risiko Dropout', visualisasi:'Dashboard Visualisasi',
  rekomendasi:'Rekomendasi Sistem', tindakan:'Tindakan Preventif', feedback:'Feedback & Update Data'
};

function showPage(id, navEl) {
  document.querySelectorAll('.page').forEach(p => p.classList.remove('active'));
  const pg = document.getElementById('page-' + id);
  if (pg) pg.classList.add('active');
  document.getElementById('pageTitle').textContent = pageTitles[id] || id;
  document.querySelectorAll('.nav-item').forEach(n => n.classList.remove('active'));
  if (navEl) navEl.classList.add('active');
  else {
    const el = document.querySelector(`[data-page="${id}"]`);
    if (el) el.classList.add('active');
  }
  currentPage = id;
  if (id !== 'inputData') document.getElementById('preprocessSection').style.display = 'none';
  if (id === 'dataMahasiswa') renderMahasiswaTable();
  if (id === 'prediksi') renderPrediksiTable();
  if (id === 'rekomendasi') renderRekomendasiList();
  if (id === 'tindakan') { populateTidMhsSelect(); renderRiwayat(); }
  if (id === 'feedback') { populateFbMhsSelect(); }
}

/* ════════════════════════════
   DATA MAHASISWA TABLE
════════════════════════════ */
function filterMahasiswaTable() {
  const q = document.getElementById('searchMahasiswa').value.toLowerCase();
  const prodi = document.getElementById('filterProdi').value;
  const risiko = document.getElementById('filterRisiko').value;
  filteredMhs = mahasiswaDB.filter(m =>
    (m.nim.includes(q) || m.nama.toLowerCase().includes(q)) &&
    (!prodi || m.prodi === prodi) &&
    (!risiko || m.risiko === risiko)
  );
  mhsPage = 1;
  renderMahasiswaTable();
}

function sortTable(key) {
  if (sortKey === key) sortDir *= -1; else { sortKey = key; sortDir = 1; }
  const numKeys = ['ipk','pct'];
  filteredMhs.sort((a,b) => {
    const av = numKeys.includes(key) ? parseFloat(a[key]) : a[key];
    const bv = numKeys.includes(key) ? parseFloat(b[key]) : b[key];
    return av < bv ? -sortDir : av > bv ? sortDir : 0;
  });
  renderMahasiswaTable();
}

function renderMahasiswaTable() {
  const tbody = document.getElementById('mahasiswaTableBody');
  const total = filteredMhs.length;
  const pages = Math.ceil(total / MHS_PER_PAGE);
  const start = (mhsPage - 1) * MHS_PER_PAGE;
  const slice = filteredMhs.slice(start, start + MHS_PER_PAGE);
  document.getElementById('mahasiswaCount').textContent = total + ' mahasiswa';

  if (!slice.length) {
    tbody.innerHTML = `<tr><td colspan="9"><div class="empty-state"><div class="icon">🔍</div><p>Tidak ada mahasiswa ditemukan.</p></div></td></tr>`;
  } else {
    tbody.innerHTML = slice.map((m,i) => {
      const idx = mahasiswaDB.indexOf(m);
      const pctColor = m.risiko==='Tinggi'?'var(--danger)':m.risiko==='Sedang'?'var(--warning)':'var(--success)';
      return `<tr>
        <td><input type="checkbox" class="row-check"></td>
        <td style="font-family:monospace">${m.nim}</td>
        <td style="font-weight:500">${m.nama}</td>
        <td>${m.prodi}</td>
        <td>${m.ipk.toFixed(2)}</td>
        <td>${m.absensi}%</td>
        <td><span class="badge ${m.risiko.toLowerCase()}">${m.risiko}</span></td>
        <td style="font-weight:700;color:${pctColor}">${m.pct}%</td>
        <td style="display:flex;gap:4px;flex-wrap:wrap">
          <button class="btn-sm btn-secondary" onclick="openDetailModal(${idx})">Detail</button>
          <button class="btn-sm btn-warning" onclick="goToRekom(${idx})" title="Rekomendasikan">💡</button>
          <button class="btn-sm btn-danger" onclick="confirmHapus(${idx})" title="Hapus">🗑️</button>
        </td>
      </tr>`;
    }).join('');
  }
  renderPagination('mahasiswaPagination','mahasiswaPageInfo', mhsPage, pages, total, MHS_PER_PAGE, (p)=>{mhsPage=p;renderMahasiswaTable();});
}

function toggleAllCheck(master) {
  document.querySelectorAll('.row-check').forEach(c => c.checked = master.checked);
}

function resetFilter() {
  document.getElementById('searchMahasiswa').value='';
  document.getElementById('filterProdi').value='';
  document.getElementById('filterRisiko').value='';
  filteredMhs = [...mahasiswaDB];
  mhsPage=1; renderMahasiswaTable();
}

/* ════════════════════════════
   PREDIKSI TABLE
════════════════════════════ */
function filterPrediksiTable() {
  const q = document.getElementById('searchPrediksi').value.toLowerCase();
  const risiko = document.getElementById('filterPrediksiRisiko').value;
  const model = document.getElementById('filterPrediksiModel').value;
  filteredPrediksi = mahasiswaDB.filter(m =>
    (m.nim.includes(q) || m.nama.toLowerCase().includes(q)) &&
    (!risiko || m.risiko === risiko) &&
    (!model || m.model === model)
  );
  prediksiPage = 1;
  renderPrediksiTable();
}

function renderPrediksiTable() {
  const tbody = document.getElementById('prediksiTableBody');
  const total = filteredPrediksi.length;
  const pages = Math.ceil(total / PRED_PER_PAGE);
  const start = (prediksiPage - 1) * PRED_PER_PAGE;
  const slice = filteredPrediksi.slice(start, start + PRED_PER_PAGE);

  if (!slice.length) {
    tbody.innerHTML = `<tr><td colspan="8"><div class="empty-state"><div class="icon">🔍</div><p>Tidak ada data prediksi.</p></div></td></tr>`;
  } else {
    tbody.innerHTML = slice.map(m => {
      const idx = mahasiswaDB.indexOf(m);
      const pctColor = m.risiko==='Tinggi'?'#ef4444':m.risiko==='Sedang'?'#f59e0b':'#22c55e';
      const btnCls = m.risiko==='Tinggi'?'btn-danger':m.risiko==='Sedang'?'btn-warning':'btn-secondary';
      return `<tr>
        <td style="font-family:monospace">${m.nim}</td>
        <td style="font-weight:500">${m.nama}</td>
        <td>${m.ipk.toFixed(2)}</td>
        <td>${m.absensi}%</td>
        <td><span class="badge ${m.risiko.toLowerCase()}">${m.risiko}</span></td>
        <td>
          <div style="display:flex;align-items:center;gap:8px">
            <div class="progress-bar" style="width:80px"><div class="progress-fill" style="width:${m.pct}%;background:${pctColor}"></div></div>
            <span style="color:${pctColor};font-weight:700">${m.pct}%</span>
          </div>
        </td>
        <td style="font-size:.75rem;color:var(--text2)">${m.model}</td>
        <td style="display:flex;gap:4px;flex-wrap:wrap">
          <button class="btn-sm btn-secondary" onclick="openDetailModal(${idx})">Detail</button>
          ${m.risiko!=='Rendah'?`<button class="btn-sm ${btnCls}" onclick="goToRekom(${idx})">💡 Rekomendasi</button>`:''}
        </td>
      </tr>`;
    }).join('');
  }
  renderPagination('prediksiPagination','prediksiPageInfo', prediksiPage, pages, total, PRED_PER_PAGE, (p)=>{prediksiPage=p;renderPrediksiTable();});
}

/* ════════════════════════════
   PAGINATION HELPER
════════════════════════════ */
function renderPagination(paginId, infoId, current, pages, total, perPage, cb) {
  const el = document.getElementById(paginId);
  const info = document.getElementById(infoId);
  if (!el) return;
  if (info) info.textContent = `Menampilkan ${Math.min((current-1)*perPage+1, total)}–${Math.min(current*perPage, total)} dari ${total}`;
  if (pages <= 1) { el.innerHTML = ''; return; }
  let html = `<button class="page-btn" ${current<=1?'disabled':''} onclick="(${cb.toString()})(${current-1})">‹</button>`;
  for (let i=1;i<=pages;i++) {
    if (i===1||i===pages||Math.abs(i-current)<=1) {
      html += `<button class="page-btn ${i===current?'active':''}" onclick="(${cb.toString()})(${i})">${i}</button>`;
    } else if (Math.abs(i-current)===2) {
      html += `<span style="color:var(--text2);padding:0 4px">…</span>`;
    }
  }
  html += `<button class="page-btn" ${current>=pages?'disabled':''} onclick="(${cb.toString()})(${current+1})">›</button>`;
  el.innerHTML = html;
}

/* ════════════════════════════
   MODAL: DETAIL
════════════════════════════ */
function openDetailModal(idx) {
  const m = mahasiswaDB[idx];
  selectedStudentIdx = idx;
  document.getElementById('mdNama').textContent = m.nama;
  document.getElementById('mdNIM').textContent = m.nim + ' · ' + m.prodi;
  document.getElementById('mdProdi').textContent = m.prodi;
  document.getElementById('mdSemester').textContent = 'Semester ' + m.semester;
  document.getElementById('mdIPK').textContent = m.ipk.toFixed(2);
  document.getElementById('mdAbsensi').textContent = m.absensi + '%';
  document.getElementById('mdEkonomi').textContent = m.ekonomi;
  document.getElementById('mdBeasiswa').textContent = m.beasiswa;
  const pctColor = m.risiko==='Tinggi'?'var(--danger)':m.risiko==='Sedang'?'var(--warning)':'var(--success)';
  document.getElementById('mdPct').textContent = m.pct + '%';
  document.getElementById('mdPct').style.color = pctColor;
  document.getElementById('mdModel').textContent = m.model;
  document.getElementById('mdRisikoTag').innerHTML = `<span class="badge ${m.risiko.toLowerCase()}">${m.risiko}</span>`;
  document.getElementById('mdBtnRekom').onclick = () => { document.getElementById('modalDetail').classList.remove('open'); goToRekom(idx); };
  document.getElementById('mdBtnTindakan').onclick = () => { document.getElementById('modalDetail').classList.remove('open'); goToTindakan(idx); };
  document.getElementById('modalDetail').classList.add('open');
}

function closeModal(id, e) {
  if (e.target.id === id) document.getElementById(id).classList.remove('open');
}

/* ════════════════════════════
   NAVIGATE TO REKOM/TINDAKAN
════════════════════════════ */
function goToRekom(idx) {
  selectedStudentIdx = idx;
  const m = mahasiswaDB[idx];
  // Pre-fill rekomendasi & show it
  showPage('rekomendasi', document.querySelector('[data-page=rekomendasi]'));
  selectRekomendasiMhs(idx);
}

function goToTindakan(idx) {
  selectedStudentIdx = idx;
  showPage('tindakan', document.querySelector('[data-page=tindakan]'));
  const sel = document.getElementById('tidSelectMhs');
  if (sel) { sel.value = idx; onTidMhsChange(); }
}

/* ════════════════════════════
   REKOMENDASI LIST
════════════════════════════ */
function renderRekomendasiList() {
  const q = document.getElementById('searchRekom').value.toLowerCase();
  const risiko = document.getElementById('filterRekRisiko').value;
  const list = mahasiswaDB.filter(m =>
    (m.nim.includes(q) || m.nama.toLowerCase().includes(q)) &&
    (!risiko || m.risiko === risiko)
  );
  const container = document.getElementById('rekomendasiList');
  if (!list.length) {
    container.innerHTML = `<div class="empty-state"><div class="icon">🔍</div><p>Tidak ada mahasiswa ditemukan.</p></div>`;
    return;
  }
  container.innerHTML = list.map(m => {
    const idOrIdx = m.id !== undefined && m.id !== null ? m.id : mahasiswaDB.indexOf(m);
    const pctColor = m.risiko==='Tinggi'?'var(--danger)':m.risiko==='Sedang'?'var(--warning)':'var(--success)';
    return `<div style="display:flex;align-items:center;justify-content:space-between;background:var(--surface2);border:1px solid var(--border);border-radius:10px;padding:12px 16px;cursor:pointer;transition:border-color .2s" onmouseover="this.style.borderColor='var(--accent)'" onmouseout="this.style.borderColor='var(--border)'" onclick="selectRekomendasiMhsById(${idOrIdx})">
      <div style="display:flex;align-items:center;gap:12px">
        <div style="width:38px;height:38px;border-radius:8px;background:linear-gradient(135deg,var(--accent),var(--accent2));display:flex;align-items:center;justify-content:center;font-size:16px;flex-shrink:0">👤</div>
        <div>
          <div style="font-weight:600;font-size:.9rem">${m.nama}</div>
          <div style="font-size:.75rem;color:var(--text2)">${m.nim} · ${m.prodi}</div>
        </div>
      </div>
      <div style="display:flex;align-items:center;gap:8px">
        <span class="badge ${m.risiko.toLowerCase()}">${m.risiko}</span>
        <span style="font-weight:800;font-family:monospace;color:${pctColor}">${m.pct}%</span>
        <div style="display:flex;gap:6px">
          <button class="btn-sm btn-primary" onclick="event.stopPropagation();selectRekomendasiMhsById(${idOrIdx})">Pilih</button>
          <button class="btn-sm btn-warning" onclick="event.stopPropagation();openEditModalById(${idOrIdx})">Edit</button>
          <button class="btn-sm btn-danger" onclick="event.stopPropagation();confirmHapusById(${idOrIdx})">Hapus</button>
        </div>
      </div>
    </div>`;
  }).join('');
}

function filterRekomendasiList() { renderRekomendasiList(); }

function selectRekomendasiMhs(idx) {
  const m = mahasiswaDB[idx];
  selectedStudentIdx = idx;
  document.getElementById('rekNIM').textContent = m.nim;
  document.getElementById('rekNama').textContent = m.nama;
  document.getElementById('rekProdi').textContent = m.prodi;
  document.getElementById('rekIPK').textContent = m.ipk.toFixed(2);
  document.getElementById('rekAbsensi').textContent = m.absensi + '%';
  const pctColor = m.risiko==='Tinggi'?'var(--danger)':m.risiko==='Sedang'?'var(--warning)':'var(--success)';
  document.getElementById('rekPct').textContent = m.pct + '%';
  document.getElementById('rekPct').style.color = pctColor;
  document.getElementById('rekRisikoAwal')&&(document.getElementById('rekRisikoAwal').textContent = m.risiko);
  document.getElementById('rekRisikoAwal')&&(document.getElementById('rekProb')&&(document.getElementById('rekProb').textContent = m.pct+'%'));
  document.getElementById('rekRisikoBadge').innerHTML = `<span class="badge ${m.risiko.toLowerCase()}">${m.risiko}</span>`;

  // Faktor risiko
  const ipkScore = Math.max(0, Math.min(100, (4 - m.ipk) / 4 * 100));
  const absensiScore = Math.max(0, 100 - m.absensi);
  const ekonomiScore = m.ekonomi==='Rendah'?70:m.ekonomi==='Menengah'?40:15;
  document.getElementById('fIPK').textContent = ipkScore>60?'Tinggi':ipkScore>30?'Sedang':'Rendah';
  document.getElementById('fIPK').style.color = ipkScore>60?'var(--danger)':ipkScore>30?'var(--warning)':'var(--success)';
  document.getElementById('fIPKBar').style.width = ipkScore + '%';
  document.getElementById('fIPKBar').style.background = ipkScore>60?'#ef4444':ipkScore>30?'#f59e0b':'#22c55e';
  document.getElementById('fAbsensi').textContent = absensiScore>40?'Tinggi':absensiScore>20?'Sedang':'Rendah';
  document.getElementById('fAbsensi').style.color = absensiScore>40?'var(--danger)':absensiScore>20?'var(--warning)':'var(--success)';
  document.getElementById('fAbsensiBar').style.width = absensiScore + '%';
  document.getElementById('fAbsensiBar').style.background = absensiScore>40?'#ef4444':absensiScore>20?'#f59e0b':'#22c55e';
  document.getElementById('fEkonomi').textContent = ekonomiScore>60?'Tinggi':ekonomiScore>30?'Sedang':'Rendah';
  document.getElementById('fEkonomiBar').style.width = ekonomiScore + '%';

  // Rekomendasi content
  const rekContent = document.getElementById('rekContent');
  let html = '';
  if (m.risiko === 'Tinggi') {
    html = `
      <div class="rekom-card tinggi">
        <div class="rekom-icon">🚨</div>
        <div><div class="rekom-title" style="color:var(--danger)">Risiko Tinggi — Intervensi Segera Diperlukan</div>
        <div class="rekom-desc">Mahasiswa ini memerlukan perhatian khusus segera. Jadwalkan konseling intensif, pantau kehadiran mingguan, dan koordinasikan dengan orang tua/wali.</div></div>
      </div>
      <div class="rekom-card sedang">
        <div class="rekom-icon">📚</div>
        <div><div class="rekom-title" style="color:var(--warning)">Pendampingan Akademik</div>
        <div class="rekom-desc">Berikan bimbingan belajar tambahan, terutama pada mata kuliah dengan nilai di bawah rata-rata. Dorong untuk aktif di kelompok belajar.</div></div>
      </div>
      <div class="rekom-card rendah">
        <div class="rekom-icon">💰</div>
        <div><div class="rekom-title" style="color:var(--accent)">Bantuan Finansial</div>
        <div class="rekom-desc">${m.ekonomi==='Rendah'?'Rekomendasikan untuk program beasiswa KIP Kuliah atau bantuan dana darurat kampus.':'Pastikan tidak ada kendala finansial yang menghambat kegiatan akademik.'}</div></div>
      </div>`;
  } else if (m.risiko === 'Sedang') {
    html = `
      <div class="rekom-card sedang">
        <div class="rekom-icon">📢</div>
        <div><div class="rekom-title" style="color:var(--warning)">Risiko Sedang — Monitoring Aktif</div>
        <div class="rekom-desc">Lakukan monitoring rutin setiap dua minggu. Dorong peningkatan kehadiran dan perbaikan nilai mata kuliah yang rendah.</div></div>
      </div>
      <div class="rekom-card rendah">
        <div class="rekom-icon">🎯</div>
        <div><div class="rekom-title" style="color:var(--accent)">Penetapan Target Akademik</div>
        <div class="rekom-desc">Bantu mahasiswa menetapkan target IPK dan kehadiran yang realistis. Evaluasi progress setiap akhir semester.</div></div>
      </div>`;
  } else {
    html = `
      <div class="rekom-card rendah">
        <div class="rekom-icon">✅</div>
        <div><div class="rekom-title" style="color:var(--success)">Risiko Rendah — Pertahankan Performa</div>
        <div class="rekom-desc">Mahasiswa dalam kondisi baik. Berikan apresiasi dan motivasi untuk terus meningkatkan prestasi. Dorong untuk menjadi mentor bagi mahasiswa lain.</div></div>
      </div>`;
  }
  html += `<div style="margin-top:16px;display:flex;gap:8px;flex-wrap:wrap">
    <button class="btn-sm btn-primary" style="padding:10px 24px" onclick="goToTindakan(${idx})">🛡️ Terapkan Tindakan Preventif</button>
    <button class="btn-sm btn-secondary" style="padding:10px 20px" onclick="exportData('rekomendasi')">📥 Export Laporan</button>
  </div>`;
  rekContent.innerHTML = html;
  document.getElementById('rekomendasiDetail').style.display = 'block';
}

function selectRekomendasiMhsById(id) {
  const idx = mahasiswaDB.findIndex(m => (m.id !== undefined && m.id !== null ? m.id : -1) == id);
  if (idx === -1) return;
  selectRekomendasiMhs(idx);
}

function openEditModalById(id) {
  const idx = mahasiswaDB.findIndex(m => (m.id !== undefined && m.id !== null ? m.id : -1) == id);
  if (idx === -1) return;
  openEditModal(idx);
}

function confirmHapusById(id) {
  const idx = mahasiswaDB.findIndex(m => (m.id !== undefined && m.id !== null ? m.id : -1) == id);
  if (idx === -1) return;
  confirmHapus(idx);
}

/* ════════════════════════════
   TINDAKAN PREVENTIF
════════════════════════════ */
function populateTidMhsSelect() {
  const sel = document.getElementById('tidSelectMhs');
  sel.innerHTML = mahasiswaDB.map((m,i) =>
    `<option value="${i}">${m.nama} (${m.nim}) — Risiko ${m.risiko}</option>`
  ).join('');
  onTidMhsChange();
}

function onTidMhsChange() {
  const idx = parseInt(document.getElementById('tidSelectMhs').value);
  const m = mahasiswaDB[idx];
  document.getElementById('tidNIM').textContent = m.nim;
  document.getElementById('tidNama').textContent = m.nama;
  document.getElementById('tidRisiko').innerHTML = `<span class="badge ${m.risiko.toLowerCase()}">${m.risiko} (${m.pct}%)</span>`;
  document.getElementById('tidModel').textContent = m.model;
}

function resetTindakanForm() {
  document.getElementById('tidJenis').value = 'Konseling';
  document.getElementById('tidTanggal').value = '';
  document.getElementById('tidDosen').value = '';
  document.getElementById('tidCatatan').value = '';
  document.getElementById('tidStatus').value = 'Dijadwalkan';
}

function simpanTindakan() {
  const idx = parseInt(document.getElementById('tidSelectMhs').value);
  const m = mahasiswaDB[idx];
  const jenis = document.getElementById('tidJenis').value;
  const tanggal = document.getElementById('tidTanggal').value;
  const dosen = document.getElementById('tidDosen').value.trim() || currentUser.name;
  const status = document.getElementById('tidStatus').value;
  const catatan = document.getElementById('tidCatatan').value.trim();

  if (!tanggal) { showToast('⚠️ Pilih tanggal tindakan!','#f59e0b'); return; }

  tindakanDB.unshift({ tanggal, nim:m.nim, nama:m.nama, jenis, dosen, status, catatan });
  
  // Update stats
  updateTindakanStats();
  renderRiwayat();
  resetTindakanForm();
  showToast('✅ Tindakan preventif berhasil disimpan!');

  // Add activity
  addActivity('🛡️', `Tindakan ${jenis} dicatat untuk ${m.nama}`, 'baru saja');
  updateNotifDot();
}

function renderRiwayat() {
  const filter = document.getElementById('filterTindakanStatus').value;
  const data = filter ? tindakanDB.filter(t => t.status === filter) : tindakanDB;
  const tbody = document.getElementById('riwayatTindakan');
  if (!data.length) {
    tbody.innerHTML = `<tr><td colspan="7"><div class="empty-state"><div class="icon">📋</div><p>Belum ada riwayat tindakan.</p></div></td></tr>`;
    return;
  }
  const statusCls = { 'Selesai':'rendah', 'Dijadwalkan':'sedang', 'Sedang Berlangsung':'info' };
  tbody.innerHTML = data.map((t,i) => {
    const tgl = new Date(t.tanggal);
    const fmt = isNaN(tgl) ? t.tanggal : tgl.toLocaleDateString('id-ID',{day:'numeric',month:'short',year:'numeric'});
    return `<tr>
      <td>${fmt}</td>
      <td style="font-family:monospace">${t.nim}</td>
      <td>${t.nama}</td>
      <td>${t.jenis}</td>
      <td>${t.dosen}</td>
      <td><span class="badge ${statusCls[t.status]||'info'}">${t.status}</span></td>
      <td><button class="btn-sm btn-secondary" onclick="showTindakanDetail(${i})" title="${t.catatan||'(Tidak ada catatan)'}">Detail</button></td>
    </tr>`;
  }).join('');
}

function showTindakanDetail(i) {
  const t = tindakanDB[i];
  showToast(`📋 ${t.jenis} — ${t.nama}: "${t.catatan||'Tidak ada catatan'}"`, t.status==='Selesai'?'#22c55e':'#f59e0b');
}

function updateTindakanStats() {
  const selesai = tindakanDB.filter(t=>t.status==='Selesai').length;
  const jadwal = tindakanDB.filter(t=>t.status==='Dijadwalkan').length;
  document.getElementById('statTindakanTotal').textContent = tindakanDB.length;
  document.getElementById('statTindakanSelesai').textContent = selesai;
  document.getElementById('statTindakanJadwal').textContent = jadwal;
}

/* ════════════════════════════
   FEEDBACK & UPDATE
════════════════════════════ */
function populateFbMhsSelect() {
  const sel = document.getElementById('fbSelectMhs');
  sel.innerHTML = mahasiswaDB.map((m,i) =>
    `<option value="${i}">${m.nama} (${m.nim})</option>`
  ).join('');
  onFbMhsChange();
}

function onFbMhsChange() {
  const idx = parseInt(document.getElementById('fbSelectMhs').value);
  const m = mahasiswaDB[idx];
  document.getElementById('fbNIM').textContent = m.nim;
  document.getElementById('fbNama').textContent = m.nama;
  const el1 = document.getElementById('fbRisikoAwal');
  const el2 = document.getElementById('fbProb');
  if (el1) el1.innerHTML = `<span class="badge ${m.risiko.toLowerCase()}">${m.risiko}</span>`;
  if (el2) el2.textContent = m.pct + '%';
  document.getElementById('fbIPK').value = m.ipk;
  document.getElementById('fbAbsensi').value = m.absensi;
}

let accuracyVal = 91.4;
function updateFeedback() {
  const idx = parseInt(document.getElementById('fbSelectMhs').value);
  const m = mahasiswaDB[idx];
  const newIPK = parseFloat(document.getElementById('fbIPK').value) || m.ipk;
  const newAbsensi = parseFloat(document.getElementById('fbAbsensi').value) || m.absensi;
  const perkembangan = document.getElementById('fbPerkembangan').value;
  const catatan = document.getElementById('fbCatatan').value.trim();

  // Recalculate risk based on new values
  const newPct = calcRisk(newIPK, newAbsensi, m.ekonomi);
  const newRisiko = newPct >= 65 ? 'Tinggi' : newPct >= 40 ? 'Sedang' : 'Rendah';

  // Update DB
  mahasiswaDB[idx].ipk = newIPK;
  mahasiswaDB[idx].absensi = newAbsensi;
  mahasiswaDB[idx].risiko = newRisiko;
  mahasiswaDB[idx].pct = newPct;

  // Animate model update
  const sec = document.getElementById('modelUpdateSection');
  sec.style.display = 'block';
  const improv = perkembangan==='Meningkat'?0.2:perkembangan==='Menurun'?-0.1:0;
  animateProgress('modelUpdate', 0, 100, 2000, () => {
    accuracyVal = Math.min(99, Math.max(80, accuracyVal + improv));
    document.getElementById('currentAccuracy').textContent = accuracyVal.toFixed(1) + '%';
    document.getElementById('accuracyImprove').textContent = '↑ +' + (accuracyVal - 88.2).toFixed(1) + '%';
    document.getElementById('trainingData').textContent = (mahasiswaDB.length + 1250) + ' records';
    sec.style.display = 'none';
    setProgress('modelUpdate', 0, null, null);
    showToast('✅ Data feedback disimpan! Model berhasil diperbarui → ' + accuracyVal.toFixed(1) + '%');
  });

  // Add to feedback log
  const tbody = document.getElementById('feedbackLog');
  const perkCls = perkembangan==='Meningkat'?'rendah':perkembangan==='Menurun'?'tinggi':'sedang';
  const today = new Date().toLocaleDateString('id-ID',{day:'numeric',month:'short',year:'numeric'});
  const tr = document.createElement('tr');
  tr.innerHTML = `<td>${today}</td><td style="font-family:monospace">${m.nim}</td><td>${m.nama}</td><td>${newIPK.toFixed(2)}</td><td>${newAbsensi}%</td><td><span class="badge ${perkCls}">${perkembangan}</span></td><td><span style="color:${improv>0?'var(--accent)':improv<0?'var(--danger)':'var(--text2)'};font-weight:700">${improv>0?'+':''}{${improv.toFixed(1)}}%</span></td>`;
  tbody.insertBefore(tr, tbody.firstChild);

  addActivity('🔄', `Feedback ${m.nama} diupdate — Perkembangan: ${perkembangan}`, 'baru saja');
}

function calcRisk(ipk, absensi, ekonomi) {
  let score = 0;
  score += (4 - Math.min(4, Math.max(0, ipk))) / 4 * 50;
  score += (100 - Math.min(100, Math.max(0, absensi))) / 100 * 35;
  if (ekonomi === 'Rendah') score += 15;
  else if (ekonomi === 'Menengah') score += 5;
  return Math.round(Math.min(99, Math.max(5, score)));
}

/* ════════════════════════════
   INPUT DATA → PREPROCESSING
════════════════════════════ */
function resetInputForm() {
  ['inputNIM','inputNama','inputIPK','inputAbsensi','inputSKS'].forEach(id => document.getElementById(id).value = '');
  ['inputProdi','inputEkonomi','inputPekerjaan','inputSemester','inputBeasiswa'].forEach(id => {
    const el = document.getElementById(id); if(el) el.selectedIndex=0;
  });
  document.getElementById('preprocessSection').style.display = 'none';
}

function simpanData() {
  const nim = document.getElementById('inputNIM').value.trim();
  const nama = document.getElementById('inputNama').value.trim();
  const ipk = parseFloat(document.getElementById('inputIPK').value);
  const absensi = parseInt(document.getElementById('inputAbsensi').value);

  if (!nim || !nama) { showToast('⚠️ NIM dan Nama wajib diisi!','#f59e0b'); return; }
  if (isNaN(ipk)||ipk<0||ipk>4) { showToast('⚠️ IPK harus antara 0–4!','#f59e0b'); return; }
  if (isNaN(absensi)||absensi<0||absensi>100) { showToast('⚠️ Absensi harus antara 0–100!','#f59e0b'); return; }
  if (mahasiswaDB.find(m=>m.nim===nim)) { showToast('⚠️ NIM sudah terdaftar!','#ef4444'); return; }

  const payload = {
    nim,
    nama,
    prodi: document.getElementById('inputProdi').value,
    ipk,
    absensi,
    ekonomi: document.getElementById('inputEkonomi').value,
    pekerjaan: document.getElementById('inputPekerjaan').value,
    semester: parseInt(document.getElementById('inputSemester').value) || 3,
    sks: parseInt(document.getElementById('inputSKS').value) || 0,
    beasiswa: document.getElementById('inputBeasiswa').value,
    save: 1
  };

  const sec = document.getElementById('preprocessSection');
  sec.style.display = 'block';
  sec.scrollIntoView({ behavior:'smooth', block:'start' });
  document.getElementById('prepLog').textContent = '';
  document.getElementById('mlLog').textContent = '';
  setProgress('prep', 0, 'Memulai preprocessing...', 'Cleaning & Encoding data');
  setProgress('ml', 0, 'Menunggu preprocessing...', 'Random Forest, XGBoost, dll');

  const prepLogs = ['[1/4] Memvalidasi tipe data...', '[2/4] Membersihkan nilai null...', '[3/4] Label encoding kategori...', '[4/4] Normalisasi fitur numerik...'];
  const mlLogs = ['[1/3] Random Forest: membangun 100 pohon...', '[2/3] XGBoost: gradient boosting...', '[3/3] Ensemble voting & kalkulasi probabilitas...'];

  let logStep = 0;
  const logInterval = setInterval(() => {
    if (logStep < prepLogs.length) {
      document.getElementById('prepLog').textContent += prepLogs[logStep] + '\n';
    } else if (logStep < prepLogs.length + mlLogs.length) {
      document.getElementById('mlLog').textContent += mlLogs[logStep - prepLogs.length] + '\n';
    }
    logStep++;
  }, 500);

  animateProgress('prep', 0, 100, 2200, () => {
    setProgress('prep', 100, 'Preprocessing selesai!', '✅ Data siap untuk model');
    setTimeout(() => {
      animateProgress('ml', 0, 100, 2500, () => {
        clearInterval(logInterval);
        setProgress('ml', 100, 'Prediksi selesai!', '✅ Hasil siap ditampilkan');

        fetch('proses_prediksi.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify(payload)
        })
        .then(response => response.json())
        .then(data => {
          if (!data.success) {
            showToast('❌ ' + data.message, '#ef4444');
            return;
          }
          const newMhs = data.record;
          mahasiswaDB.unshift(newMhs);
          filteredMhs = [...mahasiswaDB];
          filteredPrediksi = [...mahasiswaDB];

          setTimeout(() => {
            showToast(`✅ ${newMhs.nama} berhasil ditambahkan — Risiko ${newMhs.risiko} (${newMhs.pct}%)`);
            addActivity('📝', `Data ${newMhs.nama} ditambahkan — Risiko ${newMhs.risiko}`, 'baru saja');
            setTimeout(() => {
              showPage('prediksi', document.querySelector('[data-page=prediksi]'));
            }, 800);
          }, 400);
        })
        .catch(() => {
          showToast('❌ Gagal menyimpan ke server.', '#ef4444');
        });
      });
    }, 300);
  });
}

/* ════════════════════════════
   BATCH PREDICTION
════════════════════════════ */
function runBatchPrediction() {
  const sec = document.getElementById('batchSection');
  sec.style.display = 'block';
  ['RF','XG','Ens'].forEach(k => { setProgress('batch'+k, 0, null, null); document.getElementById('batch'+k).textContent='0%'; });
  animateProgress('batchRF', 0, 100, 2000, () => {
    document.getElementById('batchRF').textContent = '100%';
    animateProgress('batchXG', 0, 100, 1800, () => {
      document.getElementById('batchXG').textContent = '100%';
      animateProgress('batchEns', 0, 100, 1200, () => {
        document.getElementById('batchEns').textContent = '100%';
        setTimeout(() => { sec.style.display='none'; showToast('✅ Prediksi batch selesai — '+mahasiswaDB.length+' mahasiswa dianalisis!'); addActivity('🤖', 'Prediksi batch selesai untuk '+mahasiswaDB.length+' mahasiswa', 'baru saja'); }, 500);
      });
    });
  });
}

/* ════════════════════════════
   VISUALISASI TABS
════════════════════════════ */
function switchVisTab(tab, btn) {
  document.querySelectorAll('.vis-tab').forEach(t => t.style.display='none');
  document.getElementById('visTab-'+tab).style.display='block';
  document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
  btn.classList.add('active');
}

/* ════════════════════════════
   NOTIFICATIONS
════════════════════════════ */
function updateNotifDot() {
  const unread = notifDB.filter(n=>!n.read).length;
  document.getElementById('notifDot').style.display = unread?'block':'none';
}

function openNotifModal() {
  const list = document.getElementById('notifList');
  list.innerHTML = notifDB.map((n,i) => `
    <div style="display:flex;gap:10px;padding:10px 12px;border-radius:8px;background:${!n.read?'rgba(0,212,170,.05)':'transparent'};border:1px solid ${!n.read?'rgba(0,212,170,.15)':'transparent'};cursor:pointer" onclick="markNotifRead(${i})">
      <span style="font-size:20px;flex-shrink:0">${n.icon}</span>
      <div style="flex:1">
        <div style="font-size:.83rem;font-weight:${n.read?'400':'600'}">${n.msg}</div>
        <div style="font-size:.75rem;color:var(--text2)">${n.time}</div>
      </div>
      ${!n.read?'<div style="width:8px;height:8px;background:var(--accent);border-radius:50%;flex-shrink:0;margin-top:4px"></div>':''}
    </div>`).join('');
  document.getElementById('modalNotif').classList.add('open');
}

function markNotifRead(i) { notifDB[i].read=true; openNotifModal(); updateNotifDot(); }
function clearNotif() { notifDB.forEach(n=>n.read=true); openNotifModal(); updateNotifDot(); }

/* ════════════════════════════
   ACTIVITY
════════════════════════════ */
function addActivity(icon, msg, time) {
  const list = document.getElementById('activityList');
  const div = document.createElement('div');
  div.className = 'activity-item';
  div.innerHTML = `<span style="font-size:18px">${icon}</span><div><div style="font-size:.83rem;font-weight:600">${msg}</div><div style="font-size:.76rem;color:var(--text2)">${time}</div></div>`;
  list.insertBefore(div, list.firstChild);
  if (list.children.length > 8) list.lastChild.remove();
  notifDB.unshift({ icon, msg, time, read:false });
  updateNotifDot();
}

/* ════════════════════════════
   HAPUS / KONFIRMASI
════════════════════════════ */
let pendingHapusIdx = -1;
function confirmHapus(idx) {
  pendingHapusIdx = idx;
  const rec = mahasiswaDB[idx];
  document.getElementById('konfirmasiMsg').textContent = `Hapus data ${rec.nama} (${rec.nim})?`;
  document.getElementById('konfirmasiBtn').onclick = () => {
    if (!rec || !rec.id) {
      // fallback: remove locally
      mahasiswaDB.splice(idx, 1);
      filteredMhs = [...mahasiswaDB];
      filteredPrediksi = [...mahasiswaDB];
      document.getElementById('modalKonfirmasi').classList.remove('open');
      renderMahasiswaTable();
      showToast('🗑️ Data mahasiswa berhasil dihapus!');
      return;
    }
    fetch('api.php?action=delete', { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify({ id: rec.id }) })
      .then(r => r.json())
      .then(data => {
        if (!data.success) { showToast('❌ ' + (data.message||'Gagal menghapus'), '#ef4444'); return; }
        mahasiswaDB.splice(idx, 1);
        filteredMhs = [...mahasiswaDB];
        filteredPrediksi = [...mahasiswaDB];
        document.getElementById('modalKonfirmasi').classList.remove('open');
        renderMahasiswaTable();
        renderPrediksiTable();
        showToast('🗑️ Data mahasiswa berhasil dihapus!');
      })
      .catch(()=> showToast('❌ Gagal menghapus data.', '#ef4444'));
  };
  document.getElementById('modalKonfirmasi').classList.add('open');
}

/* ════════════════════════════
   EXPORT
════════════════════════════ */
function exportData(type) {
  if (type === 'mahasiswa_pdf') { exportMahasiswaPDF('Daftar Mahasiswa', filteredMhs); return; }
  if (type === 'mahasiswa_csv') { exportMahasiswaCSV(filteredMhs); return; }
  if (type === 'prediksi_pdf') { exportMahasiswaPDF('Hasil Prediksi', filteredPrediksi); return; }
  if (type === 'tindakan_csv') { exportTindakanCSV(); return; }
  showToast('📥 Ekspor belum tersedia untuk jenis ini.');
}

function exportMahasiswaCSV(rows) {
  if (!rows || !rows.length) { showToast('⚠️ Tidak ada data untuk diekspor.', '#f59e0b'); return; }
  const hdr = ['NIM','Nama','Prodi','IPK','Absensi','Risiko','Pct','Model'];
  const data = rows.map(r => [r.nim, r.nama, r.prodi, Number(r.ipk).toFixed(2), r.absensi + '%', r.risiko, r.pct + '%', r.model]);
  const csv = [hdr, ...data].map(row => row.map(cell => '"'+String(cell).replace(/"/g,'""')+'"').join(',')).join('\r\n');
  const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
  const url = URL.createObjectURL(blob);
  const a = document.createElement('a'); a.href = url; a.download = 'mahasiswa.csv'; a.click(); URL.revokeObjectURL(url);
  showToast('📥 CSV berhasil diunduh!');
}

function exportMahasiswaPDF(title='Daftar Mahasiswa', rows=[]) {
  if (!rows || !rows.length) { showToast('⚠️ Tidak ada data untuk diekspor.', '#f59e0b'); return; }
  const doc = new window.jspdf.jsPDF({ orientation: 'landscape' });
  doc.setFontSize(14); doc.text(title, 14, 20); doc.setFontSize(10); doc.text('Tanggal: '+new Date().toLocaleDateString('id-ID'), 14, 28);
  const headers = ['NIM','Nama','Prodi','IPK','Absensi','Risiko','%','Model'];
  const body = rows.map(m=>[m.nim,m.nama,m.prodi,Number(m.ipk).toFixed(2),m.absensi+'%',m.risiko,m.pct+'%',m.model]);
  // Try autoTable if available
  if (doc.autoTable) { doc.autoTable({ head: [headers], body, startY: 34, styles:{fontSize:9} }); }
  else {
    let y = 34; const colW = [30,70,60,18,22,26,14,40];
    doc.setFontSize(9);
    doc.text(headers.join(' | '), 14, y); y+=8;
    body.forEach(row=>{ let x=14; row.forEach((c,ci)=>{ doc.text(String(c), x, y); x+=colW[ci]||40; }); y+=8; if (y>180){doc.addPage(); y=20;} });
  }
  doc.save(title.replace(/\s+/g,'_').toLowerCase()+'.pdf');
  showToast('📥 PDF berhasil dibuat!');
}

/* ════════════════════════════
   DASHBOARD FILTER SHORTCUT
════════════════════════════ */
function filterDashboard(risiko) {
  showPage('dataMahasiswa', document.querySelector('[data-page=dataMahasiswa]'));
  document.getElementById('filterRisiko').value = risiko.charAt(0).toUpperCase()+risiko.slice(1);
  filterMahasiswaTable();
}

/* ════════════════════════════
   PROGRESS UTILITIES
════════════════════════════ */
function setProgress(key, pct, status, sub) {
  const pctEl = document.getElementById(key+'Pct');
  const barEl = document.getElementById(key+'Bar');
  if (pctEl) pctEl.textContent = pct + '%';
  if (barEl) barEl.style.width = pct + '%';
  const statusEl = document.getElementById(key+'Status');
  const subEl = document.getElementById(key+'Sub');
  if (status && statusEl) statusEl.textContent = status;
  if (sub && subEl) subEl.textContent = sub;
}

function animateProgress(key, from, to, dur, cb) {
  const start = performance.now();
  function step(now) {
    const t = Math.min((now - start) / dur, 1);
    const ease = t < .5 ? 2*t*t : -1+(4-2*t)*t;
    const val = Math.round(from + (to - from) * ease);
    setProgress(key, val, null, null);
    if (t < 1) requestAnimationFrame(step);
    else if (cb) cb();
  }
  requestAnimationFrame(step);
}

/* ════════════════════════════
   TOAST
════════════════════════════ */
function showToast(msg, color) {
  const t = document.getElementById('toast');
  t.textContent = msg;
  t.style.borderColor = color || 'var(--success)';
  t.style.color = color || 'var(--success)';
  t.style.background = color ? color.replace(')',',0.1)').replace('rgb','rgba').replace('#','') : '#1e2a1e';
  if (color === '#ef4444') t.style.background = 'rgba(239,68,68,.1)';
  else if (color === '#f59e0b') t.style.background = 'rgba(245,158,11,.1)';
  else t.style.background = '#1e2a1e';
  t.classList.add('show');
  clearTimeout(t._timer);
  t._timer = setTimeout(() => t.classList.remove('show'), 3500);
}

/* ════════════════════════════
   INIT
════════════════════════════ */
function initAll() {
  // Set today's date for tindakan form
  document.getElementById('tidTanggal').value = new Date().toISOString().slice(0,10);
  // fetch fresh data from API and render
  fetch('api.php?action=list')
    .then(r=>r.json())
    .then(data=>{
      if (data.success) {
        const rows = data.records || data.mahasiswa || [];
        mahasiswaDB = (rows).map(normalizeMahasiswa);
        tindakanDB = data.tindakan || [];
        notifDB = data.notif || [];
      }
      filteredMhs = [...mahasiswaDB];
      filteredPrediksi = [...mahasiswaDB];
      renderMahasiswaTable();
      renderPrediksiTable();
      renderRekomendasiList();
      renderTindakanList();
      renderNotif();
      updateTindakanStats();
      updateNotifDot();
      // Set default page
      showPage('dashboard', document.querySelector('[data-page=dashboard]'));
    })
    .catch(err=>{
      console.error(err);
      filteredMhs = [...mahasiswaDB];
      filteredPrediksi = [...mahasiswaDB];
      renderMahasiswaTable();
      renderPrediksiTable();
      renderRekomendasiList();
      renderTindakanList();
      renderNotif();
      updateTindakanStats();
      updateNotifDot();
      showPage('dashboard', document.querySelector('[data-page=dashboard]'));
    });
}

function normalizeMahasiswa(m){
  return Object.assign({ id:null, nim:'', nama:'', prodi:'', ipk:0, absensi:0, ekonomi:'-', pekerjaan:'', semester:0, sks:0, beasiswa:'', risiko:'', pct:0, model:'' }, m || {});
}
</script>
</body>
</html>


