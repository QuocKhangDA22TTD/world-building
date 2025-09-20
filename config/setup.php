<?php
// 1. Kết nối tới MySQL (chưa chọn database)
try {
    $pdo = new PDO("mysql:host=" . DB_HOST, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // 2. Tạo database nếu chưa có
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `" . DB_NAME . "` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");

    // 3. Kết nối lại, lần này đã chọn DB
    $pdo->exec("USE `" . DB_NAME . "`");

    // 4. Tạo bảng nếu chưa có
    $sql = "
        CREATE TABLE IF NOT EXISTS users (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(100) NOT NULL,
            email VARCHAR(100) NOT NULL UNIQUE,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        );
    ";

    $pdo->exec($sql);

    echo "Cài đặt cơ sở dữ liệu thành công.";
} catch (PDOException $e) {
    echo "Lỗi kết nối hoặc tạo CSDL: " . $e->getMessage();
    exit;
}
