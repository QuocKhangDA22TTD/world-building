<?php
/**
 * Auth Controller
 * Xử lý đăng ký, đăng nhập, đăng xuất
 */
class AuthController extends Controller {
    
    /**
     * Hiển thị trang đăng ký
     */
    public function register() {
        // Nếu đã đăng nhập, chuyển về trang chủ
        if ($this->isLoggedIn()) {
            header('Location: /world-building/public/?url=home');
            exit;
        }
        
        // Xử lý form submit
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = trim($_POST['username'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';
            $confirmPassword = $_POST['confirm_password'] ?? '';
            
            // Validate input
            $errors = [];
            
            // Validate username
            if (empty($username)) {
                $errors[] = 'Vui lòng nhập tên đăng nhập';
            } elseif (strlen($username) < 3) {
                $errors[] = 'Tên đăng nhập phải có ít nhất 3 ký tự';
            } elseif (strlen($username) > 50) {
                $errors[] = 'Tên đăng nhập không được quá 50 ký tự';
            } elseif (!preg_match('/^[a-zA-Z0-9_]+$/', $username)) {
                $errors[] = 'Tên đăng nhập chỉ được chứa chữ cái, số và dấu gạch dưới';
            }
            
            // Validate email
            if (empty($email)) {
                $errors[] = 'Vui lòng nhập email';
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = 'Email không hợp lệ';
            }
            
            // Validate password
            if (empty($password)) {
                $errors[] = 'Vui lòng nhập mật khẩu';
            } elseif (strlen($password) < 6) {
                $errors[] = 'Mật khẩu phải có ít nhất 6 ký tự';
            }
            
            // Validate confirm password
            if ($password !== $confirmPassword) {
                $errors[] = 'Mật khẩu xác nhận không khớp';
            }
            
            // Nếu không có lỗi, kiểm tra trùng lặp và tạo user
            if (empty($errors)) {
                $userModel = $this->model('User');
                
                // Kiểm tra username đã tồn tại
                if ($userModel->usernameExists($username)) {
                    $errors[] = 'Tên đăng nhập đã được sử dụng';
                }
                
                // Kiểm tra email đã tồn tại
                if ($userModel->emailExists($email)) {
                    $errors[] = 'Email đã được đăng ký';
                }
                
                // Nếu vẫn không có lỗi, tạo user
                if (empty($errors)) {
                    $userId = $userModel->createUser($username, $email, $password);
                    
                    if ($userId) {
                        $_SESSION['success'] = 'Đăng ký thành công! Vui lòng đăng nhập.';
                        header('Location: /world-building/public/?url=auth/login');
                        exit;
                    } else {
                        $errors[] = 'Có lỗi xảy ra khi tạo tài khoản. Vui lòng thử lại.';
                    }
                }
            }
            
            // Nếu có lỗi, lưu vào session và hiển thị lại form
            if (!empty($errors)) {
                $_SESSION['errors'] = $errors;
                $_SESSION['old_input'] = [
                    'username' => $username,
                    'email' => $email
                ];
            }
        }
        
        // Hiển thị form đăng ký
        $this->view('auth/register');
    }
    
    /**
     * Hiển thị trang đăng nhập
     */
    public function login() {
        // Nếu đã đăng nhập, chuyển về trang chủ
        if ($this->isLoggedIn()) {
            header('Location: /world-building/public/?url=home');
            exit;
        }
        
        // Xử lý form submit
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = trim($_POST['username'] ?? '');
            $password = $_POST['password'] ?? '';
            $remember = isset($_POST['remember']);
            
            // Validate input
            $errors = [];
            
            if (empty($username)) {
                $errors[] = 'Vui lòng nhập tên đăng nhập';
            }
            
            if (empty($password)) {
                $errors[] = 'Vui lòng nhập mật khẩu';
            }
            
            // Nếu không có lỗi, kiểm tra đăng nhập
            if (empty($errors)) {
                $userModel = $this->model('User');
                $user = $userModel->findByUsername($username);
                
                if ($user && $userModel->verifyPassword($password, $user['password'])) {
                    // Đăng nhập thành công
                    $_SESSION['user_id'] = $user['user_id'];
                    $_SESSION['username'] = $user['username'];
                    $_SESSION['email'] = $user['email'];
                    $_SESSION['role'] = $user['role'] ?? 'user'; // Lưu role vào session
                    
                    // Xử lý remember me (tùy chọn)
                    if ($remember) {
                        // Có thể implement cookie remember me ở đây
                        // setcookie('remember_token', $token, time() + 30*24*60*60, '/');
                    }
                    
                    $_SESSION['success'] = 'Đăng nhập thành công! Chào mừng ' . $user['username'];
                    
                    // Redirect về trang trước đó hoặc home
                    $redirect = $_SESSION['redirect_after_login'] ?? '/world-building/public/?url=home';
                    unset($_SESSION['redirect_after_login']);
                    header('Location: ' . $redirect);
                    exit;
                } else {
                    $errors[] = 'Tên đăng nhập hoặc mật khẩu không đúng';
                }
            }
            
            // Nếu có lỗi, lưu vào session
            if (!empty($errors)) {
                $_SESSION['errors'] = $errors;
                $_SESSION['old_input'] = ['username' => $username];
            }
        }
        
        // Hiển thị form đăng nhập
        $this->view('auth/login');
    }
    
    /**
     * Đăng xuất
     */
    public function logout() {
        // Xóa tất cả session
        $_SESSION = [];
        
        // Xóa session cookie
        if (isset($_COOKIE[session_name()])) {
            setcookie(session_name(), '', time() - 3600, '/');
        }
        
        // Hủy session
        session_destroy();
        
        // Tạo session mới để hiển thị thông báo
        session_start();
        $_SESSION['success'] = 'Đã đăng xuất thành công!';
        
        // Redirect về trang đăng nhập
        header('Location: /world-building/public/?url=auth/login');
        exit;
    }
    
    /**
     * Kiểm tra người dùng đã đăng nhập chưa
     * @return bool
     */
    private function isLoggedIn() {
        return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
    }
    
    /**
     * Middleware: Yêu cầu đăng nhập
     * Sử dụng trong các controller khác cần xác thực
     */
    public static function requireLogin() {
        if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
            $_SESSION['error'] = 'Vui lòng đăng nhập để tiếp tục';
            $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];
            header('Location: /world-building/public/?url=auth/login');
            exit;
        }
    }
}
?>
