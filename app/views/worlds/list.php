<?php
// Start session if not already
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include_once __DIR__ . '/../layout/header.php';
include_once __DIR__ . '/../layout/navigation.php';

// Get data from controller - check if $data exists first
$worlds = isset($data['worlds']) ? $data['worlds'] : [];
$currentWorld = isset($data['currentWorld']) ? $data['currentWorld'] : null;
$action = isset($data['action']) ? $data['action'] : 'list';

// Display flash messages
$successMessage = $_SESSION['success_message'] ?? null;
$errorMessages = $_SESSION['error_messages'] ?? null;
unset($_SESSION['success_message'], $_SESSION['error_messages']);
?>

<div class="main-content">
    <div class="container py-5">
        <!-- Flash Messages -->
        <?php if (!empty($successMessage)): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?= htmlspecialchars($successMessage) ?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        <?php endif; ?>
        
        <?php if (!empty($errorMessages)): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <ul class="mb-0">
                    <?php foreach ((array)$errorMessages as $err): ?>
                        <li><?= htmlspecialchars($err) ?></li>
                    <?php endforeach; ?>
                </ul>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        <?php endif; ?>

        <?php if ($action === 'list'): ?>
            <!-- DANH SÁCH THẾ GIỚI -->
            <div class="row mb-4">
                <div class="col-md-8">
                    <h1 class="mb-3">
                        <i class="fas fa-globe"></i> Danh sách thế giới của tôi
                    </h1>
                    <p class="text-muted">Quản lý các thế giới ảo bạn đã tạo</p>
                </div>
                <div class="col-md-4 text-right">
                    <a href="?url=world/create" class="btn btn-primary btn-lg">
                        <i class="fas fa-plus-circle"></i> Tạo thế giới mới
                    </a>
                </div>
            </div>

            <?php if (empty($worlds)): ?>
                <div class="alert alert-info">
                    <h5><i class="fas fa-info-circle"></i> Chưa có thế giới nào</h5>
                    <p class="mb-0">Bạn chưa tạo thế giới nào. Hãy nhấn nút "Tạo thế giới mới" để bắt đầu!</p>
                </div>
            <?php else: ?>
                <div class="row">
                    <?php foreach ($worlds as $world): ?>
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="card h-100 shadow-sm hover-shadow">
                                <div class="card-header bg-gradient-primary text-white">
                                    <h5 class="card-title mb-0">
                                        <i class="fas fa-book"></i>
                                        <?= htmlspecialchars($world['name']) ?>
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <p class="card-text text-muted">
                                        <?php 
                                        $preview = strip_tags($world['article']);
                                        echo htmlspecialchars(mb_substr($preview, 0, 120)) . (mb_strlen($preview) > 120 ? '...' : ''); 
                                        ?>
                                    </p>
                                    <p class="card-text">
                                        <small class="text-muted">
                                            <i class="far fa-clock"></i>
                                            Tạo lúc: <?= date('d/m/Y H:i', strtotime($world['created_at'])) ?>
                                        </small>
                                    </p>
                                </div>
                                <div class="card-footer bg-white border-top">
                                    <div class="btn-group btn-group-sm w-100" role="group">
                                        <a href="?url=world/read/<?= $world['world_id'] ?>" 
                                           class="btn btn-outline-info" 
                                           title="Xem chi tiết">
                                            <i class="fas fa-eye"></i> Xem
                                        </a>
                                        <a href="?url=world/update/<?= $world['world_id'] ?>" 
                                           class="btn btn-outline-warning"
                                           title="Chỉnh sửa">
                                            <i class="fas fa-edit"></i> Sửa
                                        </a>
                                        <a href="?url=world/delete/<?= $world['world_id'] ?>" 
                                           class="btn btn-outline-danger"
                                           onclick="return confirm('Bạn có chắc muốn xóa thế giới này?\n\nLưu ý: Tất cả dữ liệu liên quan sẽ bị xóa vĩnh viễn!')"
                                           title="Xóa">
                                            <i class="fas fa-trash-alt"></i> Xóa
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

        <?php else: ?>
            <!-- XEM CHI TIẾT THẾ GIỚI -->
            <div class="row">
                <div class="col-md-10 offset-md-1">
                    <div class="mb-3">
                        <a href="?url=world" class="btn btn-primary">
                            <i class="fas fa-arrow-left"></i> Quay lại danh sách
                        </a>
                        <a href="?url=world/update/<?= $currentWorld['world_id'] ?>" class="btn btn-warning">
                            <i class="fas fa-edit"></i> Chỉnh sửa
                        </a>
                        <a href="?url=world/delete/<?= $currentWorld['world_id'] ?>" 
                           class="btn btn-danger"
                           onclick="return confirm('Bạn có chắc muốn xóa thế giới này?')">
                            <i class="fas fa-trash-alt"></i> Xóa
                        </a>
                    </div>

                    <div class="card shadow-lg">
                        <div class="card-header bg-gradient-info text-white py-4">
                            <h1 class="display-4 mb-2">
                                <i class="fas fa-globe-americas"></i>
                                <?= htmlspecialchars($currentWorld['name']) ?>
                            </h1>
                            <p class="mb-0">
                                <i class="far fa-clock"></i>
                                Tạo lúc: <?= date('d/m/Y H:i', strtotime($currentWorld['created_at'])) ?>
                            </p>
                        </div>
                        <div class="card-body p-5">
                            <?php if (empty($currentWorld['article'])): ?>
                                <div class="alert alert-warning">
                                    <i class="fas fa-exclamation-triangle"></i>
                                    Chưa có mô tả chi tiết cho thế giới này.
                                </div>
                            <?php else: ?>
                                <div class="world-article">
                                    <?= $currentWorld['article'] ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- THÔNG TIN THÊM -->
                    <div class="row mt-4">
                        <div class="col-md-6">
                            <div class="card border-primary">
                                <div class="card-header bg-primary text-white">
                                    <h5 class="mb-0">
                                        <i class="fas fa-users"></i> Các thực thể (Entities)
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <p class="mb-3">
                                        Quản lý các thực thể trong thế giới của bạn như nhân vật, sinh vật, vũ khí, địa điểm...
                                    </p>
                                    <a href="/world-building/public/?url=entity&world_id=<?= $currentWorld['world_id'] ?>" 
                                       class="btn btn-primary">
                                        <i class="fas fa-arrow-right"></i> Xem danh sách thực thể
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card border-success">
                                <div class="card-header bg-success text-white">
                                    <h5 class="mb-0">
                                        <i class="fas fa-project-diagram"></i> Mối quan hệ (Relationships)
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <p class="mb-3">
                                        Quản lý mối quan hệ giữa các thực thể như cha con, sư phụ đệ tử, bạn bè...
                                    </p>
                                    <a href="/world-building/public/?url=relationship&world_id=<?= $currentWorld['world_id'] ?>" 
                                       class="btn btn-success">
                                        <i class="fas fa-arrow-right"></i> Xem danh sách mối quan hệ
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>

    </div>
</div>

<style>
/* Custom styles cho trang world */
.bg-gradient-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.bg-gradient-info {
    background: linear-gradient(135deg, #667eea 0%, #00d2ff 100%);
}

.hover-shadow {
    transition: all 0.3s ease;
}

.hover-shadow:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 20px rgba(0,0,0,0.2) !important;
}

.world-article {
    font-size: 1.1rem;
    line-height: 1.8;
    text-align: justify;
    color: #333;
}

.card-header h5 {
    font-weight: 600;
}

.btn-group .btn {
    font-size: 0.875rem;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .display-4 {
        font-size: 2rem;
    }
    
    .world-article {
        font-size: 1rem;
    }
}
</style>

<?php
include_once __DIR__ . '/../layout/footer.php';
?>
