<?php
spl_autoload_register();
use App\Controllers\TaskController;



$controller = new TaskController();

if(isset($_GET['action']) && $_GET['action'] === 'create') {
    $controller->create();
    exit;
} else if (isset($_GET['action']) && $_GET['action'] === 'add') {
    // $controller->checkForm();
        $controller->store();
        exit;
} else if(isset($_GET['action']) && $_GET['action'] === "done" && isset($_GET['idtask'])){
    $controller->done();
    exit;
}

else if(isset($_GET['action']) && $_GET['action'] === "delete" && isset($_GET['idtask'])){
    $controller->destroy();
    exit;
}

$controller->index();

exit;


// $ttl = "To do list";
// include_once "includes/_header.php";
// require_once "includes/_functions.php";

// $actions =[
//     0 => "Tâche terminée et archivée",
//     1 => "Tâche correctement supprimée",
//     2 => "Retour de la tâche effectué correctement",
//     3 => "Priorité correctement changée",
//     4 => "Tâche correctement modifiée",
//     5 => "Action annulée, une erreur s'est produite"
// ];

?>


        <!-- <div class="hidden" id="confirm-msg"> -->
            <?php
                // if(isset($_GET['action']) && $_GET['action'] == 0) echo $actions[0];
                // if(isset($_GET['action']) && $_GET['action'] == 1) echo $actions[1];
                // if(isset($_GET['action']) && $_GET['action'] == 2) echo $actions[2];
                // if(isset($_GET['action']) && $_GET['action'] == 3) echo $actions[3];
                // if(isset($_GET['action']) && $_GET['action'] == 4) echo $actions[4];
                // if(isset($_GET['action']) && $_GET['action'] == 5) echo $actions[5];
            ?>
        <!-- </div> -->



<script src="js/create.js?<?=time()?>"></script>
<script src="js/script.js?<?=time()?>"></script>
</body>

</html>
	