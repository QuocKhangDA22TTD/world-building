<?php
/**
 * WorldController
 * Xử lý tất cả các request liên quan đến quản lý thế giới (World)
 * Chức năng: CRUD (Create, Read, Update, Delete)
 */
class WorldController extends Controller {
    
    /**
     * Hiển thị danh sách tất cả thế giới của user hiện tại
     * Route: /world hoặc /world/index
     */
    public function index() {
        // Yêu cầu đăng nhập
        AuthController::requireLogin();
        
        // Lấy model World
        $worldModel = $this->model('World');
        
        // Lấy user_id từ session
        $userId = $_SESSION['user_id'];
        
        // Lấy tất cả thế giới của user
        $worlds = $worldModel->getAllByUserId($userId);
        
        // Truyền dữ liệu sang view
        $data = [
            'title' => 'Danh sách thế giới',
            'worlds' => $worlds,
            'action' => 'list'
        ];
        
        $this->view('worlds/list', $data);
    }
    
    /**
     * Xử lý tạo thế giới mới
     * GET: Hiển thị form tạo
     * POST: Xử lý dữ liệu tạo mới
     * Route: /world/create
     */
    public function create() {
        // Yêu cầu đăng nhập
        AuthController::requireLogin();
        
        // Kiểm tra method
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Lấy dữ liệu từ form
            $name = trim($_POST['name'] ?? '');
            $article = trim($_POST['article'] ?? '');
            
            // Validate
            $errors = [];
            
            if (empty($name)) {
                $errors[] = 'Tên thế giới không được để trống!';
            } elseif (strlen($name) < 3) {
                $errors[] = 'Tên thế giới phải có ít nhất 3 ký tự!';
            } elseif (strlen($name) > 255) {
                $errors[] = 'Tên thế giới không được vượt quá 255 ký tự!';
            }
            
            // Nếu không có lỗi, tạo mới
            if (empty($errors)) {
                $worldModel = $this->model('World');
                $userId = $_SESSION['user_id'];
                
                $worldId = $worldModel->create($userId, $name, $article);
                
                if ($worldId) {
                    // Thành công - redirect về danh sách với thông báo
                    $_SESSION['success_message'] = 'Tạo thế giới thành công!';
                    header('Location: /world-building/public/?url=world');
                    exit;
                } else {
                    $errors[] = 'Có lỗi xảy ra khi tạo thế giới. Vui lòng thử lại!';
                }
            }
            
            // Nếu có lỗi, hiển thị lại form với thông báo lỗi
            $_SESSION['error_messages'] = $errors;
            $_SESSION['form_data'] = ['name' => $name, 'article' => $article];
        }
        
        // Hiển thị form (GET request hoặc có lỗi)
        $data = [
            'title' => 'Tạo thế giới mới',
            'action' => 'create'
        ];
        
