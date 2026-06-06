<?php
// Konfigurasi koneksi database MySQL untuk XAMPP
function getConnection(): PDO {
    $dbHost = '127.0.0.1';
    $dbName = 'dropout_db';
    $dbUser = 'root';
    $dbPass = '';

    $pdo = new PDO("mysql:host=$dbHost;charset=utf8mb4", $dbUser, $dbPass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);

    $pdo->exec("CREATE DATABASE IF NOT EXISTS `$dbName` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    $pdo->exec("USE `$dbName`");
    ensureSchema($pdo);

    return $pdo;
}

function ensureSchema(PDO $pdo): void {
    $pdo->exec("CREATE TABLE IF NOT EXISTS `mahasiswa` (
        `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
        `nim` VARCHAR(20) NOT NULL UNIQUE,
        `nama` VARCHAR(120) NOT NULL,
        `prodi` VARCHAR(120) NOT NULL,
        `ipk` DECIMAL(3,2) NOT NULL DEFAULT 0.00,
        `absensi` TINYINT UNSIGNED NOT NULL DEFAULT 0,
        `ekonomi` VARCHAR(50) NOT NULL,
        `pekerjaan` VARCHAR(80) NOT NULL,
        `semester` TINYINT UNSIGNED NOT NULL DEFAULT 1,
        `sks` SMALLINT UNSIGNED NOT NULL DEFAULT 0,
        `beasiswa` VARCHAR(60) NOT NULL,
        `risiko` VARCHAR(20) NOT NULL,
        `pct` TINYINT UNSIGNED NOT NULL DEFAULT 0,
        `model` VARCHAR(80) NOT NULL,
        `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");

    $pdo->exec("CREATE TABLE IF NOT EXISTS `tindakan` (
        `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
        `tanggal` DATE NOT NULL,
        `nim` VARCHAR(20) NOT NULL,
        `nama` VARCHAR(120) NOT NULL,
        `jenis` VARCHAR(120) NOT NULL,
        `dosen` VARCHAR(120) NOT NULL,
        `status` VARCHAR(60) NOT NULL,
        `catatan` TEXT,
        `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");

    $pdo->exec("CREATE TABLE IF NOT EXISTS `notif` (
        `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
        `icon` VARCHAR(10) NOT NULL,
        `msg` VARCHAR(255) NOT NULL,
        `time` VARCHAR(60) NOT NULL,
        `is_read` TINYINT(1) NOT NULL DEFAULT 0,
        `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");
}
