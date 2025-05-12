<?php
require_once "BaseCrazyTwigController.php"; 

class ObjectController extends BaseCrazyTwigController {
    public $template = "object.twig"; // указываем шаблон

    public function getContext(): array
    {
        $context = parent::getContext();

        // Получаем ID объекта из параметров
        $objectId = $this->params['id'];

        // Готовим запрос к базе данных для получения общей информации
        $query = $this->pdo->prepare("SELECT description, image, info, id FROM crazy_objects WHERE id = :my_id");
        $query->bindValue("my_id", $objectId);
        $query->execute(); // выполняем запрос
        $data = $query->fetch();

        // Проверяем, есть ли данные
        if ($data) {
            // Проверяем, есть ли параметр 'show' в GET-запросе
            if (isset($_GET['show'])) {
                $context['show'] = $_GET['show'];
                // Если параметр 'show' передан, показываем только нужный контент
                if (($_GET['show']) === 'image') {
                    // Показываем только картинку
                    $context['image'] = $data['image'];
                    $context['description'] = $data['description'];
                } elseif (($_GET['show']) === 'info') {
                    // Показываем только полную информацию
                    $context['info'] = $data['info'];
                    $context['description'] = $data['description'];
                }
            } else {
                // Если параметр 'show' не передан, показываем описание
                
                $context['description'] = $data['description'];
                $context['description2'] = $data['description'];
                
            }
            $context['page_history'] = isset($_SESSION['page_history']) ? $_SESSION['page_history'] : [];
            
           
        } 

        return $context;
    }
}
?>