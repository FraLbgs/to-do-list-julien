<?php

namespace App\Models;

class Message extends Model {

    public static function get(string $slug) :string {
        $query = self::$connection->prepare("SELECT message FROM message WHERE slug = :slug");
        $query->execute(['slug' => $slug]);
        return $query->fetchColumn();
    }

}