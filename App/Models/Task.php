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
        $result = $query->fetchAll();
        return $result;
    }

    
}


?>