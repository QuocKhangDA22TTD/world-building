<?php
/**
 * Script để thêm role admin vào database
 */

require_once '../app/core/Database.php';
require_once '../config/config.php';

try {
    $db = Database::getInstance();
    
    echo "Đang thêm cột role vào bảng users...\n";
    
    // Kiểm tra xem cột role đã tồn tại chưa
    $checkColumn = $db->query("SHOW COLUMNS FROM users LIKE 'role'");
    if ($checkColumn->rowCount() == 0) {
        $db->exec("ALTER TABLE users ADD COLUMN role ENUM('user', 'admin') DEFAULT 'user' AFTER email");
        echo "✓ Đã thêm cột role\n";
    } else {
        echo "✓ Cột role đã tồn tại\n";
    }
    
    echo "\nĐang tạo tài khoản admin mặc định...\n";
    
    // Tạo password hash cho 'admin123'
    $passwordHash = password_hash('admin123', PASSWORD_BCRYPT, ['cost' => 12]);
    
    // Kiểm tra xem admin đã tồn tại chưa
    $checkAdmin = $db->prepare("SELECT user_id FROM users WHERE username = 'admin'");
    $checkAdmin->execute();
    
    if ($checkAdmin->rowCount() == 0) {
        // Tạo mới admin
        $stmt = $db->prepare("
            INSERT INTO users (username, email, password, role) 
            VALUES ('admin', 'admin@worldbuilding.local', :password, 'admin')
        ");
        $stmt->execute(['password' => $passwordHash]);
        echo "✓ Đã tạo tài khoản admin\n";
    } else {
        // Cập nhật role thành admin
        $stmt = $db->prepare("
            UPDATE users 
            SET role = 'admin', password = :password 
            WHERE username = 'admin'
        ");
        $stmt->execute(['password' => $passwordHash]);
        echo "✓ Đã cập nhật tài khoản admin\n";
    }
    
    echo "\n========================================\n";
    echo "Migration hoàn tất!\n";
    echo "========================================\n";
    echo "Thông tin đăng nhập Admin:\n";
    echo "Username: admin\n";
    echo "Password: admin123\n";
    echo "========================================\n";
    
} catch (PDOException $e) {
    echo "Lỗi: " . $e->getMessage() . "\n";
    exit(1);
}
?>
