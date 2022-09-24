<?php

namespace App\Models;

class Theme extends Model {

    public static function askThemes() :array {
        $query = self::$connection->query("SELECT id_themes, name_theme FROM themes;");
        return $query->fetchAll();
    }

    public static function addTheme(int $idTheme, int $idTask) :array{
        $query = self::$connection->prepare("INSERT INTO have_theme (id_tasks, id_themes) VALUES
            ($idTask, :idTheme);");
        $query->execute([
            "idTheme" => $idTheme
        ]);
        return $query->fetchAll();
    }

}


?>