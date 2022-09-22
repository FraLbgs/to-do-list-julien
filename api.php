<?php
// $ttl = "test";
// require_once "includes/_functions.php";

spl_autoload_register();
use App\Controllers\TaskController;

$apiController = new TaskController();

if (isset($_GET['idtask'])){
    $query = $dbCo->prepare("SELECT priority FROM tasks WHERE id_tasks = :idtasks AND done = 0;");
    $query->execute([
        "idtasks" => $_GET['idtask']
    ]);
    $res = $query->fetch();
    $prio = $res["priority"];
}

// var_dump($_GET);
if(isset($_GET['action']) && $_GET['action'] === "done" && isset($_GET['idtask'])){

    $query1 = $dbCo->prepare("UPDATE tasks
    SET done = 1, priority = 0
    WHERE id_tasks = :idtask;");
    $isDone = $query1->execute([
        "idtask" => $_GET['idtask']
    ]);

    $query2 = $dbCo->prepare("UPDATE tasks
    SET priority = priority-1
    WHERE priority > $prio AND done = 0;");
    $isDone2 = $query2->execute();

    $action = "done";

    var_dump($isDone, $isDone2);
}

else if(isset($_GET['action']) && $_GET['action'] === "delete" && isset($_GET['idtask'])){

    $query1 = $dbCo->prepare("DELETE FROM tasks
    WHERE id_tasks = :idtask;");
    $isDone = $query1->execute([
        "idtask" => $_GET['idtask']
    ]);

    $query2 = $dbCo->prepare("UPDATE tasks
    SET priority = priority-1
    WHERE priority > $prio AND done = 0;");
    $isDone2 = $query2->execute();

    $action = "delete";

}


else if(isset($_GET['action']) && $_GET['action'] === "up" && isset($_GET['idtask'])){

    if($prio != 1){
        $query1 = $dbCo->prepare("UPDATE tasks
        SET priority = priority-1
        WHERE priority = $prio AND done = 0;");
        $isDone = $query1->execute();
        
        $query2 = $dbCo->prepare("UPDATE tasks
        SET priority = priority+1
        WHERE priority = $prio-1 AND id_tasks != :idtask AND done = 0;");
        $isDone2 = $query2->execute([
            "idtask" => $_GET['idtask']
        ]);
    
        $action = "up";
    }
    
}


else if(isset($_GET['action']) && $_GET['action'] === "down" && isset($_GET['idtask'])){

    $queryMP = $dbCo->prepare("SELECT MAX(priority) AS max_prio FROM tasks WHERE id_users = 1;");
    $queryMP->execute();
    $MP = $queryMP->fetchColumn();

    if($prio != $MP){
        $query1 = $dbCo->prepare("UPDATE tasks
        SET priority = priority+1
        WHERE priority = $prio AND done = 0;");
        $isDone = $query1->execute();

        $query2 = $dbCo->prepare("UPDATE tasks
        SET priority = priority-1
        WHERE priority = $prio+1 AND id_tasks != :idtask AND done = 0;");
        $isDone2 = $query2->execute([
            "idtask" => $_GET['idtask']
        ]);

        $action = "down";
    }

}

else if(isset($_GET['action']) && $_GET['action'] === "return" && isset($_GET['idtask'])){

$query1 = $dbCo->prepare("SELECT MAX(priority) AS max_prio FROM tasks WHERE id_users = 1;");
$isDone = $query1->execute();
$res = $query1->fetch();
// var_dump($res);

$query2 = $dbCo->prepare("UPDATE tasks
SET done = 0, priority = :priority
WHERE id_tasks = :idtask;");
$isDone2 = $query2->execute([
"priority" => $res['max_prio']+1,
"idtask" => $_GET['idtask']
]);

$action = "return";

}

else if(isset($_GET['action']) && $_GET['action'] === "add_theme" && isset($_GET['name_theme'])){

if(($_GET['name_theme']) != "" ) {
    $query = $dbCo->prepare("INSERT INTO themes (name_theme) VALUES (:name_theme);");
    $result = $query->execute([
        "name_theme" => ($_GET['name_theme'])
    ]);
    $lastIndexTheme= $dbCo->lastInsertId();
};

$dataThemes = [
    'is-good' => $result,
    'id-theme' => $lastIndexTheme
];
header('Content-Type: application/json; charset=utf-8');
echo json_encode($dataThemes);
exit;

}
$res = returnMessage();

if(isset($action)){
    header("location:index.php$res");
exit;
}
?>