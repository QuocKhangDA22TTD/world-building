<?php
// Start session if not already
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include_once __DIR__ . '/../layout/header.php';
include_once __DIR__ . '/../layout/navigation.php';

// Get data from controller - check if $data exists first
$world = isset($data['world']) ? $data['world'] : null;

// Handle errors
$errors = $_SESSION['error_messages'] ?? [];
$oldData = $_SESSION['old_data'] ?? [];
unset($_SESSION['error_messages'], $_SESSION['old_data']);

// Use old data if available (for validation errors), otherwise use world data
$name = $oldData['name'] ?? ($world['name'] ?? '');
$article = $oldData['article'] ?? ($world['article'] ?? '');
?>

<div class="main-content">
    <div class="container py-5">
        <div class="row">
            <div class="col-md-10 offset-md-1">
                <!-- Back button -->
                <div class="mb-4">
                    <a href="?url=world" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Quay lại danh sách
                    </a>
                </div>

                <!-- Page title -->
                <div class="card shadow-lg">
                    <div class="card-header bg-gradient-warning text-white py-4">
                        <h1 class="mb-0">
                            <i class="fas fa-edit"></i> Chỉnh sửa thế giới
                        </h1>
                    </div>

                    <div class="card-body p-5">
                        <!-- Display errors -->
                        <?php if (!empty($errors)): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <h5 class="alert-heading">
                                    <i class="fas fa-exclamation-triangle"></i> Có lỗi xảy ra:
                                </h5>
                                <ul class="mb-0">
                                    <?php foreach ($errors as $error): ?>
                                        <li><?= htmlspecialchars($error) ?></li>
                                    <?php endforeach; ?>
                                </ul>
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        <?php endif; ?>

                        <!-- Edit Form -->
                        <form action="?url=world/update/<?= $world['world_id'] ?>" method="POST" id="editWorldForm">
                            <!-- World Name -->
                            <div class="form-group">
                                <label for="name" class="font-weight-bold">
                                    <i class="fas fa-signature"></i> Tên thế giới
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text" 
                                       class="form-control form-control-lg <?= !empty($errors) && empty($name) ? 'is-invalid' : '' ?>" 
                                       id="name" 
                                       name="name" 
                                       value="<?= htmlspecialchars($name) ?>"
                                       placeholder="Ví dụ: Thế giới Narnia, Middle Earth, Westeros..."
                                       maxlength="255"
                                       required>
                                <small class="form-text text-muted">
                                    <i class="fas fa-info-circle"></i>
                                    Tên thế giới phải từ 3-255 ký tự
                                </small>
                            </div>

                            <!-- World Article/Description -->
                            <div class="form-group">
                                <label for="article" class="font-weight-bold">
                                    <i class="fas fa-book-open"></i> Mô tả thế giới
                                    <span class="text-danger">*</span>
                                </label>
                                <textarea class="form-control <?= !empty($errors) && empty($article) ? 'is-invalid' : '' ?>" 
                                          id="article" 
                                          name="article" 
                                          rows="15"
                                          placeholder="Mô tả chi tiết về thế giới của bạn: lịch sử, địa lý, văn hóa, ma thuật, công nghệ..."
                                          required><?= htmlspecialchars($article) ?></textarea>
                                <small class="form-text text-muted">
                                    <i class="fas fa-keyboard"></i>
                                    <span id="charCount">0</span> ký tự
                                    | Tối thiểu 10 ký tự
                                </small>
                            </div>

                            <!-- Action buttons -->
                            <div class="form-group mb-0">
                                <button type="submit" class="btn btn-warning btn-lg px-5">
                                    <i class="fas fa-save"></i> Lưu thay đổi
                                </button>
                                <a href="?url=world/read/<?= $world['world_id'] ?>" class="btn btn-secondary btn-lg">
                                    <i class="fas fa-times"></i> Hủy
                                </a>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Metadata info -->
                <div class="card mt-4">
                    <div class="card-body">
                        <h6 class="text-muted mb-2">
                            <i class="fas fa-info-circle"></i> Thông tin
                        </h6>
                        <p class="mb-0">
                            <strong>Tạo lúc:</strong> 
                            <?= date('d/m/Y H:i:s', strtotime($world['created_at'])) ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Gradient background for header */
