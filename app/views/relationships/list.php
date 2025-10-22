<?php
$world = isset($data['world']) ? $data['world'] : null;
$relationships = isset($data['relationships']) ? $data['relationships'] : [];
$currentRelationship = isset($data['relationship']) ? $data['relationship'] : null;
$action = isset($data['action']) ? $data['action'] : 'list';
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $action === 'view' ? 'Chi tiết Mối quan hệ' : 'Danh sách Mối quan hệ' ?> - World Building</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="/world-building/public/css/style.css">
</head>
<body>
    <?php require_once '../app/views/layout/header.php'; ?>
    <?php require_once '../app/views/layout/navigation.php'; ?>

    <div class="container mt-4">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/world-building/public/?url=world">Thế giới</a></li>
                <?php if ($world): ?>
                    <li class="breadcrumb-item"><a href="/world-building/public/?url=world/read/<?= $world['world_id'] ?>"><?= htmlspecialchars($world['name']) ?></a></li>
                    <li class="breadcrumb-item active">Mối quan hệ</li>
                <?php endif; ?>
            </ol>
        </nav>

        <!-- Flash Messages -->
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success alert-dismissible fade show">
                <?= $_SESSION['success'] ?>
                <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show">
                <?= $_SESSION['error'] ?>
                <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <?php if ($action === 'list'): ?>
            <!-- Danh sách Mối quan hệ -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2><i class="fas fa-link"></i> Danh sách Mối quan hệ</h2>
                <?php if ($world): ?>
                    <a href="/world-building/public/?url=relationship/create&world_id=<?= $world['world_id'] ?>" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Tạo Mối quan hệ mới
                    </a>
                <?php endif; ?>
            </div>

            <?php if ($world): ?>
                <div class="alert alert-info">
                    <strong>Thế giới:</strong> <?= htmlspecialchars($world['name']) ?> | 
                    <strong>Tổng số mối quan hệ:</strong> <?= count($relationships) ?>
                </div>
            <?php endif; ?>

            <?php if (empty($relationships)): ?>
                <div class="alert alert-warning">
                    <i class="fas fa-info-circle"></i> Chưa có mối quan hệ nào. 
                    <a href="/world-building/public/?url=relationship/create&world_id=<?= $world['world_id'] ?>">Tạo mối quan hệ đầu tiên</a>
                </div>
            <?php else: ?>
                <div class="row">
                    <?php foreach ($relationships as $rel): ?>
                        <div class="col-md-6 mb-3">
                            <div class="card shadow-sm h-100">
                                <div class="card-body">
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="flex-grow-1">
                                            <div class="relationship-diagram">
                                                <span class="entity-badge badge badge-primary">
                                                    <?= htmlspecialchars($rel['entity1_name']) ?>
                                                </span>
                                                <i class="fas fa-arrow-right mx-2 text-muted"></i>
                                                <span class="type-badge badge badge-warning">
                                                    <?= htmlspecialchars($rel['type']) ?>
                                                </span>
                                                <i class="fas fa-arrow-right mx-2 text-muted"></i>
                                                <span class="entity-badge badge badge-success">
                                                    <?= htmlspecialchars($rel['entity2_name']) ?>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <?php if ($rel['description']): ?>
                                        <p class="text-muted mb-3">
                                            <small>
                                                <?= strlen($rel['description']) > 100 
                                                    ? htmlspecialchars(substr($rel['description'], 0, 100)) . '...' 
                                                    : htmlspecialchars($rel['description']) ?>
                                            </small>
                                        </p>
                                    <?php endif; ?>

                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="/world-building/public/?url=relationship/read/<?= $rel['relationship_id'] ?>" 
                                           class="btn btn-info" title="Xem chi tiết">
                                            <i class="fas fa-eye"></i> Chi tiết
                                        </a>
                                        <a href="/world-building/public/?url=relationship/update/<?= $rel['relationship_id'] ?>" 
                                           class="btn btn-warning" title="Chỉnh sửa">
                                            <i class="fas fa-edit"></i> Sửa
                                        </a>
                                        <a href="/world-building/public/?url=relationship/delete/<?= $rel['relationship_id'] ?>" 
                                           class="btn btn-danger" title="Xóa"
                                           onclick="return confirm('Bạn có chắc muốn xóa mối quan hệ này?')">
                                            <i class="fas fa-trash"></i> Xóa
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

        <?php else: ?>
            <!-- Chi tiết Mối quan hệ -->
            <?php if ($currentRelationship): ?>
                <div class="card shadow">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <h3 class="mb-0"><i class="fas fa-link"></i> Chi tiết Mối quan hệ</h3>
                        <div>
                            <a href="/world-building/public/?url=relationship/update/<?= $currentRelationship['relationship_id'] ?>" class="btn btn-warning btn-sm">
                                <i class="fas fa-edit"></i> Chỉnh sửa
                            </a>
                            <a href="/world-building/public/?url=relationship&world_id=<?= $currentRelationship['world_id'] ?>" class="btn btn-light btn-sm">
                                <i class="fas fa-arrow-left"></i> Quay lại
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Sơ đồ mối quan hệ -->
                        <div class="text-center mb-4 p-4 bg-light rounded">
                            <div class="relationship-visual">
                                <div class="entity-box">
                                    <h5 class="text-primary">
                                        <i class="fas fa-user"></i> 
                                        <?= htmlspecialchars($currentRelationship['entity1_name']) ?>
                                    </h5>
                                    <small class="text-muted"><?= htmlspecialchars($currentRelationship['entity1_type']) ?></small>
                                </div>
                                
                                <div class="my-3">
                                    <i class="fas fa-arrow-down fa-2x text-warning"></i>
                                    <h4 class="badge badge-warning badge-lg mt-2">
                                        <?= htmlspecialchars($currentRelationship['type']) ?>
                                    </h4>
                                    <i class="fas fa-arrow-down fa-2x text-warning"></i>
                                </div>
                                
                                <div class="entity-box">
                                    <h5 class="text-success">
                                        <i class="fas fa-user"></i> 
                                        <?= htmlspecialchars($currentRelationship['entity2_name']) ?>
                                    </h5>
                                    <small class="text-muted"><?= htmlspecialchars($currentRelationship['entity2_type']) ?></small>
                                </div>
                            </div>
                        </div>

                        <!-- Mô tả -->
                        <?php if ($currentRelationship['description']): ?>
                            <div class="mb-4">
                                <h5><i class="fas fa-align-left"></i> Mô tả chi tiết:</h5>
                                <div class="alert alert-light">
                                    <?= nl2br(htmlspecialchars($currentRelationship['description'])) ?>
                                </div>
                            </div>
                        <?php endif; ?>

                        <!-- Liên kết -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <h6><i class="fas fa-link"></i> Thực thể 1</h6>
                                        <a href="/world-building/public/?url=entity/read/<?= $currentRelationship['entity1_id'] ?>" class="btn btn-sm btn-outline-primary">
                                            Xem chi tiết <?= htmlspecialchars($currentRelationship['entity1_name']) ?>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <h6><i class="fas fa-link"></i> Thực thể 2</h6>
                                        <a href="/world-building/public/?url=entity/read/<?= $currentRelationship['entity2_id'] ?>" class="btn btn-sm btn-outline-success">
                                            Xem chi tiết <?= htmlspecialchars($currentRelationship['entity2_name']) ?>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>

    <?php require_once '../app/views/layout/footer.php'; ?>

    <style>
        .relationship-diagram {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 5px;
        }
        .entity-badge {
            font-size: 0.9rem;
            padding: 0.4rem 0.8rem;
        }
        .type-badge {
            font-size: 0.85rem;
            padding: 0.4rem 0.6rem;
        }
        .badge-lg {
            font-size: 1.1rem;
            padding: 0.6rem 1.2rem;
        }
        .entity-box {
            border: 2px solid #dee2e6;
            border-radius: 8px;
            padding: 15px;
            background: white;
        }
    </style>
</body>
</html>
