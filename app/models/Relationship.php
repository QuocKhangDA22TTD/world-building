<?php
/**
 * Relationship Model
 * Quản lý các mối quan hệ giữa các thực thể trong thế giới
 */
class Relationship {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    /**
     * Lấy tất cả mối quan hệ của một thế giới
     * @param int $worldId - ID của thế giới
     * @return array - Mảng các mối quan hệ với thông tin thực thể
     */
    public function getByWorld($worldId) {
        try {
            $stmt = $this->db->prepare("
                SELECT 
                    r.relationship_id,
                    r.entity1_id,
                    r.entity2_id,
                    r.type,
                    r.description,
                    e1.name as entity1_name,
                    e1.type as entity1_type,
                    e2.name as entity2_name,
                    e2.type as entity2_type
                FROM relationships r
                INNER JOIN entities e1 ON r.entity1_id = e1.entity_id
                INNER JOIN entities e2 ON r.entity2_id = e2.entity_id
                WHERE e1.world_id = :world_id
                ORDER BY r.relationship_id DESC
            ");
            $stmt->execute(['world_id' => $worldId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error in getByWorld: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Lấy thông tin một mối quan hệ theo ID
     * @param int $relationshipId - ID của mối quan hệ
     * @return array|false - Thông tin mối quan hệ hoặc false nếu không tìm thấy
     */
    public function getById($relationshipId) {
        try {
            $stmt = $this->db->prepare("
                SELECT 
                    r.relationship_id,
                    r.entity1_id,
                    r.entity2_id,
                    r.type,
                    r.description,
                    e1.name as entity1_name,
                    e1.type as entity1_type,
                    e1.world_id,
                    e2.name as entity2_name,
                    e2.type as entity2_type
                FROM relationships r
                INNER JOIN entities e1 ON r.entity1_id = e1.entity_id
                INNER JOIN entities e2 ON r.entity2_id = e2.entity_id
                WHERE r.relationship_id = :relationship_id
            ");
            $stmt->execute(['relationship_id' => $relationshipId]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error in getById: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Tạo mối quan hệ mới
     * @param int $entity1Id - ID thực thể 1
     * @param int $entity2Id - ID thực thể 2
     * @param string $type - Loại quan hệ
     * @param string $description - Mô tả chi tiết
     * @return int|false - ID của mối quan hệ mới hoặc false nếu lỗi
     */
    public function create($entity1Id, $entity2Id, $type, $description = '') {
        try {
            $stmt = $this->db->prepare("
                INSERT INTO relationships (entity1_id, entity2_id, type, description) 
                VALUES (:entity1_id, :entity2_id, :type, :description)
            ");
            
            $stmt->execute([
                'entity1_id' => $entity1Id,
                'entity2_id' => $entity2Id,
                'type' => $type,
                'description' => $description
            ]);
            
            return $this->db->lastInsertId();
        } catch (PDOException $e) {
            error_log("Error in create: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Cập nhật mối quan hệ
     * @param int $relationshipId - ID của mối quan hệ
     * @param int $entity1Id - ID thực thể 1
     * @param int $entity2Id - ID thực thể 2
     * @param string $type - Loại quan hệ
     * @param string $description - Mô tả chi tiết
     * @return bool - true nếu thành công, false nếu lỗi
     */
    public function update($relationshipId, $entity1Id, $entity2Id, $type, $description = '') {
        try {
            $stmt = $this->db->prepare("
                UPDATE relationships 
                SET entity1_id = :entity1_id, 
                    entity2_id = :entity2_id, 
                    type = :type, 
                    description = :description
                WHERE relationship_id = :relationship_id
            ");
            
            return $stmt->execute([
                'relationship_id' => $relationshipId,
                'entity1_id' => $entity1Id,
                'entity2_id' => $entity2Id,
                'type' => $type,
                'description' => $description
            ]);
        } catch (PDOException $e) {
            error_log("Error in update: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Xóa mối quan hệ
     * @param int $relationshipId - ID của mối quan hệ
     * @return bool - true nếu thành công, false nếu lỗi
     */
    public function delete($relationshipId) {
        try {
            $stmt = $this->db->prepare("DELETE FROM relationships WHERE relationship_id = :relationship_id");
            return $stmt->execute(['relationship_id' => $relationshipId]);
        } catch (PDOException $e) {
            error_log("Error in delete: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Lấy tất cả mối quan hệ của một thực thể
     * @param int $entityId - ID của thực thể
     * @return array - Mảng các mối quan hệ
     */
    public function getByEntity($entityId) {
        try {
            $stmt = $this->db->prepare("
                SELECT 
                    r.relationship_id,
                    r.entity1_id,
                    r.entity2_id,
                    r.type,
                    r.description,
                    e1.name as entity1_name,
                    e2.name as entity2_name
                FROM relationships r
                INNER JOIN entities e1 ON r.entity1_id = e1.entity_id
                INNER JOIN entities e2 ON r.entity2_id = e2.entity_id
                WHERE r.entity1_id = :entity_id OR r.entity2_id = :entity_id
                ORDER BY r.relationship_id DESC
            ");
            $stmt->execute(['entity_id' => $entityId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error in getByEntity: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Đếm số lượng mối quan hệ của một thế giới
     * @param int $worldId - ID của thế giới
     * @return int - Số lượng mối quan hệ
     */
    public function countByWorld($worldId) {
        try {
            $stmt = $this->db->prepare("
                SELECT COUNT(*) as count 
                FROM relationships r
                INNER JOIN entities e1 ON r.entity1_id = e1.entity_id
                WHERE e1.world_id = :world_id
            ");
            $stmt->execute(['world_id' => $worldId]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return (int) $result['count'];
        } catch (PDOException $e) {
            error_log("Error in countByWorld: " . $e->getMessage());
            return 0;
        }
    }
}
?>
