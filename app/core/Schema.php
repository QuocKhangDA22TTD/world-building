<?php
class Schema {
    public static function run() {
        // Bước 1: Tạo database nếu chưa có
        $root = Database::getRootConnection();
        $root->exec('CREATE DATABASE IF NOT EXISTS `' . DB_NAME . '` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci');

        // Bước 2: Kết nối lại với db cụ thể
        $pdo = Database::getInstance();

        // Bước 3: Đọc file SQL và thực thi
        $sqlFilePath = '../../world-building/db/create_schema.sql';
         // Sử dụng đường dẫn tuyệt đối để tránh lỗi
        $sql = file_get_contents($sqlFilePath);
        $sql = str_replace('{{DB_NAME}}', DB_NAME, $sql);

        // Kiểm tra xem file SQL có tồn tại và nội dung hợp lệ không
        if ($sql === false) {
            throw new Exception("Không thể đọc file SQL.");
        }

        // Thực thi câu lệnh SQL
        $pdo->exec($sql);
    }
}

