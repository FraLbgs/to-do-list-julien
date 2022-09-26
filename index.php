<?php
spl_autoload_register();
use App\Controllers\TaskController;

$controller = new TaskController();

if(isset($_GET['action']) && $_GET['action'] === 'create') {
    $controller->create();
    exit;
} else if (isset($_GET['action']) && $_GET['action'] === 'add') {
    $controller->store();
    exit;
} else if(isset($_GET['action']) && $_GET['action'] === "done" && isset($_GET['idtask'])){
    $controller->done();
    exit;
} else if(isset($_GET['action']) && $_GET['action'] === "delete" && isset($_GET['idtask'])){
    $controller->destroy();
    exit;
} else if(isset($_GET['action']) && $_GET['action'] === "up" && isset($_GET['idtask'])){
    $controller->up();
    exit;
} else if(isset($_GET['action']) && $_GET['action'] === "down" && isset($_GET['idtask'])){
    $controller->down();
    exit;
} else if(isset($_GET['action']) && $_GET['action'] === "modify" && isset($_GET['idtask'])){
    $controller->modify();
    exit;
} else if(isset($_GET['action']) && $_GET['action'] === "return" && isset($_GET['idtask'])){
    $controller->return();
    exit;
} else if(isset($_GET['action']) && $_GET['action'] === "history"){
    $controller->displayHistory();
    exit;
} 

$controller->index();

exit?>

