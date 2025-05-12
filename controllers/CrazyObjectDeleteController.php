<?php

// в кой то веки наследуемся не от TwigBaseController а от BaseController
class CrazyObjectDeleteController extends BaseController {
    public function post(array $context)
    {
        $id = $_POST['id']; // взяли id

        $sql =<<<EOL
DELETE FROM crazy_objects WHERE id = :id
EOL; // сформировали запрос
        
        // выполнили
        $query = $this->pdo->prepare($sql);
        $query->bindValue(":id", $id);
        $query->execute();

        header("Location: /");
        exit; // после  header("Location: ...") надо писать exit
    }
}