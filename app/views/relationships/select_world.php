<?php
$worlds = isset($data['worlds']) ? $data['worlds'] : [];
$message = isset($data['message']) ? $data['message'] : 'Vui lòng chọn một thế giới';
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chọn Thế giới - Mối quan hệ</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="/world-building/public/css/style.css">
</head>
<body>
    <?php require_once '../app/views/layout/header.php'; ?>
    <?php require_once '../app/views/layout/navigation.php'; ?>

    <div class="container mt-5">
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <div class="alert alert-info text-center">
                    <h4><i class="fas fa-info-circle"></i> <?= htmlspecialchars($message) ?></h4>
                </div>

                <?php if (empty($worlds)): ?>
                    <div class="alert alert-warning text-center">
                        <p><i class="fas fa-exclamation-triangle"></i> Bạn chưa có thế giới nào.</p>
                        <a href="/world-building/public/?url=world/create" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Tạo thế giới mới
                        </a>
                    </div>
                <?php else: ?>
                    <div class="card shadow">
                        <div class="card-header bg-success text-white">
                            <h5 class="mb-0"><i class="fas fa-globe"></i> Chọn thế giới</h5>
                        </div>
                        <div class="card-body">
                            <div class="list-group">
                                <?php foreach ($worlds as $world): ?>
                                    <a href="/world-building/public/?url=relationship&world_id=<?= $world['world_id'] ?>" 
                                       class="list-group-item list-group-item-action">
                                        <div class="d-flex w-100 justify-content-between align-items-center">
                                            <h5 class="mb-1">
                                                <i class="fas fa-globe-americas text-success"></i>
                                                <?= htmlspecialchars($world['name']) ?>
                                            </h5>
                                            <i class="fas fa-arrow-right text-muted"></i>
                                        </div>
                                        <?php if (!empty($world['article'])): ?>
                                            <p class="mb-1 text-muted">
                                                <?php 
                                                $preview = strip_tags($world['article']);
                                                echo strlen($preview) > 100 ? substr($preview, 0, 100) . '...' : $preview;
                                                ?>
                                            </p>
                                        <?php endif; ?>
                                        <small class="text-muted">
                                            <i class="far fa-clock"></i>
                                            Tạo lúc: <?= date('d/m/Y', strtotime($world['created_at'])) ?>
                                        </small>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <?php require_once '../app/views/layout/footer.php'; ?>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
