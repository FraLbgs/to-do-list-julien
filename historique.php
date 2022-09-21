<?php
$ttl = "Historique";
include_once "includes/_header.php";
require_once "includes/_functions.php";

$query2 = $dbCo->prepare("SELECT id_tasks, description, date_reminder FROM tasks WHERE done = 1 ORDER BY date_reminder;");
$query2->execute();
$result2 = $query2->fetchAll();

?>

<div class="tasks-done">
    <h2 class="sub-ttl">Tâches effectuées : </h2>
    <?= (getHtmlFromArrayDone($result2, "done-ul", "done-li")) ?>
</div>