        $this->view('worlds/create', $data);
    }
    
    /**
     * Xử lý cập nhật thế giới
     * GET: Hiển thị form sửa với dữ liệu có sẵn
     * POST: Xử lý cập nhật
     * Route: /world/update/{id}
     */
    public function update($worldId = null) {
        // Yêu cầu đăng nhập
        AuthController::requireLogin();
        
        // Kiểm tra ID
        if (!$worldId || !is_numeric($worldId)) {
            $_SESSION['error_messages'] = ['ID thế giới không hợp lệ!'];
            header('Location: /world-building/public/?url=world');
            exit;
        }
        
        $worldModel = $this->model('World');
        $userId = $_SESSION['user_id'];
        
        // Lấy thông tin thế giới
        $world = $worldModel->getById($worldId);
        
        // Kiểm tra thế giới có tồn tại
        if (!$world) {
            $_SESSION['error_messages'] = ['Không tìm thấy thế giới!'];
            header('Location: /world-building/public/?url=world');
            exit;
        }
        
        // Kiểm tra quyền chỉnh sửa
        // Chỉ owner hoặc admin/super_admin mới được sửa (moderator chỉ xem và xóa)
        $userRole = $_SESSION['role'] ?? 'user';
        $isAdmin = in_array($userRole, ['admin', 'super_admin']);
        
        if ($world['user_id'] != $userId && !$isAdmin) {
            $_SESSION['error_messages'] = ['Bạn không có quyền chỉnh sửa thế giới này!'];
            header('Location: /world-building/public/?url=world');
            exit;
        }
        
        // Xử lý POST request
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['name'] ?? '');
            $article = trim($_POST['article'] ?? '');
            
            // Validate
            $errors = [];
            
            if (empty($name)) {
                $errors[] = 'Tên thế giới không được để trống!';
            } elseif (strlen($name) < 3) {
                $errors[] = 'Tên thế giới phải có ít nhất 3 ký tự!';
            } elseif (strlen($name) > 255) {
                $errors[] = 'Tên thế giới không được vượt quá 255 ký tự!';
            }
            
            // Nếu không có lỗi, cập nhật
            if (empty($errors)) {
                $success = $worldModel->update($worldId, $name, $article);
                
                if ($success) {
                    $_SESSION['success_message'] = 'Cập nhật thế giới thành công!';
                    header('Location: /world-building/public/?url=world');
                    exit;
                } else {
                    $errors[] = 'Có lỗi xảy ra khi cập nhật. Vui lòng thử lại!';
                }
            }
            
            // Nếu có lỗi, cập nhật dữ liệu world với giá trị mới từ form
            $_SESSION['error_messages'] = $errors;
            $world['name'] = $name;
            $world['article'] = $article;
        }
        
        // Hiển thị form edit
        $data = [
            'title' => 'Chỉnh sửa thế giới',
            'action' => 'edit',
            'world' => $world,
            'id' => $worldId
        ];
        
        $this->view('worlds/edit', $data);
    }
    
    /**
     * Xử lý xóa thế giới
     * Route: /world/delete/{id}
     */
    public function delete($worldId = null) {
        // Yêu cầu đăng nhập
        AuthController::requireLogin();
        
        // Kiểm tra ID
        if (!$worldId || !is_numeric($worldId)) {
            $_SESSION['error_messages'] = ['ID thế giới không hợp lệ!'];
            header('Location: /world-building/public/?url=world');
            exit;
        }
        
        $worldModel = $this->model('World');
        $userId = $_SESSION['user_id'];
        
        // Lấy thông tin thế giới để kiểm tra
        $world = $worldModel->getById($worldId);
        
        // Kiểm tra thế giới có tồn tại
        if (!$world) {
            $_SESSION['error_messages'] = ['Không tìm thấy thế giới!'];
            header('Location: /world-building/public/?url=world');
            exit;
        }
        
        // Kiểm tra quyền xóa
        // Owner, admin, super_admin và moderator đều có thể xóa
        $userRole = $_SESSION['role'] ?? 'user';
        $isStaff = in_array($userRole, ['moderator', 'admin', 'super_admin']);
        
        if ($world['user_id'] != $userId && !$isStaff) {
            $_SESSION['error_messages'] = ['Bạn không có quyền xóa thế giới này!'];
            header('Location: /world-building/public/?url=world');
            exit;
        }
        
        // Thực hiện xóa
        $success = $worldModel->delete($worldId);
        
        if ($success) {
            $_SESSION['success_message'] = 'Xóa thế giới thành công!';
        } else {
            $_SESSION['error_messages'] = ['Có lỗi xảy ra khi xóa thế giới!'];
        }
        
        // Redirect về danh sách
        header('Location: /world-building/public/?url=world');
        exit;
    }
    
    /**
     * Xem chi tiết một thế giới
     * Route: /world/read/{id}
     */
    public function read($worldId = null) {
        // Yêu cầu đăng nhập
        AuthController::requireLogin();
        
        // Kiểm tra ID
        if (!$worldId || !is_numeric($worldId)) {
            $_SESSION['error_messages'] = ['ID thế giới không hợp lệ!'];
            header('Location: /world-building/public/?url=world');
            exit;
        }
        
        $worldModel = $this->model('World');
        $userId = $_SESSION['user_id'];
        
        // Lấy thông tin thế giới
        $world = $worldModel->getById($worldId);
        
        // Kiểm tra thế giới có tồn tại
        if (!$world) {
            $_SESSION['error_messages'] = ['Không tìm thấy thế giới!'];
            header('Location: /world-building/public/?url=world');
            exit;
        }
        
        // Kiểm tra quyền xem
        // Owner có thể xem, hoặc admin/moderator/super_admin cũng có thể xem
        $userRole = $_SESSION['role'] ?? 'user';
        $isStaff = in_array($userRole, ['moderator', 'admin', 'super_admin']);
        
        if ($world['user_id'] != $userId && !$isStaff) {
            $_SESSION['error_messages'] = ['Bạn không có quyền xem thế giới này!'];
            header('Location: /world-building/public/?url=world');
            exit;
        }
        
        // Lưu world_id vào session để hiển thị menu Entity và Relationship
        $_SESSION['current_world_id'] = $worldId;
        
        // Hiển thị chi tiết
        $data = [
            'title' => $world['name'],
            'action' => 'view',
            'currentWorld' => $world,
            'id' => $worldId
        ];
        
        $this->view('worlds/list', $data);
    }
}
