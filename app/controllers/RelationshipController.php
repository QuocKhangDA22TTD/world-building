<?php
/**
 * RelationshipController
 * Quản lý CRUD cho các mối quan hệ giữa thực thể
 */
class RelationshipController extends Controller {
    
    /**
     * Hiển thị danh sách mối quan hệ của một thế giới
     */
    public function index() {
        // Yêu cầu đăng nhập
        AuthController::requireLogin();
        
        // Lấy world_id từ URL hoặc session
        $worldId = isset($_GET['world_id']) ? (int)$_GET['world_id'] : (isset($_SESSION['current_world_id']) ? $_SESSION['current_world_id'] : 0);
        
        if (!$worldId) {
            // Nếu chưa chọn thế giới, hiển thị danh sách thế giới để chọn
            $worldModel = $this->model('World');
            $userId = $_SESSION['user_id'];
            $worlds = $worldModel->getAllByUserId($userId);
            
            $data = [
                'worlds' => $worlds,
                'message' => 'Vui lòng chọn một thế giới để xem danh sách mối quan hệ'
            ];
            $this->view('relationships/select_world', $data);
            return;
        }
        
        // Kiểm tra quyền truy cập thế giới
        $worldModel = $this->model('World');
        $world = $worldModel->getById($worldId);
        
        $userRole = $_SESSION['role'] ?? 'user';
        $isStaff = in_array($userRole, ['moderator', 'admin', 'super_admin']);
        
        if (!$world || ($world['user_id'] != $_SESSION['user_id'] && !$isStaff)) {
            $_SESSION['error'] = 'Bạn không có quyền truy cập thế giới này';
            header('Location: /world-building/public/?url=world');
            exit;
        }
        
        // Lưu world_id vào session
        $_SESSION['current_world_id'] = $worldId;
        
        // Lấy danh sách mối quan hệ
        $relationshipModel = $this->model('Relationship');
        $relationships = $relationshipModel->getByWorld($worldId);
        
        // Truyền dữ liệu ra view
        $data = [
            'world' => $world,
            'relationships' => $relationships,
            'action' => 'list'
        ];
        
        $this->view('relationships/list', $data);
    }
    
    /**
     * Hiển thị chi tiết một mối quan hệ
     */
    public function read($relationshipId) {
        // Yêu cầu đăng nhập
        AuthController::requireLogin();
        
        $relationshipModel = $this->model('Relationship');
        $relationship = $relationshipModel->getById($relationshipId);
        
        if (!$relationship) {
            $_SESSION['error'] = 'Không tìm thấy mối quan hệ';
            header('Location: /world-building/public/?url=relationship');
            exit;
        }
        
        // Kiểm tra quyền truy cập
        $worldModel = $this->model('World');
        $world = $worldModel->getById($relationship['world_id']);
        
        $userRole = $_SESSION['role'] ?? 'user';
        $isStaff = in_array($userRole, ['moderator', 'admin', 'super_admin']);
        
        if (!$world || ($world['user_id'] != $_SESSION['user_id'] && !$isStaff)) {
            $_SESSION['error'] = 'Bạn không có quyền truy cập mối quan hệ này';
            header('Location: /world-building/public/?url=world');
            exit;
        }
        
        $data = [
            'world' => $world,
            'relationship' => $relationship,
            'action' => 'view'
        ];
        
        $this->view('relationships/list', $data);
    }
    
    /**
     * Hiển thị form tạo mối quan hệ mới
     */
    public function create() {
        // Yêu cầu đăng nhập
        AuthController::requireLogin();
        
        // Nếu là GET request, hiển thị form
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $worldId = isset($_GET['world_id']) ? (int)$_GET['world_id'] : (isset($_SESSION['current_world_id']) ? $_SESSION['current_world_id'] : 0);
            
            if (!$worldId) {
                $_SESSION['error'] = 'Vui lòng chọn một thế giới trước';
                header('Location: /world-building/public/?url=world');
                exit;
            }
            
            // Kiểm tra quyền truy cập
            $worldModel = $this->model('World');
            $world = $worldModel->getById($worldId);
            
            $userRole = $_SESSION['role'] ?? 'user';
            $isAdmin = in_array($userRole, ['admin', 'super_admin']);
            
            if (!$world || ($world['user_id'] != $_SESSION['user_id'] && !$isAdmin)) {
                $_SESSION['error'] = 'Bạn không có quyền tạo mối quan hệ trong thế giới này';
                header('Location: /world-building/public/?url=world');
                exit;
            }
            
            // Lấy danh sách thực thể để chọn
            $entityModel = $this->model('Entity');
            $entities = $entityModel->getAllByWorld($worldId);
            
            $data = [
                'world' => $world,
                'entities' => $entities
            ];
            $this->view('relationships/create', $data);
            return;
        }
        
