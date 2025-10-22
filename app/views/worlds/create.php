<?php
// Start session if not already (safe here to allow flash messages)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include_once __DIR__ . '/../layout/header.php';
include_once __DIR__ . '/../layout/navigation.php';

// Lấy dữ liệu form cũ nếu có lỗi
$oldName = $_SESSION['form_data']['name'] ?? '';
$oldArticle = $_SESSION['form_data']['article'] ?? '';

// Lấy thông báo lỗi
$errors = $_SESSION['error_messages'] ?? [];

// Xóa session sau khi lấy
unset($_SESSION['form_data']);
unset($_SESSION['error_messages']);
?>

<div class="main-content">
    <div class="container py-5">
        <div class="row">
            <div class="col-md-10 offset-md-1">
                
                <!-- Breadcrumb -->
                <nav aria-label="breadcrumb" class="mb-4">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="?url=world">
                                <i class="fas fa-home"></i> Danh sách thế giới
                            </a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            Tạo thế giới mới
                        </li>
                    </ol>
                </nav>

                <!-- Card Form -->
                <div class="card shadow-lg border-0">
                    <div class="card-header bg-gradient-primary text-white py-4">
                        <h2 class="mb-0">
                            <i class="fas fa-plus-circle"></i> Tạo thế giới mới
                        </h2>
                        <p class="mb-0 mt-2 opacity-90">
                            Bắt đầu xây dựng thế giới ảo của riêng bạn
                        </p>
                    </div>
                    
                    <div class="card-body p-5">
                        <!-- Hiển thị lỗi nếu có -->
                        <?php if (!empty($errors)): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <h5 class="alert-heading">
                                    <i class="fas fa-exclamation-triangle"></i> Có lỗi xảy ra!
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

                        <!-- Form -->
                        <form method="POST" action="" id="createWorldForm">
                            
                            <!-- Tên thế giới -->
                            <div class="form-group">
                                <label for="name" class="font-weight-bold">
                                    <i class="fas fa-tag"></i> Tên thế giới 
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text" 
                                       class="form-control form-control-lg <?= !empty($errors) && empty($oldName) ? 'is-invalid' : '' ?>" 
                                       id="name" 
                                       name="name" 
                                       value="<?= htmlspecialchars($oldName) ?>"
                                       placeholder="Ví dụ: Vương Quốc Eldoria, Đế Chế Cyberpunk 2199..."
                                       maxlength="255"
                                       required
                                       autofocus>
                                <small class="form-text text-muted">
                                    <i class="fas fa-info-circle"></i>
                                    Tên thế giới nên ngắn gọn (3-255 ký tự), dễ nhớ và có tính gợi mở
                                </small>
                                <div class="invalid-feedback">
                                    Vui lòng nhập tên thế giới (tối thiểu 3 ký tự)
                                </div>
                            </div>

                            <!-- Bài viết mô tả -->
                            <div class="form-group">
                                <label for="article" class="font-weight-bold">
                                    <i class="fas fa-file-alt"></i> Bài viết mô tả chi tiết
                                </label>
                                <textarea class="form-control" 
                                          id="article" 
                                          name="article" 
                                          rows="15"
                                          placeholder="Nhập mô tả chi tiết về thế giới của bạn...

📝 GỢI Ý NỘI DUNG:

🌍 Tổng quan
- Giới thiệu chung về thế giới
- Thể loại (Fantasy, Sci-fi, Hiện đại, Lịch sử...)
- Đặc điểm nổi bật

📜 Lịch sử
- Quá khứ và nguồn gốc
- Các sự kiện quan trọng
- Timeline phát triển

🗺️ Địa lý & Khí hậu
- Vị trí, địa hình
- Khí hậu, thời tiết
- Các vùng đất chính

👥 Xã hội & Chính trị
- Cấu trúc xã hội
- Hệ thống chính trị
- Các thế lực quyền lực

🎭 Văn hóa & Tôn giáo
- Tín ngưỡng, tôn giáo
- Phong tục tập quán
- Ngôn ngữ, văn tự

⚔️ Hệ thống quyền lực
- Ma thuật/Công nghệ/Vũ khí
- Quy tắc và hạn chế
- Các năng lực đặc biệt

