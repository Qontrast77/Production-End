<?php

require_once "BaseCrazyTwigController.php";

class CrazyObjectUpdateController extends BaseCrazyTwigController
{
    public $template = "cars_object_update.twig";
    public function get(array $context)
    {
        // Получаем идентификатор объекта для редактирования
        $id = $this->params['id'];
    
        // Запрос для получения объекта по ID
        $sql = "SELECT * FROM crazy_objects WHERE id = :id";
        $query = $this->pdo->prepare($sql);
        $query->bindValue("id", $id);
        $query->execute();
    
        // Получаем данные объекта
        $object = $query->fetch();
    
        if ($object) {
            // Передаем данные объекта в контекст шаблона
            $context['object'] = $object;
    
            // Здесь нужно получить список типов, если он необходим
            $sql = "SELECT DISTINCT nametype FROM cars_type"; // Пример запроса для получения типов
            $query = $this->pdo->prepare($sql);
            $query->execute();
            $context['types_from_types'] = $query->fetchAll();
    
            parent::get($context); // Рендерим шаблон
        } else {
            // Обработка ошибки, если объект не найден
            $context['message'] = 'Объект не найден';
            parent::get($context);
        }
    }
    

    public function post(array $context)
    {
        // Получаем идентификатор объекта, который нужно обновить
        $id = $this->params['id'];

        // Получаем значения полей с формы
        $title = $_POST['title'];
        $description = $_POST['description'];
        $type = $_POST['type'];
        $info = $_POST['info'];

        // Проверяем, загружена ли новая картинка
        if ($_FILES['image']['error'] == 0) {
            // Если изображение загружено, то обрабатываем его
            $tmp_name = $_FILES['image']['tmp_name'];
            $name = $_FILES['image']['name'];
            move_uploaded_file($tmp_name, "../public/media/$name");
            $image_url = "/media/$name";
        } else {
            // Если изображение не было загружено, используем старое значение
            // Запрос для получения текущего пути к изображению
            $sql = "SELECT image FROM crazy_objects WHERE id = :id";
            $query = $this->pdo->prepare($sql);
            $query->bindValue("id", $id);
            $query->execute();
            $data = $query->fetch();

            $image_url = $data['image'];
        }

        // Формируем SQL-запрос для обновления данных
        $sql = <<<EOL
UPDATE crazy_objects
SET title = :title, description = :description, type = :type, info = :info, image = :image_url
WHERE id = :id
EOL;

        // Подготавливаем запрос к БД
        $query = $this->pdo->prepare($sql);

        // Привязываем параметры
        $query->bindValue("title", $title);
        $query->bindValue("description", $description);
        $query->bindValue("type", $type);
        $query->bindValue("info", $info);
        $query->bindValue("image_url", $image_url);
        $query->bindValue("id", $id);

        // Выполняем запрос
        $query->execute();

        // Уведомление об успешном обновлении
        $context['message'] = 'Объект успешно обновлён';
        $context['id'] = $id;
        // Получаем данные обновлённого объекта для отображения
        $this->get($context);
    }
}
