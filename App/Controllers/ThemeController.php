<?php

namespace App\Controllers;

use App\Models\Theme;

class ThemeController {

    public function store(){
        if(empty($_GET['name_theme'])){
            echo json_encode(['is-good' => false]);
            return;
        }
        $newTheme = new Theme;
        $done = $newTheme->add(strip_tags($_GET['name_theme']));
        
        echo json_encode([
            'is-good' => $done,
            'id-theme' => $done ? Theme::getLastId() : ''
        ]);
        exit;
    }


    
}