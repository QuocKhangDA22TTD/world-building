<?php
class Schema {
    public static function run() {
        // Bước 1: Tạo database nếu chưa có
        $root = Database::getRootConnection();
        $root->exec("CREATE DATABASE IF NOT EXISTS `" . DB_NAME . "` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");

        // Bước 2: Kết nối lại với db cụ thể
        $pdo = Database::getInstance();

        // Bước 3: Tạo bảng (nhiều bảng cũng để vào đây)
        $pdo->exec("
            CREATE TABLE IF NOT EXISTS users (
                id INT AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(100) NOT NULL,
                email VARCHAR(100) NOT NULL UNIQUE,
                password VARCHAR(255) NOT NULL,
                role ENUM('reader', 'author', 'admin') NOT NULL DEFAULT 'reader',
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )
        ");
    }
}
