<?php
    require_once '../app/core/App.php';
    require_once '../app/core/Controller.php';
    require_once '../app/core/Database.php';
    require_once '../app/core/Schema.php';
    require_once '../config/config.php';
    
    Schema::run();
    $app = new App();