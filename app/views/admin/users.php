<?php
$users = $data['users'];
$search = $data['search'];
$allowedRoles = $data['allowedRoles'];
$currentRole = $data['currentRole'];

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

// Helper function để lấy tên role tiếng Việt
function getRoleLabel($role) {
    $labels = [
        'super_admin' => 'Super Admin',
        'admin' => 'Admin',
        'moderator' => 'Moderator',
        'user' => 'User'
    ];
    return $labels[$role] ?? $role;
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý người dùng - Admin</title>
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
        
        .table-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        .search-box {
            margin-bottom: 20px;
        }
        
        .btn-action {
            padding: 5px 10px;
            font-size: 12px;
            border-radius: 5px;
            margin: 2px;
        }
    </style>
</head>
<body>
    <?php require_once '../app/views/layout/header.php'; ?>
    <?php require_once '../app/views/layout/navigation.php'; ?>

    <div class="admin-header">
        <div class="container">
            <h1 class="mb-0">
                <i class="fas fa-users"></i> Quản lý người dùng
            </h1>
        </div>
    </div>

    <div class="container mb-5">
        <!-- Admin Navigation -->
        <div class="admin-nav">
            <a href="/world-building/public/?url=admin">
                <i class="fas fa-tachometer-alt"></i> Tổng quan
            </a>
            <a href="/world-building/public/?url=admin/users" class="active">
                <i class="fas fa-users"></i> Người dùng
            </a>
            <a href="/world-building/public/?url=admin/worlds">
                <i class="fas fa-globe"></i> Thế giới
            </a>
        </div>

        <!-- Messages -->
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success alert-dismissible fade show">
                <i class="fas fa-check-circle"></i> <?= $_SESSION['success'] ?>
                <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show">
                <i class="fas fa-exclamation-circle"></i> <?= $_SESSION['error'] ?>
                <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <div class="table-card">
            <!-- Search -->
            <div class="search-box">
                <form method="GET" action="/world-building/public/?url=admin/users" class="form-inline">
                    <input type="hidden" name="url" value="admin/users">
                    <div class="input-group" style="width: 400px;">
                        <input type="text" name="search" class="form-control" 
                               placeholder="Tìm kiếm theo tên hoặc email..." 
                               value="<?= htmlspecialchars($search) ?>">
                        <div class="input-group-append">
                            <button class="btn btn-primary" type="submit">
                                <i class="fas fa-search"></i> Tìm
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Table -->
            <table class="table table-striped table-hover">
                <thead class="thead-dark">
                    <tr>
                        <th>ID</th>
                        <th>Tên đăng nhập</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Số thế giới</th>
                        <th>Ngày tạo</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($users)): ?>
                        <?php foreach ($users as $user): ?>
                            <tr>
                                <td><?= $user['user_id'] ?></td>
                                <td>
                                    <i class="fas fa-user"></i> <?= htmlspecialchars($user['username']) ?>
                                </td>
                                <td><?= htmlspecialchars($user['email']) ?></td>
                                <td><?= getRoleBadge($user['role']) ?></td>
                                <td><?= $user['world_count'] ?></td>
                                <td><?= date('d/m/Y', strtotime($user['created_at'])) ?></td>
                                <td>
                                    <?php if ($user['user_id'] != $_SESSION['user_id']): ?>
                                        <!-- Change Role Dropdown -->
                                        <?php 
                                        // Kiểm tra xem có quyền thay đổi role của user này không
                                        $canChange = false;
                                        if ($currentRole === 'super_admin') {
                                            $canChange = true;
                                        } elseif ($currentRole === 'admin' && in_array($user['role'], ['user', 'moderator'])) {
                                            $canChange = true;
                                        }
                                        ?>
                                        
                                        <?php if ($canChange && !empty($allowedRoles)): ?>
                                            <form method="POST" action="/world-building/public/?url=admin/changeRole/<?= $user['user_id'] ?>" 
                                                  style="display: inline-block;">
                                                <select name="role" class="form-control form-control-sm d-inline-block" 
                                                        style="width: auto;" onchange="this.form.submit()">
                                                    <?php foreach ($allowedRoles as $role): ?>
                                                        <option value="<?= $role ?>" <?= $user['role'] === $role ? 'selected' : '' ?>>
                                                            <?= getRoleLabel($role) ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </form>
                                        <?php else: ?>
                                            <span class="text-muted"><i class="fas fa-lock"></i> Không thể sửa</span>
                                        <?php endif; ?>
                                        
                                        <!-- Delete -->
                                        <?php if ($canChange): ?>
                                            <a href="/world-building/public/?url=admin/deleteUser/<?= $user['user_id'] ?>" 
                                               class="btn btn-action btn-danger btn-sm ml-1"
                                               onclick="return confirm('Bạn có chắc muốn xóa người dùng này? Tất cả thế giới của họ cũng sẽ bị xóa!')">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <span class="badge badge-success">
                                            <i class="fas fa-user-check"></i> Bạn
                                        </span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="text-center text-muted">
                                <?= $search ? 'Không tìm thấy kết quả nào' : 'Chưa có người dùng nào' ?>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <?php require_once '../app/views/layout/footer.php'; ?>
</body>
</html>
