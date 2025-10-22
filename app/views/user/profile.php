<?php
$user = $data['user'];
$stats = $data['stats'];
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thông tin cá nhân - World Building</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="/world-building/public/css/style.css">
    <style>
        .profile-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 60px 0;
            margin-bottom: 30px;
        }
        
        .profile-avatar {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            background: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 80px;
            color: #667eea;
            margin: 0 auto 20px;
            border: 5px solid rgba(255,255,255,0.3);
        }
        
        .stats-card {
            background: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            margin-bottom: 30px;
            transition: transform 0.3s;
        }
        
        .stats-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        }
        
        .stat-item {
            text-align: center;
            padding: 20px;
        }
        
        .stat-number {
            font-size: 48px;
            font-weight: bold;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .stat-label {
            color: #6c757d;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-top: 10px;
        }
        
        .info-card {
            background: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        .info-row {
            padding: 15px 0;
            border-bottom: 1px solid #e9ecef;
        }
        
        .info-row:last-child {
            border-bottom: none;
        }
        
        .info-label {
            font-weight: 600;
            color: #495057;
            margin-bottom: 5px;
        }
        
        .info-value {
            color: #6c757d;
            font-size: 18px;
        }
        
        .btn-edit {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            color: white;
            padding: 12px 40px;
            border-radius: 25px;
            font-weight: 600;
            transition: all 0.3s;
        }
        
        .btn-edit:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
            color: white;
        }
    </style>
</head>
<body>
    <?php require_once '../app/views/layout/header.php'; ?>
    <?php require_once '../app/views/layout/navigation.php'; ?>

    <!-- Profile Header -->
    <div class="profile-header">
        <div class="container">
            <div class="profile-avatar">
                <i class="fas fa-user"></i>
            </div>
            <h1 class="text-center mb-2">
                <?= htmlspecialchars($user['username']) ?>
                <?php 
                $userRole = $user['role'] ?? 'user';
                if ($userRole === 'super_admin'): ?>
                    <span class="badge badge-danger ml-2"><i class="fas fa-crown"></i> Super Admin</span>
                <?php elseif ($userRole === 'admin'): ?>
                    <span class="badge badge-warning ml-2"><i class="fas fa-shield-alt"></i> Admin</span>
                <?php elseif ($userRole === 'moderator'): ?>
                    <span class="badge badge-info ml-2"><i class="fas fa-user-shield"></i> Moderator</span>
                <?php endif; ?>
            </h1>
            <p class="text-center mb-0"><?= htmlspecialchars($user['email']) ?></p>
            <div class="text-center mt-3">
                <small class="text-white-50">
                    <i class="fas fa-calendar-alt"></i> 
                    Tham gia từ <?= date('d/m/Y', strtotime($user['created_at'])) ?>
                </small>
            </div>
        </div>
    </div>

    <div class="container mb-5">
        <!-- Thông báo -->
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle"></i> <?= $_SESSION['success'] ?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle"></i> <?= $_SESSION['error'] ?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <!-- Thống kê -->
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="stats-card">
                    <div class="stat-item">
                        <div class="stat-number">
                            <i class="fas fa-globe"></i> <?= $stats['worlds'] ?>
                        </div>
                        <div class="stat-label">Thế giới</div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stats-card">
                    <div class="stat-item">
                        <div class="stat-number">
                            <i class="fas fa-users"></i> <?= $stats['entities'] ?>
                        </div>
                        <div class="stat-label">Thực thể</div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stats-card">
                    <div class="stat-item">
                        <div class="stat-number">
                            <i class="fas fa-link"></i> <?= $stats['relationships'] ?>
                        </div>
                        <div class="stat-label">Mối quan hệ</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Thông tin chi tiết -->
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <div class="info-card">
                    <h3 class="mb-4">
                        <i class="fas fa-user-circle"></i> Thông tin tài khoản
                    </h3>
                    
                    <div class="info-row">
                        <div class="info-label">
                            <i class="fas fa-user"></i> Tên đăng nhập
                        </div>
                        <div class="info-value">
                            <?= htmlspecialchars($user['username']) ?>
                        </div>
                    </div>
                    
                    <div class="info-row">
                        <div class="info-label">
                            <i class="fas fa-envelope"></i> Email
                        </div>
                        <div class="info-value">
                            <?= htmlspecialchars($user['email']) ?>
                        </div>
                    </div>
                    
                    <div class="info-row">
                        <div class="info-label">
                            <i class="fas fa-shield-alt"></i> Quyền hạn
                        </div>
                        <div class="info-value">
                            <?php 
                            $userRole = $user['role'] ?? 'user';
                            if ($userRole === 'super_admin'): ?>
                                <span class="badge badge-danger badge-lg"><i class="fas fa-crown"></i> Super Admin</span>
                            <?php elseif ($userRole === 'admin'): ?>
                                <span class="badge badge-warning badge-lg"><i class="fas fa-shield-alt"></i> Admin</span>
                            <?php elseif ($userRole === 'moderator'): ?>
                                <span class="badge badge-info badge-lg"><i class="fas fa-user-shield"></i> Moderator</span>
                            <?php else: ?>
                                <span class="badge badge-secondary badge-lg"><i class="fas fa-user"></i> User</span>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <div class="info-row">
                        <div class="info-label">
                            <i class="fas fa-calendar-alt"></i> Ngày tạo tài khoản
                        </div>
                        <div class="info-value">
                            <?= date('d/m/Y H:i:s', strtotime($user['created_at'])) ?>
                        </div>
                    </div>
                    
                    <div class="text-center mt-4">
                        <a href="/world-building/public/?url=user/edit" class="btn btn-edit">
                            <i class="fas fa-edit"></i> Chỉnh sửa thông tin
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php require_once '../app/views/layout/footer.php'; ?>
</body>
</html>
