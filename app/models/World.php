<?php
/**
 * World Model
 * Xử lý tất cả các thao tác database liên quan đến bảng worlds
 */
class World {
    private $db;
    
    /**
     * Constructor - Khởi tạo kết nối database
     */
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    /**
     * Lấy tất cả thế giới của một user
     * @param int $userId - ID của user
     * @return array - Mảng các thế giới
     */
    public function getAllByUserId($userId) {
        try {
            $stmt = $this->db->prepare("
                SELECT world_id, user_id, name, article, created_at 
                FROM worlds 
                WHERE user_id = :user_id 
                ORDER BY created_at DESC
            ");
            $stmt->execute(['user_id' => $userId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error in getAllByUserId: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Lấy thông tin một thế giới theo ID
     * @param int $worldId - ID của thế giới
     * @return array|false - Thông tin thế giới hoặc false nếu không tìm thấy
     */
    public function getById($worldId) {
        try {
            $stmt = $this->db->prepare("
                SELECT world_id, user_id, name, article, created_at 
                FROM worlds 
                WHERE world_id = :world_id
            ");
            $stmt->execute(['world_id' => $worldId]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error in getById: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Tạo thế giới mới
     * @param int $userId - ID của user tạo thế giới
     * @param string $name - Tên thế giới
     * @param string $article - Bài viết mô tả chi tiết
     * @return int|false - ID của thế giới mới tạo hoặc false nếu lỗi
     */
    public function create($userId, $name, $article = '') {
        try {
            $stmt = $this->db->prepare("
                INSERT INTO worlds (user_id, name, article, created_at) 
                VALUES (:user_id, :name, :article, NOW())
            ");
            
            $success = $stmt->execute([
                'user_id' => $userId,
                'name' => $name,
                'article' => $article
            ]);
            
            if ($success) {
                return $this->db->lastInsertId();
            }
            return false;
        } catch (PDOException $e) {
            error_log("Error in create: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Cập nhật thông tin thế giới
     * @param int $worldId - ID của thế giới cần cập nhật
     * @param string $name - Tên thế giới mới
     * @param string $article - Bài viết mô tả mới
     * @return bool - true nếu thành công, false nếu lỗi
     */
    public function update($worldId, $name, $article = '') {
        try {
            $stmt = $this->db->prepare("
                UPDATE worlds 
                SET name = :name, article = :article 
                WHERE world_id = :world_id
            ");
            
            return $stmt->execute([
                'world_id' => $worldId,
                'name' => $name,
                'article' => $article
            ]);
        } catch (PDOException $e) {
            error_log("Error in update: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Xóa một thế giới
     * @param int $worldId - ID của thế giới cần xóa
     * @return bool - true nếu thành công, false nếu lỗi
     */
    public function delete($worldId) {
        try {
            $stmt = $this->db->prepare("
                DELETE FROM worlds 
                WHERE world_id = :world_id
            ");
            
            return $stmt->execute(['world_id' => $worldId]);
        } catch (PDOException $e) {
            error_log("Error in delete: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Kiểm tra xem một thế giới có thuộc về user không
     * @param int $worldId - ID của thế giới
     * @param int $userId - ID của user
     * @return bool - true nếu user sở hữu thế giới, false nếu không
     */
    public function isOwnedByUser($worldId, $userId) {
        try {
            $stmt = $this->db->prepare("
                SELECT COUNT(*) as count 
                FROM worlds 
                WHERE world_id = :world_id AND user_id = :user_id
            ");
            $stmt->execute([
                'world_id' => $worldId,
                'user_id' => $userId
            ]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['count'] > 0;
        } catch (PDOException $e) {
            error_log("Error in isOwnedByUser: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Đếm tổng số thế giới của một user
     * @param int $userId - ID của user
     * @return int - Số lượng thế giới
     */
    public function countByUserId($userId) {
        try {
            $stmt = $this->db->prepare("
                SELECT COUNT(*) as count 
                FROM worlds 
                WHERE user_id = :user_id
            ");
            $stmt->execute(['user_id' => $userId]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return (int) $result['count'];
        } catch (PDOException $e) {
            error_log("Error in countByUserId: " . $e->getMessage());
            return 0;
        }
    }
    
    /**
     * Tìm kiếm thế giới theo tên
     * @param int $userId - ID của user
     * @param string $searchTerm - Từ khóa tìm kiếm
     * @return array - Mảng các thế giới tìm thấy
     */
    public function searchByName($userId, $searchTerm) {
        try {
            $stmt = $this->db->prepare("
                SELECT world_id, user_id, name, article, created_at 
                FROM worlds 
                WHERE user_id = :user_id 
                AND name LIKE :search_term 
                ORDER BY created_at DESC
            ");
            $stmt->execute([
                'user_id' => $userId,
                'search_term' => '%' . $searchTerm . '%'
            ]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error in searchByName: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Lấy tổng số thế giới
     */
    public function getTotalWorlds() {
        try {
            $stmt = $this->db->query("SELECT COUNT(*) as total FROM worlds");
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['total'];
        } catch (PDOException $e) {
            error_log("Error in getTotalWorlds: " . $e->getMessage());
            return 0;
        }
    }
    
    /**
     * Lấy số thế giới mới hôm nay
     */
    public function getNewWorldsToday() {
        try {
            $stmt = $this->db->query("
                SELECT COUNT(*) as total 
                FROM worlds 
                WHERE DATE(created_at) = CURDATE()
            ");
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['total'];
        } catch (PDOException $e) {
            error_log("Error in getNewWorldsToday: " . $e->getMessage());
            return 0;
        }
    }
    
    /**
     * Lấy danh sách thế giới mới nhất
     */
    public function getRecentWorlds($limit = 10) {
        try {
            $stmt = $this->db->prepare("
                SELECT w.world_id, w.name, w.created_at, u.username
                FROM worlds w
                JOIN users u ON w.user_id = u.user_id
                ORDER BY w.created_at DESC 
                LIMIT :limit
            ");
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error in getRecentWorlds: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Lấy tất cả thế giới kèm thông tin user
     */
    public function getAllWorldsWithUser() {
        try {
            $stmt = $this->db->query("
                SELECT w.world_id, w.name, w.created_at, u.username, u.user_id,
                       (SELECT COUNT(*) FROM entities WHERE entities.world_id = w.world_id) as entity_count
                FROM worlds w
                JOIN users u ON w.user_id = u.user_id
                ORDER BY w.created_at DESC
            ");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error in getAllWorldsWithUser: " . $e->getMessage());
            return [];
        }
    }
}