🔮 Bí ẩn & Chưa giải đáp
- Những điều chưa rõ
- Truyền thuyết, huyền thoại
- Các mối đe dọa tiềm ẩn"><?= htmlspecialchars($oldArticle) ?></textarea>
                                <small class="form-text text-muted">
                                    <i class="fas fa-lightbulb"></i>
                                    Viết theo phong cách Wikipedia - chi tiết, có cấu trúc, dễ hiểu. Bạn có thể để trống và bổ sung sau.
                                </small>
                            </div>

                            <!-- Preview Character Count -->
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <div class="card bg-light">
                                        <div class="card-body py-2">
                                            <small class="text-muted">
                                                <i class="fas fa-keyboard"></i> Tên thế giới: 
                                                <strong id="nameCount">0</strong>/255 ký tự
                                            </small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card bg-light">
                                        <div class="card-body py-2">
                                            <small class="text-muted">
                                                <i class="fas fa-align-left"></i> Bài viết: 
                                                <strong id="articleCount">0</strong> ký tự
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Buttons -->
                            <div class="form-group mb-0">
                                <button type="submit" class="btn btn-primary btn-lg px-5">
                                    <i class="fas fa-save"></i> Tạo thế giới
                                </button>
                                <a href="?url=world" class="btn btn-secondary btn-lg px-5">
                                    <i class="fas fa-times"></i> Hủy
                                </a>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Card Hướng dẫn -->
                <div class="row mt-4">
                    <div class="col-md-6">
                        <div class="card border-info h-100">
                            <div class="card-header bg-info text-white">
                                <h5 class="mb-0">
                                    <i class="fas fa-lightbulb"></i> Mẹo xây dựng thế giới
                                </h5>
                            </div>
                            <div class="card-body">
                                <ul class="mb-0">
                                    <li>Bắt đầu với <strong>khái niệm chính</strong> của thế giới</li>
                                    <li>Xác định <strong>thể loại</strong> rõ ràng (Fantasy, Sci-fi...)</li>
                                    <li>Thiết lập <strong>quy tắc</strong> của thế giới và tuân theo chúng</li>
                                    <li>Tạo <strong>lịch sử</strong> và timeline hợp lý</li>
                                    <li>Phát triển <strong>xung đột</strong> và mâu thuẫn thú vị</li>
                                    <li>Để lại <strong>không gian mở</strong> cho sự phát triển</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card border-success h-100">
                            <div class="card-header bg-success text-white">
                                <h5 class="mb-0">
                                    <i class="fas fa-check-circle"></i> Những điều nên có
                                </h5>
                            </div>
                            <div class="card-body">
                                <ul class="mb-0">
                                    <li><strong>Tính nhất quán:</strong> Thế giới phải logic, không mâu thuẫn</li>
                                    <li><strong>Chiều sâu:</strong> Nhiều tầng lớp văn hóa, lịch sử</li>
                                    <li><strong>Tính độc đáo:</strong> Có yếu tố riêng biệt, đặc trưng</li>
                                    <li><strong>Khả năng mở rộng:</strong> Có thể thêm nhân vật, sự kiện</li>
                                    <li><strong>Cảm xúc:</strong> Tạo kết nối với độc giả</li>
                                    <li><strong>Hệ thống rõ ràng:</strong> Ma thuật, công nghệ có quy tắc</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<style>
.bg-gradient-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.opacity-90 {
    opacity: 0.9;
}

.card {
    border-radius: 15px;
    overflow: hidden;
}

.card-header {
    border-bottom: 3px solid rgba(0,0,0,0.1);
}

.form-control:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
}

.btn-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
}

.btn-primary:hover {
    background: linear-gradient(135deg, #5568d3 0%, #6a3f8f 100%);
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
}

.breadcrumb {
    background-color: #f8f9fa;
    border-radius: 10px;
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
        language: 'vi',
        placeholder: 'Nhập mô tả chi tiết về thế giới của bạn...'
    })
    .then(newEditor => {
        editor = newEditor;
        
        // Update character count on change
        editor.model.document.on('change:data', () => {
            updateCharCount();
        });
        
        // Initial count
        updateCharCount();
    })
    .catch(error => {
        console.error('CKEditor initialization error:', error);
    });

// Character counter
document.addEventListener('DOMContentLoaded', function() {
    const nameInput = document.getElementById('name');
    const articleInput = document.getElementById('article');
    const nameCount = document.getElementById('nameCount');
    const articleCount = document.getElementById('articleCount');
    
    function updateCharCount() {
        // Get plain text from CKEditor
        if (editor) {
            const data = editor.getData();
            const plainText = data.replace(/<[^>]*>/g, ''); // Strip HTML tags
            articleCount.textContent = plainText.length;
        } else {
            articleCount.textContent = articleInput.value.length;
        }
    }
    
    window.updateCharCount = updateCharCount;
    
    // Update count on load
    nameCount.textContent = nameInput.value.length;
    updateCharCount();
    
    // Update count on input
    nameInput.addEventListener('input', function() {
        nameCount.textContent = this.value.length;
    });
    
    // Form validation
    document.getElementById('createWorldForm').addEventListener('submit', function(e) {
        const name = nameInput.value.trim();
        
        // Get content from CKEditor and update textarea
        if (editor) {
            articleInput.value = editor.getData();
        }
        
        if (name.length < 3) {
            e.preventDefault();
            nameInput.classList.add('is-invalid');
            nameInput.focus();
            return false;
        }
        
        // Show loading state
        const submitBtn = this.querySelector('button[type="submit"]');
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Đang tạo...';
        submitBtn.disabled = true;
    });
});
</script>

<?php
include_once __DIR__ . '/../layout/footer.php';
?>
