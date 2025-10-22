<?php
/**
 * EntityController
 * Quản lý CRUD cho các thực thể (Entities)
 */
class EntityController extends Controller {
    
    /**
     * Hiển thị danh sách thực thể của một thế giới
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
                'message' => 'Vui lòng chọn một thế giới để xem danh sách thực thể'
            ];
            $this->view('entities/select_world', $data);
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
        
        // Lấy danh sách thực thể
        $entityModel = $this->model('Entity');
        $entities = $entityModel->getAllByWorld($worldId);
        
        // Truyền dữ liệu ra view
        $data = [
            'world' => $world,
            'entities' => $entities,
            'action' => 'list'
        ];
        
        $this->view('entities/list', $data);
    }
    
    /**
     * Hiển thị chi tiết một thực thể
     */
    public function read($entityId) {
        // Yêu cầu đăng nhập
        AuthController::requireLogin();
        
        $entityModel = $this->model('Entity');
        $entity = $entityModel->getById($entityId);
        
        if (!$entity) {
            $_SESSION['error'] = 'Không tìm thấy thực thể';
            header('Location: /world-building/public/?url=entity');
            exit;
        }
        
        // Kiểm tra quyền truy cập
        $worldModel = $this->model('World');
        $world = $worldModel->getById($entity['world_id']);
        
        $userRole = $_SESSION['role'] ?? 'user';
        $isStaff = in_array($userRole, ['moderator', 'admin', 'super_admin']);
        
        if (!$world || ($world['user_id'] != $_SESSION['user_id'] && !$isStaff)) {
            $_SESSION['error'] = 'Bạn không có quyền truy cập thực thể này';
            header('Location: /world-building/public/?url=world');
            exit;
        }
        
        // Lấy các mối quan hệ của thực thể
        $relationshipModel = $this->model('Relationship');
        $relationships = $relationshipModel->getByEntity($entityId);
        
        $data = [
            'world' => $world,
            'entity' => $entity,
            'relationships' => $relationships,
            'action' => 'view'
        ];
        
        $this->view('entities/list', $data);
    }
    
    /**
     * Hiển thị form tạo thực thể mới
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
                $_SESSION['error'] = 'Bạn không có quyền tạo thực thể trong thế giới này';
                header('Location: /world-building/public/?url=world');
                exit;
            }
            
            $data = ['world' => $world];
            $this->view('entities/create', $data);
            return;
        }
        
        // Xử lý POST request - tạo thực thể mới
        $worldId = isset($_POST['world_id']) ? (int)$_POST['world_id'] : 0;
        $name = trim($_POST['name'] ?? '');
        $type = trim($_POST['type'] ?? '');
        $article = trim($_POST['article'] ?? '');
        $attributes = trim($_POST['attributes'] ?? '');
        
        // Validate
        if (empty($name) || strlen($name) < 2) {
            $_SESSION['error'] = 'Tên thực thể phải có ít nhất 2 ký tự';
            header("Location: /world-building/public/?url=entity/create&world_id={$worldId}");
            exit;
        }
        
        if (empty($type)) {
            $_SESSION['error'] = 'Vui lòng chọn loại thực thể';
            header("Location: /world-building/public/?url=entity/create&world_id={$worldId}");
            exit;
        }
        
        // Kiểm tra quyền truy cập
        $worldModel = $this->model('World');
        $world = $worldModel->getById($worldId);
        
        $userRole = $_SESSION['role'] ?? 'user';
        $isAdmin = in_array($userRole, ['admin', 'super_admin']);
        
        if (!$world || ($world['user_id'] != $_SESSION['user_id'] && !$isAdmin)) {
            $_SESSION['error'] = 'Bạn không có quyền tạo thực thể trong thế giới này';
            header('Location: /world-building/public/?url=world');
            exit;
        }
        
        // Tạo thực thể
        $entityModel = $this->model('Entity');
        $entityId = $entityModel->create($worldId, $name, $type, $article, $attributes);
        
        if ($entityId) {
            $_SESSION['success'] = 'Tạo thực thể thành công';
            header("Location: /world-building/public/?url=entity&world_id={$worldId}");
        } else {
            $_SESSION['error'] = 'Có lỗi xảy ra khi tạo thực thể';
            header("Location: /world-building/public/?url=entity/create&world_id={$worldId}");
        }
        exit;
    }
    
    /**
     * Hiển thị form chỉnh sửa thực thể
     */
    public function update($entityId) {
        // Yêu cầu đăng nhập
        AuthController::requireLogin();
        
        $entityModel = $this->model('Entity');
        $entity = $entityModel->getById($entityId);
        
        if (!$entity) {
            $_SESSION['error'] = 'Không tìm thấy thực thể';
            header('Location: /world-building/public/?url=entity');
            exit;
        }
        
        // Kiểm tra quyền truy cập
        $worldModel = $this->model('World');
        $world = $worldModel->getById($entity['world_id']);
        
        $userRole = $_SESSION['role'] ?? 'user';
        $isAdmin = in_array($userRole, ['admin', 'super_admin']);
        
        if (!$world || ($world['user_id'] != $_SESSION['user_id'] && !$isAdmin)) {
            $_SESSION['error'] = 'Bạn không có quyền chỉnh sửa thực thể này';
            header('Location: /world-building/public/?url=world');
            exit;
        }
        
        // Nếu là GET request, hiển thị form
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $data = [
                'world' => $world,
                'entity' => $entity
            ];
            $this->view('entities/edit', $data);
            return;
        }
        
