<?php
$user = $data['user'];
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chỉnh sửa thông tin - World Building</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="/world-building/public/css/style.css">
    <style>
        .edit-container {
            max-width: 600px;
            margin: 50px auto;
        }
        
        .edit-card {
            background: white;
            border-radius: 15px;
            padding: 40px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        .form-group label {
            font-weight: 600;
            color: #495057;
        }
        
        .form-control {
            border-radius: 10px;
            border: 2px solid #e9ecef;
            padding: 12px 20px;
            transition: all 0.3s;
        }
        
        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }
        
        .btn-save {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            color: white;
            padding: 12px 40px;
            border-radius: 25px;
            font-weight: 600;
            transition: all 0.3s;
            width: 100%;
        }
        
        .btn-save:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
            color: white;
        }
        
        .btn-cancel {
            background: #6c757d;
            border: none;
            color: white;
            padding: 12px 40px;
            border-radius: 25px;
            font-weight: 600;
            transition: all 0.3s;
            width: 100%;
        }
        
        .btn-cancel:hover {
            background: #5a6268;
            color: white;
        }
        
        .password-toggle {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #6c757d;
        }
        
        .form-group {
            position: relative;
        }
        
        .section-divider {
            border-top: 2px solid #e9ecef;
            margin: 30px 0;
            padding-top: 20px;
        }
        
        .section-title {
            color: #667eea;
            font-weight: 600;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <?php require_once '../app/views/layout/header.php'; ?>
    <?php require_once '../app/views/layout/navigation.php'; ?>

    <div class="edit-container">
        <div class="edit-card">
            <h2 class="text-center mb-4">
                <i class="fas fa-user-edit"></i> Chỉnh sửa thông tin
            </h2>
            
            <!-- Thông báo lỗi -->
            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle"></i> 
                    <?= $_SESSION['error'] ?>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>

            <form method="POST" action="/world-building/public/?url=user/edit">
                <!-- Thông tin cơ bản -->
                <div class="form-group">
                    <label>
                        <i class="fas fa-user"></i> Tên đăng nhập
                    </label>
                    <input type="text" class="form-control" value="<?= htmlspecialchars($user['username']) ?>" disabled>
                    <small class="form-text text-muted">Tên đăng nhập không thể thay đổi</small>
                </div>

                <div class="form-group">
                    <label for="email">
                        <i class="fas fa-envelope"></i> Email <span class="text-danger">*</span>
                    </label>
                    <input type="email" class="form-control" id="email" name="email" 
                           value="<?= htmlspecialchars($user['email']) ?>" required>
                </div>

                <!-- Đổi mật khẩu -->
                <div class="section-divider">
                    <h5 class="section-title">
                        <i class="fas fa-key"></i> Đổi mật khẩu
                    </h5>
                    <small class="text-muted">Để trống nếu không muốn đổi mật khẩu</small>
                </div>

                <div class="form-group">
                    <label for="current_password">
                        <i class="fas fa-lock"></i> Mật khẩu hiện tại
                    </label>
                    <input type="password" class="form-control" id="current_password" name="current_password">
                    <i class="fas fa-eye password-toggle" onclick="togglePassword('current_password')"></i>
                </div>

                <div class="form-group">
                    <label for="new_password">
                        <i class="fas fa-lock"></i> Mật khẩu mới
                    </label>
                    <input type="password" class="form-control" id="new_password" name="new_password">
                    <i class="fas fa-eye password-toggle" onclick="togglePassword('new_password')"></i>
                    <small class="form-text text-muted">Tối thiểu 6 ký tự</small>
                </div>

                <div class="form-group">
                    <label for="confirm_password">
                        <i class="fas fa-lock"></i> Xác nhận mật khẩu mới
                    </label>
                    <input type="password" class="form-control" id="confirm_password" name="confirm_password">
                    <i class="fas fa-eye password-toggle" onclick="togglePassword('confirm_password')"></i>
                </div>

                <div class="row mt-4">
                    <div class="col-md-6 mb-3">
                        <a href="/world-building/public/?url=user/profile" class="btn btn-cancel">
                            <i class="fas fa-times"></i> Hủy
                        </a>
                    </div>
                    <div class="col-md-6">
                        <button type="submit" class="btn btn-save">
                            <i class="fas fa-save"></i> Lưu thay đổi
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <?php require_once '../app/views/layout/footer.php'; ?>

    <script>
        function togglePassword(fieldId) {
            const field = document.getElementById(fieldId);
            const icon = field.nextElementSibling;
            
            if (field.type === 'password') {
                field.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                field.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }
    </script>
</body>
</html>