.bg-gradient-warning {
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
}

/* Form styling */
#editWorldForm .form-control:focus {
    border-color: #f5576c;
    box-shadow: 0 0 0 0.2rem rgba(245, 87, 108, 0.25);
}

#editWorldForm .is-invalid {
    border-color: #dc3545;
}

/* Button styling */
.btn-warning {
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    border: none;
    color: white;
}

.btn-warning:hover {
    background: linear-gradient(135deg, #f5576c 0%, #f093fb 100%);
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(245, 87, 108, 0.3);
}

/* Textarea styling */
#article {
    font-size: 1rem;
    line-height: 1.6;
    resize: vertical;
    min-height: 300px;
}

/* Character counter */
#charCount {
    font-weight: bold;
    color: #667eea;
}

/* Responsive */
@media (max-width: 768px) {
    .card-body {
        padding: 2rem !important;
    }
}
</style>

<!-- CKEditor 5 Classic Build -->
<script src="https://cdn.ckeditor.com/ckeditor5/40.0.0/classic/ckeditor.js"></script>

<script>
let editor;

// Initialize CKEditor
ClassicEditor
    .create(document.querySelector('#article'), {
        toolbar: {
            items: [
                'heading', '|',
                'bold', 'italic', 'underline', 'strikethrough', '|',
                'fontSize', 'fontColor', 'fontBackgroundColor', '|',
                'alignment', '|',
                'bulletedList', 'numberedList', '|',
                'outdent', 'indent', '|',
                'link', 'blockQuote', 'insertTable', '|',
                'undo', 'redo'
            ],
            shouldNotGroupWhenFull: true
        },
        language: 'vi'
    })
    .then(newEditor => {
        editor = newEditor;
        
        // Update character count on change
        editor.model.document.on('change:data', () => {
            updateCharCount();
        });
        
        // Initial count
        setTimeout(() => updateCharCount(), 300);
    })
    .catch(error => {
        console.error('CKEditor initialization error:', error);
    });

// Character counter
document.addEventListener('DOMContentLoaded', function() {
    const articleTextarea = document.getElementById('article');
    const charCountSpan = document.getElementById('charCount');
    
    function updateCharCount() {
        let count;
        // Get plain text from CKEditor
        if (editor) {
            const data = editor.getData();
            const plainText = data.replace(/<[^>]*>/g, ''); // Strip HTML tags
            count = plainText.length;
        } else {
            count = articleTextarea.value.length;
        }
        
        charCountSpan.textContent = count.toLocaleString();
        
        // Change color based on length
        if (count < 10) {
            charCountSpan.style.color = '#dc3545'; // Red
        } else if (count < 50) {
            charCountSpan.style.color = '#ffc107'; // Yellow
        } else {
            charCountSpan.style.color = '#28a745'; // Green
        }
    }
    
    window.updateCharCount = updateCharCount;
    
    // Initial count (wait for CKEditor to load)
    setTimeout(updateCharCount, 500);
    
    // Form validation
    document.getElementById('editWorldForm').addEventListener('submit', function(e) {
        const name = document.getElementById('name').value.trim();
        
        // Get content from CKEditor and update textarea
        if (editor) {
            articleTextarea.value = editor.getData();
        }
        
        const article = articleTextarea.value.trim();
        
        if (name.length < 3) {
            e.preventDefault();
            alert('Tên thế giới phải có ít nhất 3 ký tự!');
            document.getElementById('name').focus();
            return false;
        }
        
        if (article.length < 10) {
            e.preventDefault();
            alert('Mô tả thế giới phải có ít nhất 10 ký tự!');
            if (editor) {
                editor.focus();
            } else {
                articleTextarea.focus();
            }
            return false;
        }
        
        return true;
    });
});
</script>

<?php
include_once __DIR__ . '/../layout/footer.php';
?>
