<?php
require_once "BaseCrazyTwigController.php"; 

class SearchController extends BaseCrazyTwigController {
    public $template = "search.twig";
    
    public function getContext(): array {
        $context = parent::getContext();
       
        // Получаем и декодируем параметры
        $type_selected = isset($_GET['type']) ? rawurldecode($_GET['type']) : '';
        $title_selected = isset($_GET['title']) ? rawurldecode($_GET['title']) : '';
        $info_selected = isset($_GET['info']) ? rawurldecode($_GET['info']) : '';
        
        $sql = <<<EOL
SELECT *
FROM crazy_objects
WHERE (:title = '' OR title LIKE CONCAT('%', :title, '%'))
    AND (:type = '' OR type = :type)
    AND (:info = '' OR info LIKE CONCAT('%', :info, '%'))
EOL;

        $query = $this->pdo->prepare($sql);
        $query->bindValue("title", $title_selected);
        $query->bindValue("type", $type_selected);
        $query->bindValue("info", $info_selected);
        $query->execute();
        
        $context['objects'] = $query->fetchAll();
        $context['type_selected'] = $type_selected;
        $context['title_selected'] = $title_selected;
        $context['info_selected'] = $info_selected;
        $context['page_history'] = $_SESSION['page_history'] ?? [];
        
        return $context;
    }
}