<?php
/**
 * AdminController
 * Quản lý chức năng dành cho quản trị viên
 */
class AdminController extends Controller {
    
    /**
     * Kiểm tra quyền admin (admin, super_admin)
     */
    private function requireAdmin() {
        AuthController::requireLogin();
        
        $role = $_SESSION['role'] ?? 'user';
        
        if (!in_array($role, ['admin', 'super_admin'])) {
            $_SESSION['error'] = 'Bạn không có quyền truy cập trang này';
            header('Location: /world-building/public/');
            exit;
        }
    }
    
    /**
     * Kiểm tra quyền moderator trở lên (moderator, admin, super_admin)
     */
    private function requireModerator() {
        AuthController::requireLogin();
        
        $role = $_SESSION['role'] ?? 'user';
        
        if (!in_array($role, ['moderator', 'admin', 'super_admin'])) {
            $_SESSION['error'] = 'Bạn không có quyền truy cập trang này';
            header('Location: /world-building/public/');
            exit;
        }
    }
    
    /**
     * Kiểm tra quyền super admin
     */
    private function requireSuperAdmin() {
        AuthController::requireLogin();
        
        $role = $_SESSION['role'] ?? 'user';
        
        if ($role !== 'super_admin') {
            $_SESSION['error'] = 'Chỉ Super Admin mới có quyền thực hiện thao tác này';
            header('Location: /world-building/public/?url=admin');
            exit;
        }
    }
    
    /**
     * Kiểm tra quyền thay đổi role của user khác
     * @param string $targetRole Role của user đang bị thay đổi
     * @return bool
     */
    private function canChangeRole($targetRole) {
        $currentRole = $_SESSION['role'] ?? 'user';
        
        // Super Admin có thể thay đổi bất kỳ ai
        if ($currentRole === 'super_admin') {
            return true;
        }
        
        // Admin chỉ có thể thay đổi user và moderator
        if ($currentRole === 'admin' && in_array($targetRole, ['user', 'moderator'])) {
            return true;
        }
        
        return false;
    }
    
    /**
     * Lấy các role mà user hiện tại có thể gán
     * @return array
     */
    private function getAllowedRoles() {
        $currentRole = $_SESSION['role'] ?? 'user';
        
        switch ($currentRole) {
            case 'super_admin':
                return ['user', 'moderator', 'admin', 'super_admin'];
            case 'admin':
                return ['user', 'moderator'];
            default:
                return [];
        }
    }
    
    /**
     * Trang tổng quan admin
     */
    public function index() {
        $this->requireAdmin();
        
        $userModel = $this->model('User');
        $worldModel = $this->model('World');
        
        // Lấy thống kê tổng quan
        $stats = [
            'total_users' => $userModel->getTotalUsers(),
            'total_worlds' => $worldModel->getTotalWorlds(),
            'total_entities' => $this->getTotalEntities(),
            'total_relationships' => $this->getTotalRelationships(),
            'new_users_today' => $userModel->getNewUsersToday(),
            'new_worlds_today' => $worldModel->getNewWorldsToday()
        ];
        
        // Lấy danh sách user mới nhất
        $recentUsers = $userModel->getRecentUsers(5);
        
        // Lấy danh sách world mới nhất
        $recentWorlds = $worldModel->getRecentWorlds(5);
        
        $data = [
            'stats' => $stats,
            'recentUsers' => $recentUsers,
            'recentWorlds' => $recentWorlds
        ];
        
        $this->view('admin/dashboard', $data);
    }
    
    /**
     * Quản lý người dùng
     */
    public function users() {
        $this->requireAdmin(); // Chỉ admin và super_admin
        
        $userModel = $this->model('User');
        
        // Tìm kiếm
        $search = isset($_GET['search']) ? trim($_GET['search']) : '';
        
        if ($search) {
            $users = $userModel->searchUsers($search);
        } else {
            $users = $userModel->getAllUsers();
        }
        
        $data = [
            'users' => $users,
            'search' => $search,
            'allowedRoles' => $this->getAllowedRoles(),
            'currentRole' => $_SESSION['role'] ?? 'user'
        ];
        
        $this->view('admin/users', $data);
    }
    
