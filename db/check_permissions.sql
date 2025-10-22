-- =====================================================
-- SCRIPT QUẢN LÝ HỆ THỐNG PHÂN QUYỀN
-- =====================================================
-- File này bao gồm:
-- 1. Migration: Tạo/cập nhật role system
-- 2. Queries: Kiểm tra và thống kê permissions
-- =====================================================

-- =====================================================
-- PHẦN 1: MIGRATION - Chạy lần đầu để setup
-- =====================================================

-- Bước 1: Kiểm tra xem cột role đã tồn tại chưa
-- SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS 
-- WHERE TABLE_NAME = 'users' AND COLUMN_NAME = 'role';

-- Bước 2: Thêm cột role nếu chưa có (cho hệ thống cũ)
-- ALTER TABLE users 
-- ADD COLUMN role ENUM('user', 'admin') DEFAULT 'user' AFTER email;

-- Bước 3: Mở rộng role từ 2 cấp lên 4 cấp (nếu đã có role)
-- ALTER TABLE users 
-- MODIFY COLUMN role ENUM('user', 'moderator', 'admin', 'super_admin') DEFAULT 'user';

-- Bước 4: Tạo tài khoản admin mặc định
-- Password hash cho 'admin123' với bcrypt cost 12
-- INSERT INTO users (username, email, password, role) 
-- VALUES 
--     ('superadmin', 'superadmin@worldbuilding.local', '$2y$12$LQv3c1yy2kBz3KZ5J8b6g.p7/AYf9h3F5B1O9Z3x6KJ5L7M8N9O0P', 'super_admin'),
--     ('admin', 'admin@worldbuilding.local', '$2y$12$LQv3c1yy2kBz3KZ5J8b6g.p7/AYf9h3F5B1O9Z3x6KJ5L7M8N9O0P', 'admin'),
--     ('moderator', 'moderator@worldbuilding.local', '$2y$12$LQv3c1yy2kBz3KZ5J8b6g.p7/AYf9h3F5B1O9Z3x6KJ5L7M8N9O0P', 'moderator')
-- ON DUPLICATE KEY UPDATE role = VALUES(role);

-- =====================================================
-- PHẦN 2: KIỂM TRA - Queries để xem thông tin
-- =====================================================

-- 1. Kiểm tra cấu trúc cột role
SHOW COLUMNS FROM users LIKE 'role';
-- Expected: ENUM('user','moderator','admin','super_admin') DEFAULT 'user'

-- 2. Xem tất cả users và role của họ
SELECT 
    user_id,
    username,
    email,
    role,
    created_at,
    (SELECT COUNT(*) FROM worlds WHERE worlds.user_id = users.user_id) as world_count
FROM users
ORDER BY 
    FIELD(role, 'super_admin', 'admin', 'moderator', 'user'),
    created_at DESC;

-- 3. Thống kê users theo role
SELECT 
    role,
    COUNT(*) as total,
    CONCAT(ROUND(COUNT(*) * 100.0 / (SELECT COUNT(*) FROM users), 2), '%') as percentage
FROM users
GROUP BY role
ORDER BY FIELD(role, 'super_admin', 'admin', 'moderator', 'user');

-- 4. Kiểm tra tài khoản admin mặc định
SELECT user_id, username, email, role, created_at
FROM users
WHERE username IN ('superadmin', 'admin', 'moderator')
ORDER BY FIELD(role, 'super_admin', 'admin', 'moderator');

-- 5. Users mới nhất theo từng role
SELECT 
    role,
    username,
    email,
    created_at
FROM (
    SELECT *,
           ROW_NUMBER() OVER (PARTITION BY role ORDER BY created_at DESC) as rn
    FROM users
) as ranked
WHERE rn <= 3
ORDER BY FIELD(role, 'super_admin', 'admin', 'moderator', 'user'), created_at DESC;

-- 6. Kiểm tra users không có role (nếu có lỗi)
SELECT user_id, username, email, role
FROM users
WHERE role IS NULL OR role NOT IN ('user', 'moderator', 'admin', 'super_admin');

