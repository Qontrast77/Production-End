<?php
class UserHistoryMiddleware extends BaseMiddleware {
    public function apply(BaseController $controller, array $context)
    {
        // Проверяем, существует ли сессия и массив истории
        if (!isset($_SESSION['page_history'])) {
            $_SESSION['page_history'] = [];
        }

        // Получаем текущий URL страницы и декодируем его
        $current_url = $_SERVER['REQUEST_URI'];
        $decoded_url = rawurldecode($current_url); // Декодируем кириллицу

        // Добавляем декодированный URL в историю
        array_unshift($_SESSION['page_history'], $decoded_url);

        // Ограничиваем количество записей до 10
        if (count($_SESSION['page_history']) > 10) {
            array_pop($_SESSION['page_history']); // Удаляем самый старый элемент
        }

        return $context;
    }
}