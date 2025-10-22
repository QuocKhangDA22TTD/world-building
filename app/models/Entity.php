<?php
/**
 * Entity Model
 * Quản lý các thực thể (nhân vật, sinh vật, vũ khí, địa điểm...) trong thế giới
 */
class Entity {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    /**
     * Lấy tất cả thực thể của một thế giới
     * @param int $worldId - ID của thế giới
     * @return array - Mảng các thực thể
     */
    public function getAllByWorld($worldId) {
        try {
            $stmt = $this->db->prepare("
                SELECT entity_id, world_id, name, type, article, attributes 
                FROM entities 
                WHERE world_id = :world_id 
                ORDER BY name ASC
            ");
            $stmt->execute(['world_id' => $worldId]);
            $entities = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Giải mã JSON attributes cho tất cả entities
            foreach ($entities as &$entity) {
                if (isset($entity['attributes'])) {
                    $entity['attributes'] = json_decode($entity['attributes'], true);
                }
            }
            
            return $entities;
        } catch (PDOException $e) {
            error_log("Error in getAllByWorld: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Lấy thông tin một thực thể theo ID
     * @param int $entityId - ID của thực thể
     * @return array|false - Thông tin thực thể hoặc false nếu không tìm thấy
     */
    public function getById($entityId) {
        try {
            $stmt = $this->db->prepare("
                SELECT entity_id, world_id, name, type, article, attributes 
                FROM entities 
                WHERE entity_id = :entity_id
            ");
            $stmt->execute(['entity_id' => $entityId]);
            $entity = $stmt->fetch(PDO::FETCH_ASSOC);
            
            // Giải mã JSON attributes nếu có
            if ($entity && isset($entity['attributes'])) {
                $entity['attributes'] = json_decode($entity['attributes'], true);
            }
            
            return $entity;
        } catch (PDOException $e) {
            error_log("Error in getById: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Tạo thực thể mới
     * @param int $worldId - ID của thế giới
     * @param string $name - Tên thực thể
     * @param string $type - Loại thực thể (nhân vật, sinh vật, vũ khí...)
     * @param string $article - Bài viết mô tả chi tiết
     * @param string $attributes - Thuộc tính dạng JSON
     * @return int|false - ID của thực thể mới hoặc false nếu lỗi
     */
    public function create($worldId, $name, $type, $article = '', $attributes = null) {
        try {
            $stmt = $this->db->prepare("
                INSERT INTO entities (world_id, name, type, article, attributes) 
                VALUES (:world_id, :name, :type, :article, :attributes)
            ");
            
            // Chuyển attributes thành JSON nếu là array
            $attributesJson = is_array($attributes) ? json_encode($attributes, JSON_UNESCAPED_UNICODE) : $attributes;
            
            $stmt->execute([
                'world_id' => $worldId,
                'name' => $name,
                'type' => $type,
                'article' => $article,
                'attributes' => $attributesJson
            ]);
            
            return $this->db->lastInsertId();
        } catch (PDOException $e) {
            error_log("Error in create: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Cập nhật thông tin thực thể
     * @param int $entityId - ID của thực thể
     * @param string $name - Tên thực thể
     * @param string $type - Loại thực thể
     * @param string $article - Bài viết mô tả
     * @param string $attributes - Thuộc tính dạng JSON
     * @return bool - true nếu thành công, false nếu lỗi
     */
    public function update($entityId, $name, $type, $article = '', $attributes = null) {
        try {
            $stmt = $this->db->prepare("
                UPDATE entities 
                SET name = :name, type = :type, article = :article, attributes = :attributes
                WHERE entity_id = :entity_id
            ");
            
            // Chuyển attributes thành JSON nếu là array
            $attributesJson = is_array($attributes) ? json_encode($attributes, JSON_UNESCAPED_UNICODE) : $attributes;
            
            return $stmt->execute([
                'entity_id' => $entityId,
                'name' => $name,
                'type' => $type,
                'article' => $article,
                'attributes' => $attributesJson
            ]);
        } catch (PDOException $e) {
            error_log("Error in update: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Xóa thực thể
     * @param int $entityId - ID của thực thể
     * @return bool - true nếu thành công, false nếu lỗi
     */
    public function delete($entityId) {
        try {
            $stmt = $this->db->prepare("DELETE FROM entities WHERE entity_id = :entity_id");
            return $stmt->execute(['entity_id' => $entityId]);
        } catch (PDOException $e) {
            error_log("Error in delete: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Đếm số lượng thực thể của một thế giới
     * @param int $worldId - ID của thế giới
     * @return int - Số lượng thực thể
     */
    public function countByWorld($worldId) {
        try {
            $stmt = $this->db->prepare("
                SELECT COUNT(*) as count 
                FROM entities 
                WHERE world_id = :world_id
            ");
            $stmt->execute(['world_id' => $worldId]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return (int) $result['count'];
        } catch (PDOException $e) {
            error_log("Error in countByWorld: " . $e->getMessage());
            return 0;
        }
    }
    
    /**
     * Tìm kiếm thực thể theo tên hoặc loại
     * @param int $worldId - ID của thế giới
     * @param string $searchTerm - Từ khóa tìm kiếm
     * @return array - Mảng các thực thể tìm thấy
     */
    public function search($worldId, $searchTerm) {
        try {
            $stmt = $this->db->prepare("
                SELECT entity_id, world_id, name, type, article, attributes 
                FROM entities 
                WHERE world_id = :world_id 
                AND (name LIKE :search OR type LIKE :search)
                ORDER BY name ASC
            ");
            $search = '%' . $searchTerm . '%';
            $stmt->execute([
                'world_id' => $worldId,
                'search' => $search
            ]);
            $entities = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Giải mã JSON attributes cho tất cả entities
            foreach ($entities as &$entity) {
                if (isset($entity['attributes'])) {
                    $entity['attributes'] = json_decode($entity['attributes'], true);
                }
            }
            
            return $entities;
        } catch (PDOException $e) {
            error_log("Error in search: " . $e->getMessage());
            return [];
        }
    }
}
?>
