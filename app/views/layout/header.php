<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Trang chủ</title>

<?php
$cssDir = __DIR__ . '/../../../public/css'; // đường dẫn tuyệt đối đến thư mục css

// Lấy danh sách tất cả các file CSS
$cssFiles = glob($cssDir . '/*.css');

foreach ($cssFiles as $cssFile) {
    // Lấy đường dẫn tương đối để chèn vào HTML
    $cssPath = 'css/' . basename($cssFile);
    echo '<link rel="stylesheet" href="' . htmlspecialchars($cssPath) . '">' . PHP_EOL;
}
?>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<body>
    <div class="container-fluid p-0 m-0 w-100 vh-100">
        <div class="header"></div>