<?php
require_once "BaseCrazyTwigController.php"; 

class LoginController extends BaseCrazyTwigController {
    public $template = "login.twig";
    public function post(array $context) {
        // получаем значения полей с формы
        $user = isset($_POST['login']) ? $_POST['login'] : '';
        $password = isset($_POST['password']) ? $_POST['password'] : '';
        
     // проверяем, существует ли пользователь с таким логином и паролем
        $query = $this->pdo->prepare("SELECT username,password FROM users WHERE username = :username AND password = :password");
        $query->execute(['username' => $user, 'password' => $password]);
        $userData = $query->fetchAll();

        // если пользователь найден, устанавливаем сессионную переменную
        if ($userData) {
            session_start();
            $_SESSION["is_logged"] = true;
            header("Location: /");
            exit;
        }

        // Получаем контекст из родительского класса (с добавлением типов из таблиц)
        $context = $this->getContext();

        // передаем контекст в шаблон
        $this->get($context);
    }
}