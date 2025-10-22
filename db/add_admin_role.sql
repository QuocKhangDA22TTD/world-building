-- Thêm cột role vào bảng users
ALTER TABLE users 
ADD COLUMN role ENUM('user', 'admin') DEFAULT 'user' AFTER email;

-- Tạo user admin mặc định (username: admin, password: admin123)
-- Password hash cho 'admin123' với bcrypt cost 12
INSERT INTO users (username, email, password, role) 
VALUES ('admin', 'admin@worldbuilding.local', '$2y$12$LQv3c1yy2kBz3KZ5J8b6g.p7/AYf9h3F5B1O9Z3x6KJ5L7M8N9O0P', 'admin')
ON DUPLICATE KEY UPDATE role = 'admin';
