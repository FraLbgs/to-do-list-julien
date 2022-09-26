<?php

namespace App\Models;

class Theme extends Model {

    public static function askThemes() :array {
        $query = self::$connection->query("SELECT id_themes, name_theme FROM themes;");
        return $query->fetchAll();
    }

    public static function linkTaskToTheme(int $idTheme, int $idTask) :array{
        $query = self::$connection->prepare("INSERT INTO have_theme (id_tasks, id_themes) VALUES
            (:idTask, :idTheme);");
        $query->execute([
            "idTask" => $idTask,
            "idTheme" => $idTheme
        ]);
        return $query->fetchAll();
    }

    public static function getThemes(int $id) :array {
        $query = self::$connection->prepare("SELECT id_themes FROM have_theme
            WHERE id_tasks = :id;");
        $query->execute(['id' => $id]);
        return $query->fetchAll();
    }

    public static function add(string $nameTheme) :bool {
        $query = self::$connection->prepare("INSERT INTO themes (name_theme) VALUES (:name_theme);");
        return $query->execute([
            "name_theme" => ($nameTheme)
        ]);
        
    }

}


?>