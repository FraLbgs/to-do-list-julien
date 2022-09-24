<?php

namespace App\Models;

class Task extends Model {

    public function getAll():array {
        $query = self::$connection->query("SELECT id_tasks, description, done, color, date_reminder, priority, id_users 
                         FROM tasks");
                         return $query->fetchAll();
    }


    public static function displayTasks():array {
        $query = self::$connection->prepare("SELECT id_tasks, description, color, date_reminder, GROUP_CONCAT(name_theme) AS themes
                FROM tasks 
                LEFT JOIN have_theme USING (id_tasks)
                LEFT JOIN themes USING (id_themes)
                WHERE done = 0
                GROUP BY id_tasks
                ORDER BY priority;");
        $query->execute();
        return $query->fetchAll();
        
    }

    public static function getMaxPrio() :array {
        $query = self::$connection->query("SELECT MAX(priority) AS max_prio FROM tasks WHERE id_users = 1;");
        return $query->fetch();
    }

    public static function addTask(array $array) :void {
        $query2 = self::$connection->prepare("INSERT INTO tasks (description, date_reminder, color, priority, id_users) VALUES
      (:description, :date, :color, :priority, :id);");
        $query2->execute($array);
    }

    public static function getLastId() :string {
        return self::$connection->lastInsertId();
    }

    public static function getPrio(int $id) :string {
        // var_dump($array);
        $query = self::$connection->prepare("SELECT priority FROM tasks WHERE id_tasks = :idtask AND done = 0;");
        $query->execute(["idtask" => $id]);
        $res = $query->fetch();
        return intval($res["priority"]);
    }

    public static function validateTask(int $id) :void {
        // var_dump($array);
        $prio = self::getPrio($id);
        $query1 = self::$connection->prepare("UPDATE tasks
        SET done = 1, priority = 0
        WHERE id_tasks = :idtask;");
        $isDone = $query1->execute(["idtask" => $id]);

        $query2 = self::$connection->prepare("UPDATE tasks
        SET priority = priority-1
        WHERE priority > $prio AND done = 0;");
        $isDone2 = $query2->execute();

        $action = "done";

    }

    public static function delete(int $id):void {
        $prio = self::getPrio($id);

        $query = self::$connection->prepare("DELETE FROM have_theme
        WHERE id_tasks = :idtask;");
        $query->execute(["idtask" => $id]);

        $query1 = self::$connection->prepare("DELETE FROM tasks
        WHERE id_tasks = :idtask;");
        $isDone = $query1->execute(["idtask" => $id]);

        $query2 = self::$connection->prepare("UPDATE tasks
        SET priority = priority-1
        WHERE priority > $prio AND done = 0;");
        $isDone2 = $query2->execute();

        $action = "delete";
    }

    public static function moveUp(int $id):void {
        $prio = self::getPrio($id);
        if($prio != 1){
            $query1 = self::$connection->prepare("UPDATE tasks
            SET priority = priority-1
            WHERE priority = $prio AND done = 0;");
            $isDone = $query1->execute();
            
            $query2 = self::$connection->prepare("UPDATE tasks
            SET priority = priority+1
            WHERE priority = $prio-1 AND id_tasks != :idtask AND done = 0;");
            $isDone2 = $query2->execute(["idtask" => $id]);
        
            $action = "up";
        }

    }

    public static function moveDown(int $id):void {
        $prio = self::getPrio($id);
        $prioMax = self::getMaxPrio($id);
        if($prio != $prioMax){
            $query1 = self::$connection->prepare("UPDATE tasks
            SET priority = priority+1
            WHERE priority = $prio AND done = 0;");
            $isDone = $query1->execute();
            
            $query2 = self::$connection->prepare("UPDATE tasks
            SET priority = priority-1
            WHERE priority = $prio+1 AND id_tasks != :idtask AND done = 0;");
            $isDone2 = $query2->execute(["idtask" => $id]);
        
            $action = "down";
        }

    }


    
}


?>