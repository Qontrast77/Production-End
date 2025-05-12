<?php
require_once "BaseCrazyTwigController.php";

class MainController extends BaseCrazyTwigController {
    public $title = "Тачки";
    public $template = "main.twig";
   // добавим метод getContext()
   public function getContext(): array
   {
       $context = parent::getContext();

       if (isset($_GET['type'])){
         $query=$this->pdo->prepare("SELECT * FROM crazy_objects WHERE type = :type");
         $query->bindValue("type", $_GET['type']);
         $query->execute();
       } else {
       
       // подготавливаем запрос SELECT * FROM space_objects
       // вообще звездочку не рекомендуется использовать, но на первый раз пойдет
       $query = $this->pdo->query("SELECT * FROM crazy_objects");
       }
       // стягиваем данные через fetchAll() и сохраняем результат в контекст
       $context['crazy_objects'] = $query->fetchAll();
       $context['page_history'] = isset($_SESSION['page_history']) ? $_SESSION['page_history'] : [];
       return $context;
   }
}