-- 7. Thống kê worlds theo role của owner
SELECT 
    u.role,
    COUNT(w.world_id) as total_worlds,
    COUNT(DISTINCT u.user_id) as total_users,
    ROUND(COUNT(w.world_id) / COUNT(DISTINCT u.user_id), 2) as avg_worlds_per_user
FROM users u
LEFT JOIN worlds w ON u.user_id = w.user_id
GROUP BY u.role
ORDER BY FIELD(u.role, 'super_admin', 'admin', 'moderator', 'user');

-- 8. Tìm users có nhiều worlds nhất (theo role)
SELECT 
    u.username,
    u.role,
    COUNT(w.world_id) as world_count,
    MAX(w.created_at) as last_world_created
FROM users u
LEFT JOIN worlds w ON u.user_id = w.user_id
GROUP BY u.user_id, u.username, u.role
HAVING COUNT(w.world_id) > 0
ORDER BY world_count DESC, u.role
LIMIT 10;

-- =====================================================
-- TEST CASES - Chạy từng câu để test
-- =====================================================

-- TEST 1: Tạo user mới (role mặc định phải là 'user')
-- INSERT INTO users (username, email, password, role) 
-- VALUES ('testuser', 'test@example.com', '$2y$12$...hash...', DEFAULT);
-- SELECT username, role FROM users WHERE username = 'testuser';
-- Expected role: 'user'

-- TEST 2: Thăng user lên moderator
-- UPDATE users SET role = 'moderator' WHERE username = 'testuser';
-- SELECT username, role FROM users WHERE username = 'testuser';
-- Expected role: 'moderator'

-- TEST 3: Thăng moderator lên admin
-- UPDATE users SET role = 'admin' WHERE username = 'testuser';
-- SELECT username, role FROM users WHERE username = 'testuser';
-- Expected role: 'admin'

-- TEST 4: Thăng admin lên super_admin
-- UPDATE users SET role = 'super_admin' WHERE username = 'testuser';
-- SELECT username, role FROM users WHERE username = 'testuser';
-- Expected role: 'super_admin'

-- TEST 5: Hạ super_admin xuống user
-- UPDATE users SET role = 'user' WHERE username = 'testuser';
-- SELECT username, role FROM users WHERE username = 'testuser';
-- Expected role: 'user'

-- TEST 6: Thử set role không hợp lệ (phải lỗi)
-- UPDATE users SET role = 'invalid_role' WHERE username = 'testuser';
-- Expected: Error 1265 - Data truncated for column 'role'

-- =====================================================
-- CLEANUP TEST DATA (nếu cần)
-- =====================================================
-- DELETE FROM users WHERE username = 'testuser';

-- =====================================================
-- UTILITY QUERIES
-- =====================================================

-- Reset tất cả user thường về role 'user'
-- UPDATE users SET role = 'user' 
-- WHERE role != 'user' 
--   AND username NOT IN ('superadmin', 'admin', 'moderator');

-- Đếm số lượng users online (nếu có bảng sessions)
-- SELECT role, COUNT(DISTINCT user_id) as active_users
-- FROM sessions s
-- JOIN users u ON s.user_id = u.user_id
-- WHERE last_activity > DATE_SUB(NOW(), INTERVAL 15 MINUTE)
-- GROUP BY role;

-- Tìm users chưa từng tạo world
SELECT 
    u.user_id,
    u.username,
    u.email,
    u.role,
    u.created_at,
    DATEDIFF(NOW(), u.created_at) as days_since_signup
FROM users u
LEFT JOIN worlds w ON u.user_id = w.user_id
WHERE w.world_id IS NULL
  AND u.role = 'user'
ORDER BY u.created_at DESC;

-- Top contributors (users có nhiều worlds + entities)
SELECT 
    u.username,
    u.role,
    COUNT(DISTINCT w.world_id) as total_worlds,
    COUNT(DISTINCT e.entity_id) as total_entities,
    COUNT(DISTINCT r.relationship_id) as total_relationships
FROM users u
LEFT JOIN worlds w ON u.user_id = w.user_id
LEFT JOIN entities e ON w.world_id = e.world_id
LEFT JOIN relationships r ON w.world_id = r.world_id
GROUP BY u.user_id, u.username, u.role
HAVING total_worlds > 0
ORDER BY total_entities DESC, total_worlds DESC
LIMIT 20;
