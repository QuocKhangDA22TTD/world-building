<?php
$world = isset($data['world']) ? $data['world'] : null;
$entities = isset($data['entities']) ? $data['entities'] : [];
$currentEntity = isset($data['entity']) ? $data['entity'] : null;
$relationships = isset($data['relationships']) ? $data['relationships'] : [];
$action = isset($data['action']) ? $data['action'] : 'list';

// Hàm format JSON thành HTML dạng văn bản mô tả
function formatJsonHtml($data, $indent = 0) {
    $html = '';
    
    if (is_array($data)) {
        // Kiểm tra là array hay object
        $isAssoc = array_keys($data) !== range(0, count($data) - 1);
        
        if ($isAssoc) {
            // Object - hiển thị dạng thuộc tính
            foreach ($data as $key => $value) {
                $html .= '<div class="json-property">';
                
                // Chuyển snake_case/camelCase thành tiêu đề đẹp
                $displayKey = ucfirst(str_replace(['_', '-'], ' ', $key));
                $html .= '<span class="json-property-name">' . htmlspecialchars($displayKey) . ':</span>';
                
                if (is_array($value)) {
                    // Nếu value là array
                    $valueIsAssoc = array_keys($value) !== range(0, count($value) - 1);
                    if ($valueIsAssoc) {
                        // Nested object
                        $html .= '<div class="json-nested">' . formatJsonHtml($value, $indent + 1) . '</div>';
                    } else {
                        // Array items - hiển thị dạng tags
                        $html .= '<span class="json-property-value">';
                        foreach ($value as $item) {
                            if (is_string($item) || is_numeric($item)) {
                                $html .= '<span class="json-array-item">' . htmlspecialchars($item) . '</span>';
                            }
                        }
                        $html .= '</span>';
                    }
                } else {
                    // Simple value
                    $html .= '<span class="json-property-value">';
                    if (is_string($value)) {
                        $html .= htmlspecialchars($value);
                    } elseif (is_numeric($value)) {
                        $html .= $value;
                    } elseif (is_bool($value)) {
                        $html .= $value ? 'Có' : 'Không';
                    } elseif (is_null($value)) {
                        $html .= '<em class="text-muted">Chưa có thông tin</em>';
                    }
                    $html .= '</span>';
                }
                
                $html .= '</div>';
            }
        } else {
            // Array - hiển thị dạng danh sách
            foreach ($data as $value) {
                if (is_string($value) || is_numeric($value)) {
                    $html .= '<span class="json-array-item">' . htmlspecialchars($value) . '</span>';
                } elseif (is_array($value)) {
                    $html .= '<div class="json-nested">' . formatJsonHtml($value, $indent + 1) . '</div>';
                }
            }
        }
    }
    
    return $html;
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $action === 'view' ? 'Chi tiết Thực thể' : 'Danh sách Thực thể' ?> - World Building</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="/world-building/public/css/style.css">
    <style>
        .json-viewer {
            background: #ffffff;
            border-left: 4px solid #007bff;
            padding: 20px;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-size: 15px;
            line-height: 1.8;
            color: #333;
        }
        .json-property {
            margin-bottom: 12px;
            padding-left: 20px;
        }
        .json-property-name {
            color: #495057;
            font-weight: 600;
            display: inline-block;
            min-width: 150px;
            text-transform: capitalize;
        }
        .json-property-value {
            color: #212529;
            margin-left: 10px;
        }
        .json-array-item {
            display: inline-block;
            background: #e9ecef;
            padding: 4px 12px;
            margin: 4px 4px 4px 0;
            border-radius: 15px;
            font-size: 14px;
        }
        .json-nested {
            margin-left: 20px;
            padding-left: 15px;
            border-left: 2px solid #dee2e6;
            margin-top: 8px;
        }
        .json-compact {
            display: inline-block;
            max-width: 200px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-size: 13px;
            color: #6c757d;
        }
        .json-section-title {
            color: #007bff;
            font-weight: 600;
            margin-bottom: 8px;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
    </style>
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
                    <li class="breadcrumb-item active">Thực thể</li>
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
            <!-- Danh sách Thực thể -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2><i class="fas fa-users"></i> Danh sách Thực thể</h2>
                <?php if ($world): ?>
                    <a href="/world-building/public/?url=entity/create&world_id=<?= $world['world_id'] ?>" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Tạo Thực thể mới
                    </a>
                <?php endif; ?>
            </div>

            <?php if ($world): ?>
                <div class="alert alert-info">
                    <strong>Thế giới:</strong> <?= htmlspecialchars($world['name']) ?> | 
                    <strong>Tổng số thực thể:</strong> <?= count($entities) ?>
                </div>
            <?php endif; ?>

            <?php if (empty($entities)): ?>
                <div class="alert alert-warning">
                    <i class="fas fa-info-circle"></i> Chưa có thực thể nào. 
                    <a href="/world-building/public/?url=entity/create&world_id=<?= $world['world_id'] ?>">Tạo thực thể đầu tiên</a>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover table-striped">
                        <thead class="thead-dark">
                            <tr>
                                <th width="5%">#</th>
                                <th width="25%">Tên thực thể</th>
                                <th width="15%">Loại</th>
                                <th width="35%">Thuộc tính</th>
                                <th width="20%" class="text-center">Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($entities as $index => $entity): ?>
                                <tr>
                                    <td><?= $index + 1 ?></td>
                                    <td>
                                        <a href="/world-building/public/?url=entity/read/<?= $entity['entity_id'] ?>" class="font-weight-bold">
                                            <?= htmlspecialchars($entity['name']) ?>
                                        </a>
                                    </td>
                                    <td>
                                        <span class="badge badge-info">
                                            <?= htmlspecialchars($entity['type']) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php 
                                        if (is_array($entity['attributes']) && !empty($entity['attributes'])) {
                                            // Hiển thị JSON compact trong table
                                            $jsonStr = json_encode($entity['attributes'], JSON_UNESCAPED_UNICODE);
                                            echo '<span class="json-compact" title="' . htmlspecialchars($jsonStr) . '">';
                                            echo htmlspecialchars(strlen($jsonStr) > 50 ? substr($jsonStr, 0, 50) . '...' : $jsonStr);
                                            echo '</span>';
                                        } elseif ($entity['attributes']) {
                                            // Nếu là string
                                            $attrs = htmlspecialchars($entity['attributes']);
                                            echo '<span class="json-compact" title="' . $attrs . '">';
                                            echo strlen($attrs) > 50 ? substr($attrs, 0, 50) . '...' : $attrs;
                                            echo '</span>';
                                        } else {
                                            echo '<em class="text-muted">Chưa có</em>';
                                        }
                                        ?>
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group" role="group">
                                            <a href="/world-building/public/?url=entity/read/<?= $entity['entity_id'] ?>" 
                                               class="btn btn-sm btn-info" title="Xem chi tiết">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="/world-building/public/?url=entity/update/<?= $entity['entity_id'] ?>" 
                                               class="btn btn-sm btn-warning" title="Chỉnh sửa">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="/world-building/public/?url=entity/delete/<?= $entity['entity_id'] ?>" 
                                               class="btn btn-sm btn-danger" title="Xóa"
                                               onclick="return confirm('Bạn có chắc muốn xóa thực thể này? Tất cả mối quan hệ liên quan cũng sẽ bị xóa.')">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>

        <?php else: ?>
            <!-- Chi tiết Thực thể -->
            <?php if ($currentEntity): ?>
                <div class="card shadow">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <h3 class="mb-0"><i class="fas fa-user"></i> <?= htmlspecialchars($currentEntity['name']) ?></h3>
                        <div>
                            <a href="/world-building/public/?url=entity/update/<?= $currentEntity['entity_id'] ?>" class="btn btn-warning btn-sm">
                                <i class="fas fa-edit"></i> Chỉnh sửa
                            </a>
                            <a href="/world-building/public/?url=entity&world_id=<?= $currentEntity['world_id'] ?>" class="btn btn-light btn-sm">
                                <i class="fas fa-arrow-left"></i> Quay lại
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Thông tin cơ bản -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <p><strong>Loại thực thể:</strong> 
                                    <span class="badge badge-info badge-lg">
                                        <?= htmlspecialchars($currentEntity['type']) ?>
                                    </span>
                                </p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Thế giới:</strong> 
                                    <a href="/world-building/public/?url=world/read/<?= $world['world_id'] ?>">
                                        <?= htmlspecialchars($world['name']) ?>
                                    </a>
                                </p>
                            </div>
                        </div>

                        <!-- Thuộc tính -->
                        <?php if ($currentEntity['attributes']): ?>
                            <div class="mb-4">
                                <h5><i class="fas fa-list"></i> Thuộc tính:</h5>
                                <?php if (is_array($currentEntity['attributes'])): ?>
                                    <div class="json-viewer">
                                        <?= formatJsonHtml($currentEntity['attributes']) ?>
                                    </div>
                                <?php else: ?>
                                    <div class="alert alert-light">
                                        <?= nl2br(htmlspecialchars($currentEntity['attributes'])) ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>

                        <!-- Mô tả chi tiết -->
                        <div class="mb-4">
                            <h5><i class="fas fa-book"></i> Mô tả chi tiết:</h5>
                            <div class="article-content">
                                <?= $currentEntity['article'] ?>
                            </div>
                        </div>

                        <!-- Mối quan hệ -->
                        <div class="mb-4">
                            <h5><i class="fas fa-link"></i> Mối quan hệ 
                                <span class="badge badge-secondary"><?= count($relationships) ?></span>
                            </h5>
                            <?php if (empty($relationships)): ?>
                                <div class="alert alert-info">
                                    Thực thể này chưa có mối quan hệ nào.
                                    <a href="/world-building/public/?url=relationship/create&world_id=<?= $currentEntity['world_id'] ?>">
                                        Tạo mối quan hệ mới
                                    </a>
                                </div>
                            <?php else: ?>
                                <ul class="list-group">
                                    <?php foreach ($relationships as $rel): ?>
                                        <li class="list-group-item">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    <strong><?= htmlspecialchars($rel['entity1_name']) ?></strong>
                                                    <i class="fas fa-arrow-right mx-2 text-primary"></i>
                                                    <span class="badge badge-warning"><?= htmlspecialchars($rel['type']) ?></span>
                                                    <i class="fas fa-arrow-right mx-2 text-primary"></i>
                                                    <strong><?= htmlspecialchars($rel['entity2_name']) ?></strong>
                                                </div>
                                                <a href="/world-building/public/?url=relationship/read/<?= $rel['relationship_id'] ?>" 
                                                   class="btn btn-sm btn-outline-primary">
                                                    Chi tiết
                                                </a>
                                            </div>
                                            <?php if ($rel['description']): ?>
                                                <small class="text-muted mt-2 d-block">
                                                    <?= htmlspecialchars($rel['description']) ?>
                                                </small>
                                            <?php endif; ?>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>

    <?php require_once '../app/views/layout/footer.php'; ?>

</body>
</html>
