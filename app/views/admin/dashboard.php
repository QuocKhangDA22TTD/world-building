<?php
$stats = $data['stats'];
$recentUsers = $data['recentUsers'];
$recentWorlds = $data['recentWorlds'];
$currentRole = $_SESSION['role'] ?? 'user';

// Helper function để hiển thị badge role
function getRoleBadge($role) {
    $badges = [
        'super_admin' => '<span class="badge badge-danger"><i class="fas fa-crown"></i> Super Admin</span>',
        'admin' => '<span class="badge badge-warning"><i class="fas fa-shield-alt"></i> Admin</span>',
        'moderator' => '<span class="badge badge-info"><i class="fas fa-user-shield"></i> Moderator</span>',
        'user' => '<span class="badge badge-secondary"><i class="fas fa-user"></i> User</span>'
    ];
    return $badges[$role] ?? '<span class="badge badge-secondary">Unknown</span>';
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản trị - World Building</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="/world-building/public/css/style.css">
    <style>
        .admin-header {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: white;
            padding: 30px 0;
            margin-bottom: 30px;
        }
        
        .stat-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            margin-bottom: 30px;
            transition: transform 0.3s;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        }
        
        .stat-icon {
            font-size: 48px;
            margin-bottom: 15px;
        }
        
        .stat-number {
            font-size: 36px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        
        .stat-label {
            color: #6c757d;
            font-size: 14px;
        }
        
        .stat-change {
            font-size: 12px;
            margin-top: 10px;
        }
        
        .table-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }
        
        .admin-nav {
            background: white;
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 30px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        .admin-nav a {
            color: #495057;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 10px;
            transition: all 0.3s;
            display: inline-block;
            margin: 5px;
        }
        
        .admin-nav a:hover, .admin-nav a.active {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: white;
        }
    </style>
</head>
<body>
    <?php require_once '../app/views/layout/header.php'; ?>
    <?php require_once '../app/views/layout/navigation.php'; ?>

    <!-- Admin Header -->
    <div class="admin-header">
        <div class="container">
            <h1 class="mb-0">
                <i class="fas fa-shield-alt"></i> Quản trị hệ thống
            </h1>
            <p class="mb-0">Chào mừng, <?= htmlspecialchars($_SESSION['username'] ?? 'Admin') ?></p>
        </div>
    </div>

    <div class="container mb-5">
        <!-- Admin Navigation -->
        <div class="admin-nav">
            <a href="/world-building/public/?url=admin" class="active">
                <i class="fas fa-tachometer-alt"></i> Tổng quan
            </a>
            <a href="/world-building/public/?url=admin/users">
                <i class="fas fa-users"></i> Người dùng
            </a>
            <a href="/world-building/public/?url=admin/worlds">
                <i class="fas fa-globe"></i> Thế giới
            </a>
        </div>

        <!-- Statistics -->
        <div class="row">
            <div class="col-md-3">
                <div class="stat-card text-center">
                    <div class="stat-icon text-primary">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="stat-number"><?= $stats['total_users'] ?></div>
                    <div class="stat-label">Tổng người dùng</div>
                    <div class="stat-change text-success">
                        <i class="fas fa-arrow-up"></i> +<?= $stats['new_users_today'] ?> hôm nay
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card text-center">
                    <div class="stat-icon text-info">
                        <i class="fas fa-globe"></i>
                    </div>
                    <div class="stat-number"><?= $stats['total_worlds'] ?></div>
                    <div class="stat-label">Tổng thế giới</div>
                    <div class="stat-change text-success">
                        <i class="fas fa-arrow-up"></i> +<?= $stats['new_worlds_today'] ?> hôm nay
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card text-center">
                    <div class="stat-icon text-warning">
                        <i class="fas fa-user-friends"></i>
                    </div>
                    <div class="stat-number"><?= $stats['total_entities'] ?></div>
                    <div class="stat-label">Tổng thực thể</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card text-center">
                    <div class="stat-icon text-danger">
                        <i class="fas fa-link"></i>
                    </div>
                    <div class="stat-number"><?= $stats['total_relationships'] ?></div>
                    <div class="stat-label">Tổng mối quan hệ</div>
                </div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="row">
            <div class="col-md-6">
                <div class="table-card">
                    <h4 class="mb-3">
                        <i class="fas fa-user-plus"></i> Người dùng mới nhất
                    </h4>
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Tên đăng nhập</th>
                                <th>Email</th>
                                <th>Ngày tạo</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($recentUsers)): ?>
                                <?php foreach ($recentUsers as $user): ?>
                                    <tr>
                                        <td>
                                            <i class="fas fa-user"></i> <?= htmlspecialchars($user['username']) ?>
                                            <?= getRoleBadge($user['role']) ?>
                                        </td>
                                        <td><?= htmlspecialchars($user['email']) ?></td>
                                        <td><?= date('d/m/Y', strtotime($user['created_at'])) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="3" class="text-center text-muted">Chưa có người dùng nào</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="table-card">
                    <h4 class="mb-3">
                        <i class="fas fa-globe"></i> Thế giới mới nhất
                    </h4>
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Tên thế giới</th>
                                <th>Tác giả</th>
                                <th>Ngày tạo</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($recentWorlds)): ?>
                                <?php foreach ($recentWorlds as $world): ?>
                                    <tr>
                                        <td>
                                            <i class="fas fa-globe"></i> <?= htmlspecialchars($world['name']) ?>
                                        </td>
                                        <td><?= htmlspecialchars($world['username']) ?></td>
                                        <td><?= date('d/m/Y', strtotime($world['created_at'])) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="3" class="text-center text-muted">Chưa có thế giới nào</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <?php require_once '../app/views/layout/footer.php'; ?>
</body>
</html>
