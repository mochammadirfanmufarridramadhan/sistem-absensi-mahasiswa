<?php
require_once __DIR__ . '/config.php';

function calculateRisk(float $ipk, int $absensi, string $ekonomi): int {
    $ipk = max(0.0, min(4.0, $ipk));
    $absensi = max(0, min(100, $absensi));
    $score = (4.0 - $ipk) / 4.0 * 50.0;
    $score += (100 - $absensi) / 100.0 * 35.0;
    if (strtolower($ekonomi) === 'rendah') {
        $score += 15.0;
    } elseif (strtolower($ekonomi) === 'menengah') {
        $score += 5.0;
    }
    return (int) round(min(99.0, max(5.0, $score)));
}

function getJsonInput(): array {
    $input = json_decode(file_get_contents('php://input'), true);
    return is_array($input) ? $input : [];
}

function respondJson(array $payload): void {
    header('Content-Type: application/json; charset=UTF-8');
    echo json_encode($payload, JSON_UNESCAPED_UNICODE);
    exit;
}

function ensureUsersTable(PDO $pdo): void {
    $pdo->exec("CREATE TABLE IF NOT EXISTS `users` (
        `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
        `nim` VARCHAR(20) NOT NULL UNIQUE,
        `nama` VARCHAR(120) NOT NULL,
        `email` VARCHAR(150) NOT NULL UNIQUE,
        `password` VARCHAR(255) NOT NULL,
        `role` VARCHAR(30) NOT NULL DEFAULT 'mahasiswa',
        `reset_token` VARCHAR(128) DEFAULT NULL,
        `reset_expires` DATETIME DEFAULT NULL,
        `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");
}

function getPostValue(array $source, string $key, string $default = ''): string {
    return isset($source[$key]) ? trim((string) $source[$key]) : $default;
}
