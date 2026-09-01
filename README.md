# Sistem Absensi Mahasiswa

Repo project: Sistem Absensi Mahasiswa (PHP + MySQL)

Project files:
- `index.php` - UI utama
- `api.php` - endpoint CRUD (jika ada)
- `config.php` - konfigurasi DB
- `db_init.sql` - skrip inisialisasi database
- `input.php`, `proses_prediksi.php`, `hasil.php`, `register.php`, `reset_password.php`

Cara cepat push ke GitHub (setelah menginstall Git):

1. Install Git: https://git-scm.com/download/win
2. Buka PowerShell di folder proyek (`c:\xampp\htdocs\dropout_mahasiswa`)
3. Jalankan (atau gunakan `push_to_github.ps1` yang disediakan):

```powershell
git init
git add .
git commit -m "Initial commit"
git branch -M main
git remote add origin https://github.com/mochammadirfanmufarridramadhan/sistem-absensi-mahasiswa.git
git push -u origin main
```

Atau jalankan skrip PowerShell:

```powershell
powershell -ExecutionPolicy Bypass -File .\push_to_github.ps1 -RemoteUrl "https://github.com/mochammadirfanmufarridramadhan/sistem-absensi-mahasiswa.git" -Branch "main"
```

Catatan:
- Pastikan Git terpasang dan Anda sudah login ke GitHub (atau gunakan credential manager)
- Skrip hanya menambahkan remote jika belum ada
