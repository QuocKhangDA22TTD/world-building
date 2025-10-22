<?php
/**
 * Script để mở rộng hệ thống phân quyền
 * Thêm các role: super_admin, moderator
 */

require_once '../app/core/Database.php';
require_once '../config/config.php';

try {
    $db = Database::getInstance();
    
    echo "=== NÂNG CẤP HỆ THỐNG PHÂN QUYỀN ===\n\n";
    
    // Bước 1: Mở rộng ENUM role
    echo "Bước 1: Mở rộng cột role...\n";
    $db->exec("ALTER TABLE users MODIFY COLUMN role ENUM('user', 'moderator', 'admin', 'super_admin') DEFAULT 'user'");
    echo "✓ Đã mở rộng role: user, moderator, admin, super_admin\n\n";
    
    // Bước 2: Tạo tài khoản super_admin
    echo "Bước 2: Tạo tài khoản Super Admin...\n";
    $passwordHash = password_hash('superadmin123', PASSWORD_BCRYPT, ['cost' => 12]);
    
    $checkSuperAdmin = $db->prepare("SELECT user_id FROM users WHERE username = 'superadmin'");
    $checkSuperAdmin->execute();
    
    if ($checkSuperAdmin->rowCount() == 0) {
        $stmt = $db->prepare("
            INSERT INTO users (username, email, password, role) 
            VALUES ('superadmin', 'superadmin@worldbuilding.local', :password, 'super_admin')
        ");
        $stmt->execute(['password' => $passwordHash]);
        echo "✓ Đã tạo tài khoản Super Admin\n";
        echo "  Username: superadmin\n";
        echo "  Password: superadmin123\n";
    } else {
        $stmt = $db->prepare("UPDATE users SET role = 'super_admin', password = :password WHERE username = 'superadmin'");
        $stmt->execute(['password' => $passwordHash]);
        echo "✓ Đã cập nhật tài khoản Super Admin\n";
    }
    
    // Bước 3: Tạo tài khoản moderator mẫu
    echo "\nBước 3: Tạo tài khoản Moderator mẫu...\n";
    $modPasswordHash = password_hash('mod123', PASSWORD_BCRYPT, ['cost' => 12]);
    
    $checkMod = $db->prepare("SELECT user_id FROM users WHERE username = 'moderator'");
    $checkMod->execute();
    
    if ($checkMod->rowCount() == 0) {
        $stmt = $db->prepare("
            INSERT INTO users (username, email, password, role) 
            VALUES ('moderator', 'moderator@worldbuilding.local', :password, 'moderator')
        ");
        $stmt->execute(['password' => $modPasswordHash]);
        echo "✓ Đã tạo tài khoản Moderator\n";
        echo "  Username: moderator\n";
        echo "  Password: mod123\n";
    } else {
        $stmt = $db->prepare("UPDATE users SET role = 'moderator', password = :password WHERE username = 'moderator'");
        $stmt->execute(['password' => $modPasswordHash]);
        echo "✓ Đã cập nhật tài khoản Moderator\n";
    }
    
    // Bước 4: Kiểm tra admin hiện tại
    echo "\nBước 4: Kiểm tra tài khoản Admin...\n";
    $checkAdmin = $db->prepare("SELECT user_id, username, role FROM users WHERE role IN ('admin', 'super_admin')");
    $checkAdmin->execute();
    $admins = $checkAdmin->fetchAll(PDO::FETCH_ASSOC);
    
    echo "✓ Danh sách quản trị viên:\n";
    foreach ($admins as $admin) {
        $roleLabel = [
            'super_admin' => 'Super Admin',
            'admin' => 'Admin'
        ][$admin['role']] ?? $admin['role'];
        echo "  - {$admin['username']} ({$roleLabel})\n";
    }
    
    echo "\n=== HOÀN THÀNH ===\n\n";
    echo "Thông tin đăng nhập:\n";
    echo "┌────────────────┬──────────────────┬────────────────┐\n";
    echo "│ Tài khoản      │ Mật khẩu         │ Quyền          │\n";
    echo "├────────────────┼──────────────────┼────────────────┤\n";
    echo "│ superadmin     │ superadmin123    │ Super Admin    │\n";
    echo "│ admin          │ admin123         │ Admin          │\n";
    echo "│ moderator      │ mod123           │ Moderator      │\n";
    echo "└────────────────┴──────────────────┴────────────────┘\n\n";
    
    echo "Phân quyền:\n";
    echo "• Super Admin: Toàn quyền, quản lý tất cả users (kể cả admin khác)\n";
    echo "• Admin: Quản lý users thường, không sửa admin/super_admin\n";
    echo "• Moderator: Xem users, quản lý worlds (xem + xóa)\n";
    echo "• User: Chỉ quản lý worlds của mình\n\n";
    
} catch (PDOException $e) {
    echo "✗ Lỗi: " . $e->getMessage() . "\n";
    exit(1);
}
