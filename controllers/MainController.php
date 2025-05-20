<?php
require_once "BaseCrazyTwigController.php";

class MainController extends BaseCrazyTwigController {
    public $title = "Тачки";
    public $template = "main.twig";
  
   public function getContext(): array
{
    $context = parent::getContext();

    if (isset($_GET['type'])){
        $type = $_GET['type']; // PHP автоматически декодирует URL-параметры
        $query = $this->pdo->prepare("SELECT * FROM crazy_objects WHERE type = :type");
        $query->bindValue("type", $type);
        $query->execute();
    } else {
        $query = $this->pdo->query("SELECT * FROM crazy_objects");
    }
    
    $context['crazy_objects'] = $query->fetchAll();
    $context['page_history'] = isset($_SESSION['page_history']) ? $_SESSION['page_history'] : [];
    return $context;
}
}