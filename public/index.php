<?php
    session_start();
    
    require_once '../app/core/App.php';
    require_once '../app/core/Controller.php';
    require_once '../app/core/Database.php';
    require_once '../app/core/Schema.php';
    require_once '../config/config.php';
    
    // Require AuthController để các controller khác có thể sử dụng
    require_once '../app/controllers/AuthController.php';
    
    Schema::run();
    $app = new App();