<?php

namespace App\Views;

abstract class View {

    protected static string $filename;

    public static function getLiFromArray(array $data):string {
        return implode('', array_map(fn($d) => "<li>".$d."</li>", $data));
    }

    public static function getHtmlFromArrayToDo(array $array, string $classUl = null, string $classLi = null): string {
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

    function verifyDate(string $d) :string{
        if($d === date("Y-m-d")) return "Dernier délai : Aujourd'hui";
        return "";
      }
    
    private array $data;

    public function __construct(array $data){
        $this->data = $data;
    }

    // Getters & Setters

    public function getFilename():string {
        return static::$filename;
    }
    public function getData():array {
        return $this->data;
    }
    public function setData(array $data):void {
        $this->data = $data;
    }

    // Methods

    public function getContent():string|false {
        return file_get_contents($this->getFilename());
    }

    public function getHtml():string {
        return str_replace(array_map(fn($s)=>"{{".$s."}}", array_keys($this->getData())), array_values($this->getData()), $this->getContent());
    }
    
    public function display():void {
        echo $this->getHtml();
    }
}