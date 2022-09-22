<?php
spl_autoload_register();

use App\Views\TaskAdd;

// var_dump($_POST);
$ttl = "Création de tâches";
include_once "includes/_header.php";
require_once "includes/_functions.php";
if (isset($_POST['color'])) $_POST['color'] = str_replace("#", "", $_POST['color']);

$queryThemes = $dbCo->query("SELECT id_themes, name_theme FROM themes;");
$themes = $queryThemes->fetchAll();

$desc = '';
if(isset($_POST['description']) && strlen($_POST['description']) > 255){
  $desc = "<span class='form-err'>*La description est trop longue</span><br>";
}


$date = '';
if (isset($_POST['date']) && $_POST['date'] < date("Y-m-d")) {
  $date = "<span class='form-err'>*Date déjà dépassée ou non définie</span><br>";
}


$taskAdd = new TaskAdd([
  'test-description' => $desc,
  'test-date' => '<?php if (isset($_POST[\'date\']) && $_POST[\'date\'] < date("Y-m-d")) echo "<span class=\'form-err\'>*Date déjà dépassée ou non définie</span><br>"; ?>',
  'test-color' => ' <?php if (isset($_POST[\'color\']) && preg_match(\'/^[a-f0-9]{6}$/\', $_POST[\'color\']) !== 1) echo "<span class=\'form-err\'>*Code hexa invalide</span><br>"; ?>',
  'display-themes' => '<?=displayThemes($themes);?>'
]);

$taskAdd->display();


// $_POST = recursiveStripTags($_POST);
// var_dump($_POST['theme']);


$query1 = $dbCo->query("SELECT MAX(priority) AS max_prio FROM tasks WHERE id_users = 1;");
$res = $query1->fetch();

if (isset($_POST['submit']) && verifyForm($_POST['description'], $_POST['date'], $_POST['color']) === true) {
  $query2 = $dbCo->prepare("INSERT INTO tasks (description, date_reminder, color, priority, id_users) VALUES
      (:description, :date, :color, :priority, :id);");
  $query2->execute([
    "description" => $_POST['description'],
    "date" => $_POST['date'],
    "color" => $_POST['color'],
    "priority" => $res['max_prio'] + 1,
    "id" => 1
  ]);
  $lastIndexTask = $dbCo->lastInsertId();
}

if(isset($_POST['theme'])){
  foreach($_POST['theme'] as $theme){
    $query2 = $dbCo->prepare("INSERT INTO have_theme (id_tasks, id_themes) VALUES
      (:idTask, :idTheme);");
    $query2->execute([
      "idTask" => $lastIndexTask,
      "idTheme" => intval($theme)
  ]);
  }
}

?>

<script src="js/create.js?<?=time()?>"></script>
</body>

</html>