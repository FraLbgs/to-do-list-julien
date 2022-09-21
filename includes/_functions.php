<?php 

function getHtmlFromArrayToDo(array $array, string $classUl = null, string $classLi = null): string
{
    if ($classUl) $classUl = " class=\"$classUl\"";
    if ($classLi) $classLi = " class=\"$classLi\"";
    $valueToLi = fn ($v) => "<li$classLi style='background-color: #".$v['color']."'><div class='list-content'>
    <div class='left-content'><div class='up-down'>
      <a class='up' href='action.php?action=up&idtask=".$v['id_tasks']."'><img title='Monter la priorité' class='img-up-down' src='img/up.png' alt='up'></a>
      <a class='down' href='action.php?action=down&idtask=".$v['id_tasks']."'><img title='Descendre la priorité' class='img-up-down' src='img/down.png' alt='up'></a>
    </div>
    <div>
      <p class='desc'>".$v['description']."</p>
      <p class='desc'>".$v['date_reminder']."</p>
      <p class='desc'>".$v['themes']."</p>

    </div>
    </div>
    <div class='date-alert'>".verifyDate($v['date_reminder'])."</div>
    <div class='links'>
      <a class='link' href ='modify.php?action=modify&idtask=".$v['id_tasks']."'><img title='Modifier' class='img-link' src='img/modif.png' alt='modifier'></a>
      <a class='link' href ='action.php?action=delete&idtask=".$v['id_tasks']."'><img title='Supprimer' class='img-link' src='img/cross.png' alt='supprimer'></a>
      <a class='link' href ='action.php?action=done&idtask=".$v['id_tasks']."'><img title='Terminer' class='img-link' src='img/check.png' alt='terminer'></a>
    </div></li>";
    return "<ul$classUl>" . implode("", array_map($valueToLi, $array)) . "</ul>";
}

function getHtmlFromArrayDone(array $array, string $classUl = null, string $classLi = null): string
{
    if ($classUl) $classUl = " class=\"$classUl\"";
    if ($classLi) $classLi = " class=\"$classLi\"";
    $valueToLi = fn ($v) => "<li$classLi><div class='list-content'>
      <div class='left-content'>
        <a class='link' href ='action.php?action=return&idtask=".$v['id_tasks']."'><img title='Annuler la validation' class='img-link' src='img/back.png' alt='retour'></a>
        <p class='a-voir'>".$v['description']."</p>
      </div>
      <p class='a-voir'>".$v['date_reminder']."</p>
    </div></li>";
    return "<ul$classUl>" . implode("", array_map($valueToLi, $array)) . "</ul>";
}

function verifyForm(string $desc, string $date, string $color) :bool{
    if(strlen($desc) > 255 || $date < date("Y-m-d") || preg_match('/^[a-f0-9]{6}$/', $color) !== 1){
      return false;
    }
    return true;
  }

function verifyDate(string $d) :string{
  if($d === date("Y-m-d")) return "Dernier délai : Aujourd'hui";
  return "";
}

function returnMessage() : string {
  global $action;
  global $isDone;
  global $isDone2;

  if($isDone && $isDone2){
      if($action === "done") return "?action=0";
      else if($action === "delete") return "?action=1";
      else if($action === "return") return "?action=2";
      else return "?action=3";
  }
  else{
      return "?action=5";
  }
}

function displayThemes(array $array):string {
    $themes = "<fieldset id='fieldset'><legend>Choisissez vos thèmes</legend>";
    
    foreach($array as $t){
        $themes.= "<label>
        <input type='checkbox' name='theme[]' value='".$t['id_themes']."'>".$t['name_theme']."</label><br/>";
    }
    $themes .= "<input id='add-theme' type='text' name='new'> <button type ='button' class='btn-add-theme' id='btn-add-theme'>+</button>";
    
    return $themes."</fieldset>";
}

function recursiveStripTags(array|string $var) :array|string {
  if(!is_array($var)) return strip_tags($var);
  return array_map("recursiveStripTags", $var);  
}

?>