        // Xử lý POST request - tạo mối quan hệ mới
        $worldId = isset($_POST['world_id']) ? (int)$_POST['world_id'] : 0;
        $entity1Id = isset($_POST['entity1_id']) ? (int)$_POST['entity1_id'] : 0;
        $entity2Id = isset($_POST['entity2_id']) ? (int)$_POST['entity2_id'] : 0;
        $type = trim($_POST['type'] ?? '');
        $description = trim($_POST['description'] ?? '');
        
        // Validate
        if (!$entity1Id || !$entity2Id) {
            $_SESSION['error'] = 'Vui lòng chọn đầy đủ hai thực thể';
            header("Location: /world-building/public/?url=relationship/create&world_id={$worldId}");
            exit;
        }
        
        if ($entity1Id === $entity2Id) {
            $_SESSION['error'] = 'Không thể tạo mối quan hệ giữa một thực thể với chính nó';
            header("Location: /world-building/public/?url=relationship/create&world_id={$worldId}");
            exit;
        }
        
        if (empty($type)) {
            $_SESSION['error'] = 'Vui lòng nhập loại quan hệ';
            header("Location: /world-building/public/?url=relationship/create&world_id={$worldId}");
            exit;
        }
        
        // Kiểm tra quyền truy cập
        $worldModel = $this->model('World');
        $world = $worldModel->getById($worldId);
        
        $userRole = $_SESSION['role'] ?? 'user';
        $isAdmin = in_array($userRole, ['admin', 'super_admin']);
        
        if (!$world || ($world['user_id'] != $_SESSION['user_id'] && !$isAdmin)) {
            $_SESSION['error'] = 'Bạn không có quyền tạo mối quan hệ trong thế giới này';
            header('Location: /world-building/public/?url=world');
            exit;
        }
        
        // Kiểm tra các thực thể thuộc về thế giới này
        $entityModel = $this->model('Entity');
        $entity1 = $entityModel->getById($entity1Id);
        $entity2 = $entityModel->getById($entity2Id);
        
        if (!$entity1 || !$entity2 || $entity1['world_id'] != $worldId || $entity2['world_id'] != $worldId) {
            $_SESSION['error'] = 'Các thực thể không hợp lệ';
            header("Location: /world-building/public/?url=relationship/create&world_id={$worldId}");
            exit;
        }
        
        // Tạo mối quan hệ
        $relationshipModel = $this->model('Relationship');
        $relationshipId = $relationshipModel->create($entity1Id, $entity2Id, $type, $description);
        
