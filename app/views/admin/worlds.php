<?php
$worlds = $data['worlds'];
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý thế giới - Admin</title>
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
        
        .btn-action {
            padding: 5px 10px;
            font-size: 12px;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <?php require_once '../app/views/layout/header.php'; ?>
    <?php require_once '../app/views/layout/navigation.php'; ?>

    <div class="admin-header">
        <div class="container">
            <h1 class="mb-0">
                <i class="fas fa-globe"></i> Quản lý thế giới
            </h1>
        </div>
    </div>

    <div class="container mb-5">
        <!-- Admin Navigation -->
        <div class="admin-nav">
            <a href="/world-building/public/?url=admin">
                <i class="fas fa-tachometer-alt"></i> Tổng quan
            </a>
            <a href="/world-building/public/?url=admin/users">
                <i class="fas fa-users"></i> Người dùng
            </a>
            <a href="/world-building/public/?url=admin/worlds" class="active">
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
            <h4 class="mb-3">Danh sách tất cả thế giới</h4>
            
            <table class="table table-striped table-hover">
                <thead class="thead-dark">
                    <tr>
                        <th>ID</th>
                        <th>Tên thế giới</th>
                        <th>Tác giả</th>
                        <th>Số thực thể</th>
                        <th>Ngày tạo</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($worlds)): ?>
                        <?php foreach ($worlds as $world): ?>
                            <tr>
                                <td><?= $world['world_id'] ?></td>
                                <td>
                                    <i class="fas fa-globe"></i> 
                                    <a href="/world-building/public/?url=world/read/<?= $world['world_id'] ?>" target="_blank">
                                        <?= htmlspecialchars($world['name']) ?>
                                    </a>
                                </td>
                                <td><?= htmlspecialchars($world['username']) ?></td>
                                <td><?= $world['entity_count'] ?></td>
                                <td><?= date('d/m/Y', strtotime($world['created_at'])) ?></td>
                                <td>
                                    <a href="/world-building/public/?url=world/read/<?= $world['world_id'] ?>" 
                                       class="btn btn-action btn-info" target="_blank">
                                        <i class="fas fa-eye"></i> Xem
                                    </a>
                                    <a href="/world-building/public/?url=admin/deleteWorld/<?= $world['world_id'] ?>" 
                                       class="btn btn-action btn-danger"
                                       onclick="return confirm('Bạn có chắc muốn xóa thế giới này? Tất cả thực thể và mối quan hệ liên quan cũng sẽ bị xóa!')">
                                        <i class="fas fa-trash"></i> Xóa
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center text-muted">Chưa có thế giới nào</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <?php require_once '../app/views/layout/footer.php'; ?>
</body>
</html>
