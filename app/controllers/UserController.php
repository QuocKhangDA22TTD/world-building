<?php
/**
 * UserController
 * Quản lý thông tin người dùng
 */
class UserController extends Controller {
    
    /**
     * Hiển thị trang thông tin cá nhân
     */
    public function profile() {
        // Yêu cầu đăng nhập
        AuthController::requireLogin();
        
        // Lấy thông tin user từ database
        $userModel = $this->model('User');
        $user = $userModel->getById($_SESSION['user_id']);
        
        if (!$user) {
            $_SESSION['error'] = 'Không tìm thấy thông tin người dùng';
            header('Location: /world-building/public/');
            exit;
        }
        
        // Lấy thống kê
        $worldModel = $this->model('World');
        $worlds = $worldModel->getAllByUserId($_SESSION['user_id']);
        $totalWorlds = count($worlds);
        
        $totalEntities = 0;
        $totalRelationships = 0;
        
        if ($totalWorlds > 0) {
            $entityModel = $this->model('Entity');
            $relationshipModel = $this->model('Relationship');
            
            foreach ($worlds as $world) {
                $entities = $entityModel->getAllByWorld($world['world_id']);
                $relationships = $relationshipModel->getByWorld($world['world_id']);
                
                $totalEntities += count($entities);
                $totalRelationships += count($relationships);
            }
        }
        
        $data = [
            'user' => $user,
            'stats' => [
                'worlds' => $totalWorlds,
                'entities' => $totalEntities,
                'relationships' => $totalRelationships
            ]
        ];
        
        $this->view('user/profile', $data);
    }
    
    /**
     * Hiển thị form chỉnh sửa thông tin cá nhân
     */
    public function edit() {
        // Yêu cầu đăng nhập
        AuthController::requireLogin();
        
        $userModel = $this->model('User');
        
        // Nếu là GET request, hiển thị form
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $user = $userModel->getById($_SESSION['user_id']);
            
            if (!$user) {
                $_SESSION['error'] = 'Không tìm thấy thông tin người dùng';
                header('Location: /world-building/public/');
                exit;
            }
            
            $data = ['user' => $user];
            $this->view('user/edit', $data);
            return;
        }
        
        // Xử lý POST request - cập nhật thông tin
        $email = trim($_POST['email'] ?? '');
        $currentPassword = trim($_POST['current_password'] ?? '');
        $newPassword = trim($_POST['new_password'] ?? '');
        $confirmPassword = trim($_POST['confirm_password'] ?? '');
        
        $errors = [];
        
        // Validate email
        if (empty($email)) {
            $errors[] = 'Email không được để trống';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Email không hợp lệ';
        } else {
            // Kiểm tra email đã tồn tại chưa (trừ email của chính user này)
            $existingUser = $userModel->findByEmail($email);
            if ($existingUser && $existingUser['user_id'] != $_SESSION['user_id']) {
                $errors[] = 'Email này đã được sử dụng bởi tài khoản khác';
            }
        }
        
        // Nếu muốn đổi mật khẩu
        if (!empty($newPassword) || !empty($confirmPassword)) {
            if (empty($currentPassword)) {
                $errors[] = 'Vui lòng nhập mật khẩu hiện tại';
            } else {
                // Xác thực mật khẩu hiện tại
                $user = $userModel->getById($_SESSION['user_id']);
                if (!$userModel->verifyPassword($currentPassword, $user['password'])) {
                    $errors[] = 'Mật khẩu hiện tại không đúng';
                }
            }
            
            if (strlen($newPassword) < 6) {
                $errors[] = 'Mật khẩu mới phải có ít nhất 6 ký tự';
            }
            
            if ($newPassword !== $confirmPassword) {
                $errors[] = 'Mật khẩu mới và xác nhận mật khẩu không khớp';
            }
        }
        
        if (!empty($errors)) {
            $_SESSION['error'] = implode('<br>', $errors);
            header('Location: /world-building/public/?url=user/edit');
            exit;
        }
        
        // Cập nhật thông tin
        $result = $userModel->updateProfile($_SESSION['user_id'], $email, $newPassword);
        
        if ($result) {
            $_SESSION['email'] = $email;
            $_SESSION['success'] = 'Cập nhật thông tin thành công';
            header('Location: /world-building/public/?url=user/profile');
        } else {
            $_SESSION['error'] = 'Có lỗi xảy ra khi cập nhật thông tin';
            header('Location: /world-building/public/?url=user/edit');
        }
        exit;
    }
}
?>
