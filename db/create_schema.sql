
USE {{DB_NAME}};

-- Bảng người dùng
CREATE TABLE IF NOT EXISTS users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(150) UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Bảng thế giới
CREATE TABLE IF NOT EXISTS worlds (
    world_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    name VARCHAR(255) NOT NULL,
    article LONGTEXT, -- Bài viết chi tiết như Wikipedia
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
);

-- Bảng thực thể
CREATE TABLE IF NOT EXISTS entities (
    entity_id INT AUTO_INCREMENT PRIMARY KEY,
    world_id INT,
    name VARCHAR(255) NOT NULL,
    type VARCHAR(100), -- Ví dụ: nhân vật, sinh vật, sự kiện, vũ khí...
    article LONGTEXT,  -- Bài viết chi tiết như Wikipedia
    attributes JSON,   -- Thuộc tính động dạng JSON
    FOREIGN KEY (world_id) REFERENCES worlds(world_id) ON DELETE CASCADE
);

-- Bảng mối quan hệ giữa các thực thể
CREATE TABLE IF NOT EXISTS relationships (
    relationship_id INT AUTO_INCREMENT PRIMARY KEY,
    world_id INT,
    entity1_id INT,
    entity2_id INT,
    type VARCHAR(100) NOT NULL,   -- Ví dụ: gia đình, bạn bè, kẻ thù
    description TEXT,
    FOREIGN KEY (world_id) REFERENCES worlds(world_id) ON DELETE CASCADE,
    FOREIGN KEY (entity1_id) REFERENCES entities(entity_id) ON DELETE CASCADE,
    FOREIGN KEY (entity2_id) REFERENCES entities(entity_id) ON DELETE CASCADE
);



