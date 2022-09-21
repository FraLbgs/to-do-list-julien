<?php
$ttl = "test";
include_once "includes/_header.php";
require_once "includes/_functions.php";


$query = $dbCo->prepare("SELECT description, date_reminder, color FROM tasks WHERE id_tasks =".$_GET['idtask'].";");
$query->execute();
$result = $query->fetch();
$result['color'] = "#".$result['color'];
// var_dump($_POST);
if(isset($_POST['color'])) $_POST['color'] = str_replace("#", "", $_POST['color']);

if(isset($_POST['submit']) && verifyForm($_POST['description'], $_POST['date'], $_POST['color']) === true){
    $query = $dbCo->prepare("UPDATE  tasks
    SET description = :description, date_reminder = :date, color = :color
    WHERE id_tasks = :idTask;");
    $query->execute([
        "description" => $_POST['description'],
        "date" => $_POST['date'],
        "color" => $_POST['color'],
        "idTask" => intval($_GET['idtask'])
    ]);
    var_dump($query);
    header(("location:index.php?action=4"));
    exit;
  }
  // else{echo "erreur";}

?>

<div class="container">
    <h2 class="sub-ttl">Veuillez rentrer les données : </h2>
    <form class="form" method="post" action="#">
        <div class="field">
          <label class="label">Description : <?php if(isset($_POST['description']) && strlen($_POST['description']) > 255) echo "<span class='form-err'>*La description est trop longue</span><br>";?><br>
          <input class="input" type="text" name="description" required value="<?=$result['description']?>"></label>
      </div>
        <div class="field"><label class="label">Date de rappel : <?php if(isset($_POST['date']) && $_POST['date'] < date("Y-m-d")) echo "<span class='form-err'>*Date déjà dépassée ou non définie</span><br>";?><br>
          <input class="input" type="date" name="date" required value="<?=$result['date_reminder']?>"></label>
      </div>
        <div class="field"><label class="label">Couleur : <?php if(isset($_POST['color']) && preg_match('/^[a-f0-9]{6}$/', $_POST['color']) !== 1) echo "<span class='form-err'>*Code hexa invalide</span><br>";?><br>
        <input class="input" type="color" name="color" required value="<?=$result['color']?>"></label>
      </div>
        <!-- <div class="field"><label class="label">Priorité : (chiffre entre 1 et 5) : <?php if(isset($_POST['priority']) && preg_match('/^[1-5]{1}$/', $_POST['priority']) !== 1) echo "<span class='form-err'>*Veuillez entrer un chiffre entre 1 et 5</span><br>";?><br> 
        <input class="input" type="number" name="priority" required value="<?=$result['priority']?>"></label>
      </div> -->
      <!-- <div class="field"><label class="label">Votre identifiant : <br>
        <input class="input" type="number" name="id"></label>
      </div> -->




      <div class="field">
          <input class="input" type="submit" name="submit" value="Valider les données">
      </div>
    </form>
</div>

