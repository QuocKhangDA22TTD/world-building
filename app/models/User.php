<?php
/**
 * User Model
 * Quản lý người dùng - đăng ký, đăng nhập, xác thực
 */
class User {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    /**
     * Tìm người dùng theo username
     * @param string $username - Tên đăng nhập
     * @return array|false - Thông tin người dùng hoặc false nếu không tìm thấy
     */
    public function findByUsername($username) {
        try {
            $stmt = $this->db->prepare("
                SELECT user_id, username, email, password, role, created_at 
                FROM users 
                WHERE username = :username
            ");
            $stmt->execute(['username' => $username]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error in findByUsername: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Tìm người dùng theo email
     * @param string $email - Email
     * @return array|false - Thông tin người dùng hoặc false nếu không tìm thấy
     */
    public function findByEmail($email) {
        try {
            $stmt = $this->db->prepare("
                SELECT user_id, username, email, password, role, created_at 
                FROM users 
                WHERE email = :email
            ");
            $stmt->execute(['email' => $email]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error in findByEmail: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Tạo người dùng mới
     * @param string $username - Tên đăng nhập
     * @param string $email - Email
     * @param string $password - Mật khẩu (plain text)
     * @return int|false - ID người dùng mới hoặc false nếu lỗi
     */
    public function createUser($username, $email, $password) {
        try {
            // Hash password với bcrypt
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
            
            $stmt = $this->db->prepare("
                INSERT INTO users (username, email, password, created_at) 
                VALUES (:username, :email, :password, NOW())
            ");
            
            $stmt->execute([
                'username' => $username,
                'email' => $email,
                'password' => $hashedPassword
            ]);
            
            return $this->db->lastInsertId();
        } catch (PDOException $e) {
            error_log("Error in createUser: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Xác thực mật khẩu
     * @param string $plainPassword - Mật khẩu plain text
     * @param string $hashedPassword - Mật khẩu đã hash từ database
     * @return bool - true nếu mật khẩu đúng, false nếu sai
     */
    public function verifyPassword($plainPassword, $hashedPassword) {
        return password_verify($plainPassword, $hashedPassword);
    }
    
    /**
     * Kiểm tra username đã tồn tại chưa
     * @param string $username - Tên đăng nhập cần kiểm tra
     * @return bool - true nếu đã tồn tại, false nếu chưa
     */
    public function usernameExists($username) {
        try {
            $stmt = $this->db->prepare("SELECT COUNT(*) as count FROM users WHERE username = :username");
            $stmt->execute(['username' => $username]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return (int)$result['count'] > 0;
        } catch (PDOException $e) {
            error_log("Error in usernameExists: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Kiểm tra email đã tồn tại chưa
     * @param string $email - Email cần kiểm tra
     * @return bool - true nếu đã tồn tại, false nếu chưa
     */
    public function emailExists($email) {
        try {
            $stmt = $this->db->prepare("SELECT COUNT(*) as count FROM users WHERE email = :email");
            $stmt->execute(['email' => $email]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return (int)$result['count'] > 0;
        } catch (PDOException $e) {
            error_log("Error in emailExists: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Lấy thông tin người dùng theo ID
     * @param int $userId - ID người dùng
     * @return array|false - Thông tin người dùng hoặc false nếu không tìm thấy
     */
    public function getById($userId) {
        try {
            $stmt = $this->db->prepare("
                SELECT user_id, username, email, role, created_at 
                FROM users 
                WHERE user_id = :user_id
            ");
            $stmt->execute(['user_id' => $userId]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error in getById: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Cập nhật thông tin người dùng
     * @param int $userId - ID người dùng
     * @param string $email - Email mới
     * @param string $newPassword - Mật khẩu mới (để trống nếu không đổi)
     * @return bool - True nếu thành công, false nếu thất bại
     */
    public function updateProfile($userId, $email, $newPassword = '') {
        try {
            if (!empty($newPassword)) {
                // Cập nhật cả email và password
                $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT, ['cost' => 12]);
                $stmt = $this->db->prepare("
                    UPDATE users 
                    SET email = :email, password = :password 
                    WHERE user_id = :user_id
                ");
                $stmt->execute([
                    'email' => $email,
                    'password' => $hashedPassword,
                    'user_id' => $userId
                ]);
            } else {
                // Chỉ cập nhật email
                $stmt = $this->db->prepare("
                    UPDATE users 
                    SET email = :email 
                    WHERE user_id = :user_id
                ");
                $stmt->execute([
                    'email' => $email,
                    'user_id' => $userId
                ]);
            }
            
            return true;
        } catch (PDOException $e) {
            error_log("Error in updateProfile: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Lấy tổng số người dùng
     */
    public function getTotalUsers() {
        try {
            $stmt = $this->db->query("SELECT COUNT(*) as total FROM users");
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['total'];
        } catch (PDOException $e) {
            error_log("Error in getTotalUsers: " . $e->getMessage());
            return 0;
        }
    }
    
    /**
     * Lấy số người dùng mới hôm nay
     */
    public function getNewUsersToday() {
        try {
            $stmt = $this->db->query("
                SELECT COUNT(*) as total 
                FROM users 
                WHERE DATE(created_at) = CURDATE()
            ");
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['total'];
        } catch (PDOException $e) {
            error_log("Error in getNewUsersToday: " . $e->getMessage());
            return 0;
        }
    }
    
    /**
     * Lấy danh sách người dùng mới nhất
     */
    public function getRecentUsers($limit = 10) {
        try {
            $stmt = $this->db->prepare("
                SELECT user_id, username, email, role, created_at 
                FROM users 
                ORDER BY created_at DESC 
                LIMIT :limit
            ");
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error in getRecentUsers: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Lấy tất cả người dùng
     */
    public function getAllUsers() {
        try {
            $stmt = $this->db->query("
                SELECT user_id, username, email, role, created_at,
                       (SELECT COUNT(*) FROM worlds WHERE worlds.user_id = users.user_id) as world_count
                FROM users 
                ORDER BY created_at DESC
            ");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error in getAllUsers: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Tìm kiếm người dùng
     */
    public function searchUsers($keyword) {
        try {
            $stmt = $this->db->prepare("
                SELECT user_id, username, email, role, created_at,
                       (SELECT COUNT(*) FROM worlds WHERE worlds.user_id = users.user_id) as world_count
                FROM users 
                WHERE username LIKE :keyword OR email LIKE :keyword
                ORDER BY created_at DESC
            ");
            $stmt->execute(['keyword' => "%{$keyword}%"]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error in searchUsers: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Xóa người dùng
     */
    public function deleteUser($userId) {
        try {
            $stmt = $this->db->prepare("DELETE FROM users WHERE user_id = :user_id");
            $stmt->execute(['user_id' => $userId]);
            return true;
        } catch (PDOException $e) {
            error_log("Error in deleteUser: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Thay đổi role người dùng
     */
    public function changeRole($userId, $role) {
        try {
            $stmt = $this->db->prepare("
                UPDATE users 
                SET role = :role 
                WHERE user_id = :user_id
            ");
            $stmt->execute([
                'role' => $role,
                'user_id' => $userId
            ]);
            return true;
        } catch (PDOException $e) {
            error_log("Error in changeRole: " . $e->getMessage());
            return false;
        }
    }
}
?>
