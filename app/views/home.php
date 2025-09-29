<?php
$page_title = isset($page_title) ? $page_title : 'Truyện tự sáng tác - Cổng Light Novel - Đọc Light Novel';
$stories = isset($stories) ? $stories : [];
$comments = isset($comments) ? $comments : [];
$popular_stories = isset($popular_stories) ? $popular_stories : [];
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($page_title); ?></title>
    <!-- Bootstrap CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- Google Fonts: Roboto -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="/app/public/css/style.css" rel="stylesheet">
</head>
<body>
    <!-- Header -->
    <header class="py-4 border-bottom shadow-sm">
        <div class="container">
            <h1 class="h2 mb-0"><a href="#" class="text-decoration-none"><?php echo htmlspecialchars($page_title); ?></a></h1>
        </div>
    </header>

    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light border-bottom">
        <div class="container">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent" aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item"><a href="#" class="nav-link">Truyện Tự Sáng Tác</a></li>
                    <li class="nav-item"><a href="#" class="nav-link">Mới Cập Nhật</a></li>
                    <li class="nav-item"><a href="#" class="nav-link">Xem Nhiều</a></li>
                </ul>
                <!-- Thanh tìm kiếm -->
                <form class="d-flex me-3" role="search" action="#" method="GET">
                    <input class="form-control me-2" type="search" placeholder="Tìm truyện..." aria-label="Search" name="q">
                    <button class="btn btn-outline-primary" type="submit"><i class="bi bi-search"></i></button>
                </form>
                <!-- Nút đăng nhập -->
                <a href="#" class="btn btn-primary"><i class="bi bi-person me-1"></i> Đăng nhập</a>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container mt-5">
        <div class="row">
            <main class="col-md-8">
                <!-- Section: Mới cập nhật -->
                <section class="mb-5">
                    <h2 class="h3 mb-4 text-primary">Mới cập nhật</h2>
                    <div class="row">
                        <?php if (!empty($stories)): ?>
                            <?php foreach ($stories as $story): ?>
                            <div class="col-md-6 mb-4">
                                <div class="card h-100 shadow-sm story-card">
                                    <img src="/bccomai/public/images/<?php echo htmlspecialchars($story['image'] ?? 'default.jpg'); ?>" class="card-img-top" alt="Bìa truyện">
                                    <div class="card-body">
                                        <h3 class="h5 card-title"><a href="#" class="text-decoration-none"><?php echo htmlspecialchars($story['title']); ?></a></h3>
                                        <p class="text-muted mb-1">Người đăng: <a href="#"><?php echo htmlspecialchars($story['author']); ?></a></p>
                                        <p class="text-muted mb-1">Số từ: <?php echo number_format($story['word_count']); ?></p>
                                        <p class="card-text"><?php echo substr(htmlspecialchars($story['summary']), 0, 100) . '...'; ?></p>
                                        <a href="#" class="text-primary"><?php echo htmlspecialchars($story['latest_chapter']); ?></a>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p class="text-muted">Chưa có truyện mới.</p>
                        <?php endif; ?>
                    </div>
                </section>

                <!-- Section: Bình luận mới -->
                <section class="mb-5">
                    <h2 class="h3 mb-4 text-primary">Bình luận mới</h2>
                    <?php if (!empty($comments)): ?>
                        <?php foreach ($comments as $comment): ?>
                        <div class="card mb-3 shadow-sm">
                            <div class="card-body">
                                <p class="mb-1"><strong><?php echo htmlspecialchars($comment['user']); ?>:</strong> <a href="#"><?php echo htmlspecialchars($comment['story_title']); ?></a></p>
                                <p class="text-muted"><?php echo htmlspecialchars($comment['comment_text']); ?></p>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="text-muted">Chưa có bình luận.</p>
                    <?php endif; ?>
                </section>

                <!-- Section: Xem nhiều -->
                <section>
                    <h2 class="h3 mb-4 text-primary">Xem nhiều</h2>
                    <h3 class="h5 mb-3">Truyện sáng tác mới</h3>
                    <div class="row">
                        <?php if (!empty($popular_stories)): ?>
                            <?php foreach ($popular_stories as $story): ?>
                            <div class="col-md-6 mb-4">
                                <div class="card h-100 shadow-sm">
                                    <div class="card-body">
                                        <h4 class="h6 card-title"><a href="#" class="text-decoration-none"><?php echo htmlspecialchars($story['title']); ?></a></h4>
                                        <p class="card-text"><?php echo substr(htmlspecialchars($story['summary']), 0, 80) . '...'; ?></p>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p class="text-muted">Chưa có truyện phổ biến.</p>
                        <?php endif; ?>
                    </div>
                </section>
            </main>

            <!-- Sidebar -->
            <aside class="col-md-4">
                <div class="card mb-4 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">Thể loại nổi bật</h5>
                        <p>
                            <a href="#">Fantasy</a> |
                            <a href="#">Isekai</a> |
                            <a href="#">Adventure</a>
                        </p>
                    </div>
                </div>
            </aside>
        </div>
    </div>

    <!-- Footer -->
    <footer class="py-4 mt-5">
        <div class="container">
            <p class="mb-0 text-center">&copy; 2025 Cổng Light Novel. All rights reserved.</p>
        </div>
    </footer>

    <!-- Bootstrap JS CDN -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <!-- Custom JS -->
    <script src="/app/public/js/script.js"></script>
</body>
</html>