        if ($relationshipId) {
            $_SESSION['success'] = 'Tạo mối quan hệ thành công';
            header("Location: /world-building/public/?url=relationship&world_id={$worldId}");
        } else {
            $_SESSION['error'] = 'Có lỗi xảy ra khi tạo mối quan hệ';
            header("Location: /world-building/public/?url=relationship/create&world_id={$worldId}");
        }
        exit;
    }
    
    /**
     * Hiển thị form chỉnh sửa mối quan hệ
     */
    public function update($relationshipId) {
        // Yêu cầu đăng nhập
        AuthController::requireLogin();
        
        $relationshipModel = $this->model('Relationship');
        $relationship = $relationshipModel->getById($relationshipId);
        
        if (!$relationship) {
            $_SESSION['error'] = 'Không tìm thấy mối quan hệ';
            header('Location: /world-building/public/?url=relationship');
            exit;
        }
        
        // Kiểm tra quyền truy cập
        $worldModel = $this->model('World');
        $world = $worldModel->getById($relationship['world_id']);
        
        $userRole = $_SESSION['role'] ?? 'user';
        $isAdmin = in_array($userRole, ['admin', 'super_admin']);
        
        if (!$world || ($world['user_id'] != $_SESSION['user_id'] && !$isAdmin)) {
            $_SESSION['error'] = 'Bạn không có quyền chỉnh sửa mối quan hệ này';
            header('Location: /world-building/public/?url=world');
            exit;
        }
        
        // Nếu là GET request, hiển thị form
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            // Lấy danh sách thực thể để chọn
            $entityModel = $this->model('Entity');
            $entities = $entityModel->getAllByWorld($relationship['world_id']);
            
            $data = [
                'world' => $world,
                'relationship' => $relationship,
                'entities' => $entities
            ];
            $this->view('relationships/edit', $data);
            return;
        }
        
        // Xử lý POST request - cập nhật mối quan hệ
        $entity1Id = isset($_POST['entity1_id']) ? (int)$_POST['entity1_id'] : 0;
        $entity2Id = isset($_POST['entity2_id']) ? (int)$_POST['entity2_id'] : 0;
        $type = trim($_POST['type'] ?? '');
        $description = trim($_POST['description'] ?? '');
        
        // Validate
        if (!$entity1Id || !$entity2Id) {
            $_SESSION['error'] = 'Vui lòng chọn đầy đủ hai thực thể';
            header("Location: /world-building/public/?url=relationship/update/{$relationshipId}");
            exit;
        }
        
        if ($entity1Id === $entity2Id) {
            $_SESSION['error'] = 'Không thể tạo mối quan hệ giữa một thực thể với chính nó';
            header("Location: /world-building/public/?url=relationship/update/{$relationshipId}");
            exit;
        }
        
        if (empty($type)) {
            $_SESSION['error'] = 'Vui lòng nhập loại quan hệ';
            header("Location: /world-building/public/?url=relationship/update/{$relationshipId}");
            exit;
        }
        
        // Kiểm tra các thực thể thuộc về thế giới này
        $entityModel = $this->model('Entity');
        $entity1 = $entityModel->getById($entity1Id);
        $entity2 = $entityModel->getById($entity2Id);
        
        if (!$entity1 || !$entity2 || $entity1['world_id'] != $relationship['world_id'] || $entity2['world_id'] != $relationship['world_id']) {
            $_SESSION['error'] = 'Các thực thể không hợp lệ';
            header("Location: /world-building/public/?url=relationship/update/{$relationshipId}");
            exit;
        }
        
        // Cập nhật mối quan hệ
        $result = $relationshipModel->update($relationshipId, $entity1Id, $entity2Id, $type, $description);
        
        if ($result) {
            $_SESSION['success'] = 'Cập nhật mối quan hệ thành công';
            header("Location: /world-building/public/?url=relationship&world_id={$relationship['world_id']}");
        } else {
            $_SESSION['error'] = 'Có lỗi xảy ra khi cập nhật mối quan hệ';
            header("Location: /world-building/public/?url=relationship/update/{$relationshipId}");
        }
        exit;
    }
    
    /**
     * Xóa mối quan hệ
     */
    public function delete($relationshipId) {
        // Yêu cầu đăng nhập
        AuthController::requireLogin();
        
        $relationshipModel = $this->model('Relationship');
        $relationship = $relationshipModel->getById($relationshipId);
        
        if (!$relationship) {
            $_SESSION['error'] = 'Không tìm thấy mối quan hệ';
            header('Location: /world-building/public/?url=relationship');
            exit;
        }
        
        // Kiểm tra quyền truy cập
        $worldModel = $this->model('World');
        $world = $worldModel->getById($relationship['world_id']);
        
        $userRole = $_SESSION['role'] ?? 'user';
        $isStaff = in_array($userRole, ['moderator', 'admin', 'super_admin']);
        
        if (!$world || ($world['user_id'] != $_SESSION['user_id'] && !$isStaff)) {
            $_SESSION['error'] = 'Bạn không có quyền xóa mối quan hệ này';
            header('Location: /world-building/public/?url=world');
            exit;
        }
        
        // Xóa mối quan hệ
        $result = $relationshipModel->delete($relationshipId);
        
        if ($result) {
            $_SESSION['success'] = 'Xóa mối quan hệ thành công';
        } else {
            $_SESSION['error'] = 'Có lỗi xảy ra khi xóa mối quan hệ';
        }
        
        header("Location: /world-building/public/?url=relationship&world_id={$relationship['world_id']}");
        exit;
    }
}
?>

