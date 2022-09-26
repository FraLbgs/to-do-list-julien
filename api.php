<?php
spl_autoload_register();
use App\Controllers\ThemeController;

header('Content-Type: application/json; charset=utf-8');

if(isset($_GET['action']) && $_GET['action'] === "add_theme" && isset($_GET['name_theme'])){
    $controller = new ThemeController;

    $controller->store();
    
    }
