<?php
$world = isset($data['world']) ? $data['world'] : null;
$relationship = isset($data['relationship']) ? $data['relationship'] : null;
$entities = isset($data['entities']) ? $data['entities'] : [];
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chỉnh sửa Mối quan hệ - World Building</title>
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
                <?php if ($world && $relationship): ?>
                    <li class="breadcrumb-item"><a href="/world-building/public/?url=world/read/<?= $world['world_id'] ?>"><?= htmlspecialchars($world['name']) ?></a></li>
                    <li class="breadcrumb-item"><a href="/world-building/public/?url=relationship&world_id=<?= $world['world_id'] ?>">Mối quan hệ</a></li>
                    <li class="breadcrumb-item active">Chỉnh sửa</li>
                <?php endif; ?>
            </ol>
        </nav>

        <!-- Flash Messages -->
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show">
                <?= $_SESSION['error'] ?>
                <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <div class="card shadow">
            <div class="card-header bg-warning text-white">
                <h3 class="mb-0"><i class="fas fa-edit"></i> Chỉnh sửa Mối quan hệ</h3>
            </div>
            <div class="card-body">
                <?php if ($relationship): ?>
                    <form action="/world-building/public/?url=relationship/update/<?= $relationship['relationship_id'] ?>" method="POST" id="editRelationshipForm">
                        
                        <!-- Thực thể 1 -->
                        <div class="form-group">
                            <label for="entity1_id">
                                <i class="fas fa-user"></i> Thực thể 1 <span class="text-danger">*</span>
                            </label>
                            <select class="form-control" id="entity1_id" name="entity1_id" required>
                                <option value="">-- Chọn thực thể đầu tiên --</option>
                                <?php foreach ($entities as $entity): ?>
                                    <option value="<?= $entity['entity_id'] ?>" 
                                            <?= $entity['entity_id'] == $relationship['entity1_id'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($entity['name']) ?> 
                                        (<?= htmlspecialchars($entity['type']) ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Loại quan hệ -->
                        <div class="form-group">
                            <label for="type">
                                <i class="fas fa-tag"></i> Loại quan hệ <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control" id="type" name="type" 
                                   list="relationshipTypes"
                                   value="<?= htmlspecialchars($relationship['type']) ?>"
                                   placeholder="Ví dụ: Cha con, Sư phụ - Đệ tử, Đối thủ..." 
                                   required>
                            <datalist id="relationshipTypes">
                                <option value="Cha con">
                                <option value="Mẹ con">
                                <option value="Anh em">
                                <option value="Vợ chồng">
                                <option value="Bạn bè">
                                <option value="Sư phụ - Đệ tử">
                                <option value="Chủ - Tớ">
                                <option value="Đối thủ">
                                <option value="Kẻ thù">
                                <option value="Đồng minh">
                                <option value="Chủ - Thú cưng">
                                <option value="Tạo ra">
                                <option value="Sở hữu">
                                <option value="Thuộc về">
                            </datalist>
                            <small class="form-text text-muted">
                                Bạn có thể chọn từ danh sách gợi ý hoặc nhập loại quan hệ mới
                            </small>
                        </div>

                        <!-- Thực thể 2 -->
                        <div class="form-group">
                            <label for="entity2_id">
                                <i class="fas fa-user"></i> Thực thể 2 <span class="text-danger">*</span>
                            </label>
                            <select class="form-control" id="entity2_id" name="entity2_id" required>
                                <option value="">-- Chọn thực thể thứ hai --</option>
                                <?php foreach ($entities as $entity): ?>
                                    <option value="<?= $entity['entity_id'] ?>"
                                            <?= $entity['entity_id'] == $relationship['entity2_id'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($entity['name']) ?> 
                                        (<?= htmlspecialchars($entity['type']) ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Mô tả chi tiết -->
                        <div class="form-group">
                            <label for="description">
                                <i class="fas fa-align-left"></i> Mô tả chi tiết về mối quan hệ
                            </label>
                            <textarea class="form-control" id="description" name="description" rows="5"
                                      placeholder="Mô tả chi tiết về mối quan hệ giữa hai thực thể..."><?= htmlspecialchars($relationship['description'] ?? '') ?></textarea>
                            <small class="form-text text-muted">
                                Ví dụ: Tuấn là cha của Thành, họ có mối quan hệ cha con rất thân thiết...
                            </small>
                        </div>

                        <!-- Preview -->
                        <div class="alert alert-info" id="preview" style="display: none;">
                            <h6><i class="fas fa-eye"></i> Xem trước mối quan hệ:</h6>
                            <div class="text-center">
                                <span id="previewEntity1" class="badge badge-primary">-</span>
                                <i class="fas fa-arrow-right mx-2"></i>
                                <span id="previewType" class="badge badge-warning">-</span>
                                <i class="fas fa-arrow-right mx-2"></i>
                                <span id="previewEntity2" class="badge badge-success">-</span>
                            </div>
                        </div>

                        <!-- Buttons -->
                        <div class="form-group">
                            <button type="submit" class="btn btn-warning" id="submitBtn">
                                <i class="fas fa-save"></i> Cập nhật Mối quan hệ
                            </button>
                            <a href="/world-building/public/?url=relationship&world_id=<?= $relationship['world_id'] ?>" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Hủy
                            </a>
                            <a href="/world-building/public/?url=relationship/delete/<?= $relationship['relationship_id'] ?>" 
                               class="btn btn-danger float-right"
                               onclick="return confirm('Bạn có chắc muốn xóa mối quan hệ này?')">
                                <i class="fas fa-trash"></i> Xóa Mối quan hệ
                            </a>
                        </div>
                    </form>
                <?php else: ?>
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-triangle"></i> Không tìm thấy thông tin mối quan hệ!
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <?php require_once '../app/views/layout/footer.php'; ?>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Cập nhật preview khi trang load
        window.addEventListener('load', updatePreview);

        // Cập nhật preview khi thay đổi
        function updatePreview() {
            const entity1Select = document.getElementById('entity1_id');
            const entity2Select = document.getElementById('entity2_id');
            const typeInput = document.getElementById('type');
            const preview = document.getElementById('preview');

            const entity1 = entity1Select.options[entity1Select.selectedIndex].text;
            const entity2 = entity2Select.options[entity2Select.selectedIndex].text;
            const type = typeInput.value;

            if (entity1Select.value && entity2Select.value && type) {
                document.getElementById('previewEntity1').textContent = entity1;
                document.getElementById('previewEntity2').textContent = entity2;
                document.getElementById('previewType').textContent = type;
                preview.style.display = 'block';
            } else {
                preview.style.display = 'none';
            }
        }

        // Lắng nghe sự kiện thay đổi
        document.getElementById('entity1_id').addEventListener('change', updatePreview);
        document.getElementById('entity2_id').addEventListener('change', updatePreview);
        document.getElementById('type').addEventListener('input', updatePreview);

        // Xử lý submit form
        document.getElementById('editRelationshipForm').addEventListener('submit', function(e) {
            const entity1Id = parseInt(document.getElementById('entity1_id').value);
            const entity2Id = parseInt(document.getElementById('entity2_id').value);
            const type = document.getElementById('type').value.trim();

            // Validate không được chọn cùng một thực thể
            if (entity1Id === entity2Id) {
                e.preventDefault();
                alert('Không thể tạo mối quan hệ giữa một thực thể với chính nó!');
                return false;
            }

            // Validate loại quan hệ
            if (!type) {
                e.preventDefault();
                alert('Vui lòng nhập loại quan hệ!');
                return false;
            }

            // Hiển thị trạng thái loading
            const submitBtn = document.getElementById('submitBtn');
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Đang cập nhật...';
        });
    </script>
</body>
</html>
