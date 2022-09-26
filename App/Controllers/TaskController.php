<?php

namespace App\Controllers;

use App\Models\Message;
use App\Models\Task;
use App\Models\Theme;
use App\Views\TaskList;
use App\Views\TaskForm;
use App\Views\TaskHistory;
use App\Views\TaskItem;
use App\Views\TaskItemDone;

class TaskController {

    public function index() {
        new Task;
        $message = new Message;
        $view = new TaskList([
            'taskList' => self::getHtmlFromArrayToDo(Task::displayTasks(), "tasks", "task"),
            'message' => isset($_GET['message']) ? $message->get($_GET['message']) : ''
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

    public function create() :void {
        new Theme;
        $message = new Message;
        session_start();
        $_SESSION['myToken'] = md5(uniqid(mt_rand(), true));
            $view = new TaskForm([
                'message' => isset($_GET['message']) ? $message->get($_GET['message']) : '',
                'title' => 'Créer une tâche',
                'action' => "index.php?action=add&message=createKO",
                'description' => '',
                'date' => '',
                'color' => '',
                'test-description' => '',
                'test-date' => '',
                'test-color' => '',
                'display-themes' => self::displayThemes(Theme::askThemes()),
                'token' => $_SESSION['myToken'],
                'time' => time()
            ]);
        $view->display();
    }

    public function displayThemes(array $themesList, array $idthemes = null):string {
        $themes = "<fieldset id='fieldset'><legend>Choisissez vos thèmes</legend>";
        foreach($themesList as $t){
            $checked = (isset($_POST['theme']) && in_array($t['id_themes'],$_POST['theme'])) ? 'checked' : '';
            $checkedModify = ($idthemes !== null && (in_array($t['id_themes'],$idthemes))) ? 'checked' : '';
            $themes.= "<label>
            <input type='checkbox' name='theme[]' value='".$t['id_themes']."' $checked $checkedModify >".$t['name_theme']."</label><br/>";
        }
        $themes .= "<input id='add-theme' type='text' name='new'> <button type ='button' class='btn-add-theme' id='btn-add-theme'>+</button>";
        
        return $themes."</fieldset>";
    }
    
    
    // -------------------------------------------------------------------------------
    
    
    public function store() :void {
        session_start();
        $message = new Message;
        // $this->checkForm();
        if(!isset($_SERVER['HTTP_REFERER']) || strpos($_SERVER['HTTP_REFERER'], 'localhost/TodoListPOO') === false){
            header('location:index.php?err=referer');
            exit;
        }
        if(!isset($_POST['token']) || !isset($_SESSION['myToken']) || $_POST['token'] !== $_SESSION['myToken']){
            header('location:index.php?err=csrf');
            exit;
        }
        $newTask = new Task;
        if(!(self::verifyForm())){
            $view = new TaskForm([
                'message' => isset($_GET['message']) ? $message->get($_GET['message']) : '',
                'title' => 'Créer une tâche',
                'action' => "index.php?action=add",
                'description' => $_POST['description'],
                'date' => $_POST['date'],
                'color' => $_POST['color'],
                'test-description' => $this->testDescription(),
                'test-date' => $this->testDate(),
                'test-color' => $this->testColor(),
                'display-themes' => self::displayThemes(Theme::askThemes()),
                'token' => $_SESSION['myToken']
            ]);
            $view->display();
            exit;
        }
        
        if (isset($_POST['submit']) && ($newTask->getMaxPrio()) !== null) {
            if (isset($_POST['color'])) $_POST['color'] = str_replace("#", "", $_POST['color']);
            $newTask->addTask([
                "description" => strip_tags($_POST['description']),
                "date" => strip_tags($_POST['date']),
                "color" => strip_tags($_POST['color']),
                "priority" => strip_tags($newTask->getMaxPrio()) + 1,
                "id" => 1
            ]);
            $idtask =  Task::getLastId();
            $newTheme = new Theme;
            if (isset($_POST["theme"])) {
                foreach ($_POST["theme"] as $theme) {
                    var_dump($idtask);
                    $newTheme->linkTaskToTheme(intval($theme), $idtask);
                }
            }
            header("location:index.php?action=create&message=createOK");
            exit;
        }
    }
    
    public function verifyForm() :bool{
        $test = [];
        $test['description'] = $this->testDescription();
        $test['color'] = $this->testColor();
        $test['date'] = $this->testDate();
        return ($test['description'] === '' && $test['color'] === '' && $test['date'] === '');
    }

    public function testDescription():string{
        if (isset($_POST['description']) && mb_strlen($_POST['description']) > 255){
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

    public function done() :void {
        new Message;
        if (isset($_GET['idtask'])){
            $newTask = new Task;
            $newTask->validateTask($_GET['idtask']);
            header("location:index.php?message=doneOK");
        }
    }

    public function destroy() :void {
        if (isset($_GET['idtask'])){
            $newTask = new Task;
            $newTask->delete($_GET['idtask']);
            header("location:index.php?message=suppOK");
        }
    }

    public function up() :void {
        if (isset($_GET['idtask'])){
            $newTask = new Task;
            $newTask->moveUp($_GET['idtask']);
            header("location:index.php");
        }
    }

    public function down() :void {
        if (isset($_GET['idtask'])){
            $newTask = new Task;
            $newTask->moveDown($_GET['idtask']);
            header("location:index.php");
        }
    }

    public function modify() :void {
        session_start();
        $newTask = new Task;
        $newTheme = new Theme;
        $message = new Message;
        $idThemes = [];
        $themeModif = $newTheme->getThemes($_GET['idtask']);
        $taskInfo = $newTask->getTaskInfo($_GET['idtask']);
        if(!isset($_POST['submit'])){
            if(sizeof($themeModif) !== 0){
                foreach($themeModif as $theme){
                    $idThemes[] = $theme['id_themes'];
                }
            }
            // var_dump($idThemes);
            $view = new TaskForm([
                'title' => 'Modifier une tâche',
                'message' => '',
                'action' => "index.php?action=modify&idtask=".$_GET['idtask']."&message=modifKO",
                'description' => $taskInfo['description'],
                'date' => $taskInfo['date_reminder'],
                'color' => $taskInfo['color'],
                'test-description' => '',
                'test-date' => '',
                'test-color' => '',
                'display-themes' => self::displayThemes(Theme::askThemes(), $idThemes),
                'token' => $_SESSION['myToken'],
            ]);
            $view->display();
            exit;
        }
        if(!(self::verifyForm())){
            $view = new TaskForm([
                'title' => 'Modifier une tâche',
                'message' => isset($_GET['message']) ? $message->get($_GET['message']) : '',
                'action' => "index.php?action=modify&idtask=".$_GET['idtask'],
                'description' => $_POST['description'],
                'date' => $_POST['date'],
                'color' => $_POST['color'],
                'test-description' => $this->testDescription(),
                'test-date' => $this->testDate(),
                'test-color' => $this->testColor(),
                'display-themes' => self::displayThemes(Theme::askThemes())
            ]);
            $view->display();
            exit;
        }
        if (isset($_POST['submit']) && isset($_GET['idtask'])){
            if (isset($_POST['color'])) $_POST['color'] = str_replace("#", "", $_POST['color']);
            $newTask->update([
                "description" => strip_tags($_POST['description']),
                "date" => strip_tags($_POST['date']),
                "color" => strip_tags($_POST['color']),
                "idTask" => intval($_GET['idtask'])
            ]);
            $idtask =  $_GET['idtask'];
            $newTheme = new Theme;
            if (isset($_POST["theme"])) {
                foreach ($_POST["theme"] as $theme) {
                    $newTheme->linkTaskToTheme($theme, $idtask);
                }
            }
            header("location:index.php?message=modifOK");
            exit;
        }

    }

    public function return() :void {
        if (isset($_GET['idtask'])){
            $newTask = new Task;
            $newTask->undone($_GET['idtask']);
            header("location:index.php?message=returnOK");
        }
    }

    public function displayHistory() :void {
        new Task;
        $view = new TaskHistory([
            'taskList' => self::getHtmlFromArrayDone(Task::displayTasksDone())
        ]);
        $view->display();
    }

    public static function getHtmlFromArrayDone(array $array) :string {
        $valueToLi = function ($v){
            $taskItemDone = new TaskItemDone([
                'id_task' => $v['id_tasks'],
                'description' => $v['description'],
                'date' => $v['date_reminder']
            ]);
            return $taskItemDone->getHtml();
        };
        return "<ul class='done-ul'>" . implode("", array_map($valueToLi, $array)) . "</ul>";
    }

}




?>