<?php
class UserHistoryMiddleware extends BaseMiddleware {
    public function apply(BaseController $controller, array $context)
    {
        // Проверяем, существует ли сессия и массив истории
        if (!isset($_SESSION['page_history'])) {
            $_SESSION['page_history'] = [];
        }

        // Получаем текущий URL страницы
        $current_url = $_SERVER['REQUEST_URI'];

        // Добавляем текущий URL в начало массива истории
        array_unshift($_SESSION['page_history'], $current_url);

        // Ограничиваем количество записей до 10
        if (count($_SESSION['page_history']) > 10) {
            array_pop($_SESSION['page_history']); // Удаляем самый старый элемент
        }



        return $context;
    }
}