    /**
     * Xóa người dùng
     */
    public function deleteUser($userId) {
        $this->requireAdmin(); // Chỉ admin và super_admin
        
        if (!$userId) {
            $_SESSION['error'] = 'ID người dùng không hợp lệ';
            header('Location: /world-building/public/?url=admin/users');
            exit;
        }
        
        // Không cho phép xóa chính mình
        if ($userId == $_SESSION['user_id']) {
            $_SESSION['error'] = 'Bạn không thể xóa chính mình';
            header('Location: /world-building/public/?url=admin/users');
            exit;
        }
        
        $userModel = $this->model('User');
        $targetUser = $userModel->getById($userId);
        
        // Kiểm tra quyền xóa
        if (!$this->canChangeRole($targetUser['role'])) {
            $_SESSION['error'] = 'Bạn không có quyền xóa người dùng này';
            header('Location: /world-building/public/?url=admin/users');
            exit;
        }
        
        $result = $userModel->deleteUser($userId);
        
        if ($result) {
            $_SESSION['success'] = 'Đã xóa người dùng thành công';
        } else {
            $_SESSION['error'] = 'Có lỗi xảy ra khi xóa người dùng';
        }
        
        header('Location: /world-building/public/?url=admin/users');
        exit;
    }
    
    /**
     * Thay đổi role người dùng
     */
    public function changeRole($userId) {
        $this->requireAdmin(); // Chỉ admin và super_admin
        
        if (!$userId) {
            $_SESSION['error'] = 'ID người dùng không hợp lệ';
            header('Location: /world-building/public/?url=admin/users');
            exit;
        }
        
        $newRole = isset($_POST['role']) ? $_POST['role'] : '';
        $allowedRoles = $this->getAllowedRoles();
        
        if (!in_array($newRole, $allowedRoles)) {
            $_SESSION['error'] = 'Bạn không có quyền gán role này';
            header('Location: /world-building/public/?url=admin/users');
            exit;
        }
        
        // Không cho phép thay đổi role của chính mình
        if ($userId == $_SESSION['user_id']) {
            $_SESSION['error'] = 'Bạn không thể thay đổi role của chính mình';
            header('Location: /world-building/public/?url=admin/users');
            exit;
        }
        
        $userModel = $this->model('User');
        $targetUser = $userModel->getById($userId);
        
        // Kiểm tra quyền thay đổi role của user đó
        if (!$this->canChangeRole($targetUser['role'])) {
            $_SESSION['error'] = 'Bạn không có quyền thay đổi role của người dùng này';
            header('Location: /world-building/public/?url=admin/users');
            exit;
        }
        
        $result = $userModel->changeRole($userId, $newRole);
        
        if ($result) {
            $_SESSION['success'] = 'Đã thay đổi quyền thành công';
        } else {
            $_SESSION['error'] = 'Có lỗi xảy ra khi thay đổi quyền';
        }
        
        header('Location: /world-building/public/?url=admin/users');
        exit;
    }
    
    /**
     * Quản lý thế giới
     */
    public function worlds() {
        $this->requireModerator(); // Moderator, Admin, Super Admin
        
        $worldModel = $this->model('World');
        $worlds = $worldModel->getAllWorldsWithUser();
        
        $data = [
            'worlds' => $worlds,
            'currentRole' => $_SESSION['role'] ?? 'user'
        ];
        $this->view('admin/worlds', $data);
    }
    
    /**
     * Xóa thế giới
     */
    public function deleteWorld($worldId) {
        $this->requireModerator(); // Moderator, Admin, Super Admin
        
        if (!$worldId) {
            $_SESSION['error'] = 'ID thế giới không hợp lệ';
            header('Location: /world-building/public/?url=admin/worlds');
            exit;
        }
        
        $worldModel = $this->model('World');
        $result = $worldModel->delete($worldId);
        
        if ($result) {
            $_SESSION['success'] = 'Đã xóa thế giới thành công';
        } else {
            $_SESSION['error'] = 'Có lỗi xảy ra khi xóa thế giới';
        }
        
        header('Location: /world-building/public/?url=admin/worlds');
        exit;
    }
    
    /**
     * Helper methods
     */
    private function getTotalEntities() {
        $db = Database::getInstance();
        $stmt = $db->query("SELECT COUNT(*) as total FROM entities");
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'];
    }
    
    private function getTotalRelationships() {
        $db = Database::getInstance();
        $stmt = $db->query("SELECT COUNT(*) as total FROM relationships");
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'];
    }
}
?>
