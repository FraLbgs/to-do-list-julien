<?php

namespace App\Controllers;

use App\Models\Task;
use App\Models\Theme;
use App\Views\TaskList;
use App\Views\TaskForm;
use App\Views\TaskItem;

class TaskController {

    public function index() {
        $task = new Task;
        $view = new TaskList([
            'taskList' => self::getHtmlFromArrayToDo(Task::displayTasks(), "tasks", "task")
        ]);
        $view->display();
    }

    public static function getHtmlFromArrayToDo(array $array, string $classUl = null, string $classLi = null): string {
        if ($classUl) $classUl = " class=\"$classUl\"";
        if ($classLi) $classLi = " class=\"$classLi\"";
        $valueToLi = function ($v){
            $taskItem = new TaskItem([
                'color' => $v['color'],
                'description' => $v['description'],
                'date' => $v['date_reminder'],
                'themes' => $v['themes'],
                'id_task' => $v['id_tasks'],
                'verifyDate' => self::verifyDate($v['date_reminder'])
            ]);
            // $taskItem = new TaskItem($v);
            return $taskItem->getHtml();
        };
        return "<ul$classUl>" . implode("", array_map($valueToLi, $array)) . "</ul>";
    }
    
    public static function verifyDate(string $d) :string {
        if($d === date("Y-m-d")) return "Dernier délai : Aujourd'hui";
        return "";
    }

// -------------------------------------------------------------------------------

    public function create() {
        new Theme;
            $view = new TaskForm([
                'title' => 'Créer une tâche',
                'description' => '',
                'date' => '',
                'color' => '',
                'test-description' => '',
                'test-date' => '',
                'test-color' => '',
                'display-themes' => self::displayThemes(Theme::askThemes())
            ]);
        $view->display();
    }
    
    public function checkForm() {
        new Theme;
        $view = new TaskForm([
            'title' => 'Créer une tâche',
            'description' => $_POST['description'],
            'date' => $_POST['date'],
            'color' => $_POST['color'],
            'test-description' => self::testDescription(),
            'test-date' => self::testDate(),
            'test-color' => self::testColor(),
            'display-themes' => self::displayThemes(Theme::askThemes())
        ]);
        $view->display();
    }

    public function displayThemes(array $array):string {
        $themes = "<fieldset id='fieldset'><legend>Choisissez vos thèmes</legend>";
        
        foreach($array as $t){
            $themes.= "<label>
            <input type='checkbox' name='theme[]' value='".$t['id_themes']."'>".$t['name_theme']."</label><br/>";
        }
        $themes .= "<input id='add-theme' type='text' name='new'> <button type ='button' class='btn-add-theme' id='btn-add-theme'>+</button>";
        
        return $themes."</fieldset>";
    }
    
    
    public function testDescription():string{
        if (isset($_POST['description']) && strlen($_POST['description']) > 255){
            return '*La description est trop longue';
        }
        return '';
    }

    public function testDate():string{
        if (isset($_POST['date']) && $_POST['date'] < date("Y-m-d")){
            return '*Date déjà dépassée ou non définie';
        }
        return '';
    }

    public function testColor():string{
        if (isset($_POST['color']) && preg_match('/^#[a-f0-9]{6}$/', $_POST['color']) !== 1){
            return '*Code hexa invalide';
        }
        return '';
    }

// -------------------------------------------------------------------------------


    public function store(){
        $newTask = new Task;
        if (isset($_POST['submit']) && self::verifyForm($_POST['description'], $_POST['date'], $_POST['color']) === true && isset($newTask->getMaxPrio()["max_prio"])) {
            if (isset($_POST['color'])) $_POST['color'] = str_replace("#", "", $_POST['color']);
            $newTask->addTask([
                "description" => strip_tags($_POST['description']),
                "date" => strip_tags($_POST['date']),
                "color" => strip_tags($_POST['color']),
                "priority" => intval(strip_tags($newTask->getMaxPrio()["max_prio"]) + 1),
                "id" => 1
            ]);
            $idtask =  Task::getLastId();
            $newTheme = new Theme;
            if (isset($_POST["theme"])) {
                foreach ($_POST["theme"] as $theme) {
                    var_dump($idtask);
                    $newTheme->addTheme(intval($theme), $idtask);
                }
            }
        }
    }

    public function verifyForm(string $desc, string $date, string $color) :bool{
        if(mb_strlen($desc) > 255 || $date < date("Y-m-d") || preg_match('/^#[a-f0-9]{6}$/', $color) !== 1){
          return false;
        }
        return true;
    }

    // -------------------------------------------------------------------------------


}




?>