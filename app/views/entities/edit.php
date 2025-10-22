<?php
$world = isset($data['world']) ? $data['world'] : null;
$entity = isset($data['entity']) ? $data['entity'] : null;
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chỉnh sửa Thực thể - World Building</title>
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
                <?php if ($world && $entity): ?>
                    <li class="breadcrumb-item"><a href="/world-building/public/?url=world/read/<?= $world['world_id'] ?>"><?= htmlspecialchars($world['name']) ?></a></li>
                    <li class="breadcrumb-item"><a href="/world-building/public/?url=entity&world_id=<?= $world['world_id'] ?>">Thực thể</a></li>
                    <li class="breadcrumb-item active">Chỉnh sửa: <?= htmlspecialchars($entity['name']) ?></li>
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
                <h3 class="mb-0"><i class="fas fa-edit"></i> Chỉnh sửa Thực thể</h3>
            </div>
            <div class="card-body">
                <?php if ($entity): ?>
                    <form action="/world-building/public/?url=entity/update/<?= $entity['entity_id'] ?>" method="POST" id="editEntityForm">
                        
                        <!-- Tên thực thể -->
                        <div class="form-group">
                            <label for="name">
                                <i class="fas fa-signature"></i> Tên thực thể <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control" id="name" name="name" 
                                   value="<?= htmlspecialchars($entity['name']) ?>"
                                   required minlength="2">
                            <small class="form-text text-muted">Tối thiểu 2 ký tự</small>
                        </div>

                        <!-- Loại thực thể -->
                        <div class="form-group">
                            <label for="type">
                                <i class="fas fa-tag"></i> Loại thực thể <span class="text-danger">*</span>
                            </label>
                            <select class="form-control" id="type" name="type" required>
                                <option value="">-- Chọn loại thực thể --</option>
                                <option value="Nhân vật" <?= $entity['type'] === 'Nhân vật' ? 'selected' : '' ?>>Nhân vật</option>
                                <option value="Sinh vật" <?= $entity['type'] === 'Sinh vật' ? 'selected' : '' ?>>Sinh vật</option>
                                <option value="Địa điểm" <?= $entity['type'] === 'Địa điểm' ? 'selected' : '' ?>>Địa điểm</option>
                                <option value="Vật phẩm" <?= $entity['type'] === 'Vật phẩm' ? 'selected' : '' ?>>Vật phẩm</option>
                                <option value="Vũ khí" <?= $entity['type'] === 'Vũ khí' ? 'selected' : '' ?>>Vũ khí</option>
                                <option value="Tổ chức" <?= $entity['type'] === 'Tổ chức' ? 'selected' : '' ?>>Tổ chức</option>
                                <option value="Chủng tộc" <?= $entity['type'] === 'Chủng tộc' ? 'selected' : '' ?>>Chủng tộc</option>
                                <option value="Sự kiện" <?= $entity['type'] === 'Sự kiện' ? 'selected' : '' ?>>Sự kiện</option>
                                <option value="Khác" <?= $entity['type'] === 'Khác' ? 'selected' : '' ?>>Khác</option>
                            </select>
                        </div>

                        <!-- Thuộc tính -->
                        <div class="form-group">
                            <label>
                                <i class="fas fa-list"></i> Thuộc tính
                            </label>
                            <div id="attributesContainer">
                                <?php 
                                $attrs = is_array($entity['attributes']) ? $entity['attributes'] : [];
                                if (empty($attrs)): 
                                ?>
                                    <div class="attribute-row mb-2">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <input type="text" class="form-control attribute-key" placeholder="Tên thuộc tính">
                                            </div>
                                            <div class="col-md-7">
                                                <input type="text" class="form-control attribute-value" placeholder="Giá trị">
                                            </div>
                                            <div class="col-md-1">
                                                <button type="button" class="btn btn-danger btn-sm remove-attribute" disabled>
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                <?php else: ?>
                                    <?php foreach ($attrs as $key => $value): ?>
                                        <div class="attribute-row mb-2">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <input type="text" class="form-control attribute-key" 
                                                           value="<?= htmlspecialchars(ucfirst(str_replace('_', ' ', $key))) ?>" 
                                                           placeholder="Tên thuộc tính">
                                                </div>
                                                <div class="col-md-7">
                                                    <input type="text" class="form-control attribute-value" 
                                                           value="<?= htmlspecialchars(is_array($value) ? implode(', ', $value) : $value) ?>" 
                                                           placeholder="Giá trị">
                                                </div>
                                                <div class="col-md-1">
                                                    <button type="button" class="btn btn-danger btn-sm remove-attribute">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                            <button type="button" class="btn btn-success btn-sm" id="addAttribute">
                                <i class="fas fa-plus"></i> Thêm thuộc tính
                            </button>
                            <input type="hidden" name="attributes" id="attributesJson">
                            <small class="form-text text-muted">
                                <i class="fas fa-info-circle"></i> Thêm các thuộc tính của thực thể (không bắt buộc)
                            </small>
                        </div>

                        <!-- Mô tả chi tiết -->
                        <div class="form-group">
                            <label for="article">
                                <i class="fas fa-book"></i> Mô tả chi tiết
                            </label>
                            <textarea class="form-control" id="article" name="article" rows="10"><?= htmlspecialchars($entity['article'] ?? '') ?></textarea>
                            <div class="d-flex justify-content-between align-items-center mt-2">
                                <small class="form-text text-muted">
                                    Sử dụng trình soạn thảo để định dạng văn bản
                                </small>
                                <small id="charCount" class="text-muted">0 ký tự</small>
                            </div>
                        </div>

                        <!-- Buttons -->
                        <div class="form-group">
                            <button type="submit" class="btn btn-warning" id="submitBtn">
                                <i class="fas fa-save"></i> Cập nhật Thực thể
                            </button>
                            <a href="/world-building/public/?url=entity&world_id=<?= $entity['world_id'] ?>" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Hủy
                            </a>
                            <a href="/world-building/public/?url=entity/delete/<?= $entity['entity_id'] ?>" 
                               class="btn btn-danger float-right"
                               onclick="return confirm('Bạn có chắc muốn xóa thực thể này? Tất cả mối quan hệ liên quan cũng sẽ bị xóa.')">
                                <i class="fas fa-trash"></i> Xóa Thực thể
                            </a>
                        </div>
                    </form>
                <?php else: ?>
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-triangle"></i> Không tìm thấy thông tin thực thể!
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <?php require_once '../app/views/layout/footer.php'; ?>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.ckeditor.com/ckeditor5/40.0.0/classic/ckeditor.js"></script>
    <script src="https://cdn.ckeditor.com/ckeditor5/40.0.0/classic/translations/vi.js"></script>
    
    <script>
        let editor;
        
        // Khởi tạo CKEditor
        ClassicEditor
            .create(document.querySelector('#article'), {
                language: 'vi',
                toolbar: [
                    'heading', '|',
                    'bold', 'italic', 'underline', 'strikethrough', '|',
                    'fontSize', 'fontColor', 'fontBackgroundColor', '|',
                    'alignment', '|',
                    'bulletedList', 'numberedList', '|',
                    'outdent', 'indent', '|',
                    'link', 'blockQuote', 'insertTable', '|',
                    'undo', 'redo'
                ]
            })
            .then(newEditor => {
                editor = newEditor;
                
                // Cập nhật số ký tự ban đầu
                updateCharCount();
                
                // Cập nhật số ký tự khi nội dung thay đổi
                editor.model.document.on('change:data', () => {
                    updateCharCount();
                });
            })
            .catch(error => {
                console.error('Lỗi khởi tạo CKEditor:', error);
            });

        // Hàm cập nhật số ký tự
        function updateCharCount() {
            const data = editor.getData();
            const plainText = data.replace(/<[^>]*>/g, ''); // Loại bỏ HTML tags
            document.getElementById('charCount').textContent = plainText.length + ' ký tự';
        }

        // Xử lý thêm/xóa thuộc tính
        let attributeCount = document.querySelectorAll('.attribute-row').length;
        
        document.getElementById('addAttribute').addEventListener('click', function() {
            attributeCount++;
            const container = document.getElementById('attributesContainer');
            const newRow = document.createElement('div');
            newRow.className = 'attribute-row mb-2';
            newRow.innerHTML = `
                <div class="row">
                    <div class="col-md-4">
                        <input type="text" class="form-control attribute-key" placeholder="Tên thuộc tính">
                    </div>
                    <div class="col-md-7">
                        <input type="text" class="form-control attribute-value" placeholder="Giá trị">
                    </div>
                    <div class="col-md-1">
                        <button type="button" class="btn btn-danger btn-sm remove-attribute">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
            `;
            container.appendChild(newRow);
            updateRemoveButtons();
        });

        // Xóa thuộc tính
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('remove-attribute') || e.target.parentElement.classList.contains('remove-attribute')) {
                const row = e.target.closest('.attribute-row');
                row.remove();
                attributeCount--;
                updateRemoveButtons();
            }
        });

        function updateRemoveButtons() {
            const rows = document.querySelectorAll('.attribute-row');
            rows.forEach((row, index) => {
                const btn = row.querySelector('.remove-attribute');
                if (rows.length === 1) {
                    btn.disabled = true;
                } else {
                    btn.disabled = false;
                }
            });
        }

        // Chuyển đổi attributes thành JSON trước khi submit
        function buildAttributesJson() {
            const rows = document.querySelectorAll('.attribute-row');
            const attributes = {};
            
            rows.forEach(row => {
                const key = row.querySelector('.attribute-key').value.trim();
                const value = row.querySelector('.attribute-value').value.trim();
                
                if (key && value) {
                    // Chuyển snake_case hoặc có dấu cách thành snake_case
                    const normalizedKey = key.toLowerCase()
                        .normalize('NFD')
                        .replace(/[\u0300-\u036f]/g, '') // Bỏ dấu tiếng Việt
                        .replace(/đ/g, 'd')
                        .replace(/\s+/g, '_')
                        .replace(/[^a-z0-9_]/g, '');
                    
                    // Thử parse số
                    if (!isNaN(value) && value !== '') {
                        attributes[normalizedKey] = Number(value);
                    } else {
                        attributes[normalizedKey] = value;
                    }
                }
            });
            
            return Object.keys(attributes).length > 0 ? JSON.stringify(attributes) : '';
        }

        // Initialize remove buttons
        updateRemoveButtons();

        // Xử lý submit form
        document.getElementById('editEntityForm').addEventListener('submit', function(e) {
            // Lấy dữ liệu từ editor và lưu vào textarea
            const articleTextarea = document.getElementById('article');
            articleTextarea.value = editor.getData();

            // Build JSON từ các input attributes
            const attributesJson = buildAttributesJson();
            document.getElementById('attributesJson').value = attributesJson;

            // Validate tên
            const name = document.getElementById('name').value.trim();
            if (name.length < 2) {
                e.preventDefault();
                alert('Tên thực thể phải có ít nhất 2 ký tự!');
                return false;
            }

            // Validate loại
            const type = document.getElementById('type').value;
            if (!type) {
                e.preventDefault();
                alert('Vui lòng chọn loại thực thể!');
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