        // Xử lý POST request - cập nhật thực thể
        $name = trim($_POST['name'] ?? '');
        $type = trim($_POST['type'] ?? '');
        $article = trim($_POST['article'] ?? '');
        $attributes = trim($_POST['attributes'] ?? '');
        
        // Validate
        if (empty($name) || strlen($name) < 2) {
            $_SESSION['error'] = 'Tên thực thể phải có ít nhất 2 ký tự';
            header("Location: /world-building/public/?url=entity/update/{$entityId}");
            exit;
        }
        
        if (empty($type)) {
            $_SESSION['error'] = 'Vui lòng chọn loại thực thể';
            header("Location: /world-building/public/?url=entity/update/{$entityId}");
            exit;
        }
        
        // Cập nhật thực thể
        $result = $entityModel->update($entityId, $name, $type, $article, $attributes);
        
        if ($result) {
            $_SESSION['success'] = 'Cập nhật thực thể thành công';
            header("Location: /world-building/public/?url=entity&world_id={$entity['world_id']}");
        } else {
            $_SESSION['error'] = 'Có lỗi xảy ra khi cập nhật thực thể';
            header("Location: /world-building/public/?url=entity/update/{$entityId}");
        }
        exit;
    }
    
    /**
     * Xóa thực thể
     */
    public function delete($entityId) {
        // Yêu cầu đăng nhập
        AuthController::requireLogin();
        
        $entityModel = $this->model('Entity');
        $entity = $entityModel->getById($entityId);
        
        if (!$entity) {
            $_SESSION['error'] = 'Không tìm thấy thực thể';
            header('Location: /world-building/public/?url=entity');
            exit;
        }
        
        // Kiểm tra quyền truy cập
        $worldModel = $this->model('World');
        $world = $worldModel->getById($entity['world_id']);
        
        $userRole = $_SESSION['role'] ?? 'user';
        $isStaff = in_array($userRole, ['moderator', 'admin', 'super_admin']);
        
        if (!$world || ($world['user_id'] != $_SESSION['user_id'] && !$isStaff)) {
            $_SESSION['error'] = 'Bạn không có quyền xóa thực thể này';
            header('Location: /world-building/public/?url=world');
            exit;
        }
        
        // Xóa thực thể
        $result = $entityModel->delete($entityId);
        
        if ($result) {
            $_SESSION['success'] = 'Xóa thực thể thành công';
        } else {
            $_SESSION['error'] = 'Có lỗi xảy ra khi xóa thực thể';
        }
        
        header("Location: /world-building/public/?url=entity&world_id={$entity['world_id']}");
        exit;
    }
}
?>
