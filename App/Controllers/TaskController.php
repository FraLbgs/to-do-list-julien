<?php

namespace App\Controllers;

use App\Models\Task;
use App\Views\TaskList;
use App\Views\TaskAdd;
use App\Views\TaskItem;

class TaskController {

    public function index() {
        $task = new Task;
        $view = new TaskList([
            'taskList' => self::getHtmlFromArrayToDo(Task::displayTasks(), "tasks", "task")
        ]);
        $view->display();
    }

    public function create() {
        $view = new TaskAdd([]);
        $view->display();
    }

    public function add() {
        echo "Youpi tralala youpla";
    }

    

    public function testDescription():string{
        if (isset($_POST['description']) && strlen($_POST['description']) > 255){
            return '<span class=\'form-err\'>*La description est trop longue</span><br>';
        }
    }
    
    public static function getHtmlFromArrayToDo(array $array, string $classUl = null, string $classLi = null): string {
        
        if ($classUl) $classUl = " class=\"$classUl\"";
        if ($classLi) $classLi = " class=\"$classLi\"";
        $valueToLi = function ($v){
            $taskItem = new TaskItem($v);
            return $taskItem->getHtml();
        };
        return "<ul$classUl>" . implode("", array_map($valueToLi, $array)) . "</ul>";
        }
    
        public function verifyDate(string $d) :string {
            if($d === date("Y-m-d")) return "Dernier délai : Aujourd'hui";
            return "";
        }

}




?>