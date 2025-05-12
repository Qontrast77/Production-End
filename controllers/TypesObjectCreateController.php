<?php
require_once "BaseCrazyTwigController.php";

class TypesObjectCreateController extends BaseCrazyTwigController {
    public $template = "types_object_create.twig";

    public function get(array $context) // добавили параметр
    {
        
        
        parent::get($context); // пробросили параметр
    }

    public function post(array $context) {
        // получаем значения полей с формы
        $nametype = $_POST['nametype'];
        $tmp_name = $_FILES['image']['tmp_name'];
        $name =  $_FILES['image']['name'];
        
   
        move_uploaded_file($tmp_name, "../public/media/$name");
        $image_url = "/media/$name"; // формируем ссылку без адреса сервера
        // создаем текст запрос
        $sql = <<<EOL
INSERT INTO cars_type(nametype, image)
VALUES(:nametype, :image_url)
EOL;
        

        // подготавливаем запрос к БД
        $query = $this->pdo->prepare($sql);
        // привязываем параметры
        $query->bindValue("nametype", $nametype);
        $query->bindValue("image_url", $image_url);
        // выполняем запрос
        $query->execute();
        
        $context['message'] = 'Вы успешно создали объект';
        $context['id'] = $this->pdo->lastInsertId(); // получаем id нового добавленного объекта

        $this->get($context);
    }
}