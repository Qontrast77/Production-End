<?php

class BaseCrazyTwigController extends TwigBaseController {
   
    public function getContext(): array
    {
        $context = parent::getContext();

        // Создаем запрос для получения типов объектов из таблицы cars_objects
        $query = $this->pdo->query("SELECT DISTINCT type FROM crazy_objects ORDER BY 1");
        // Стягиваем данные по типам объектов из cars_objects
        $types_from_objects = $query->fetchAll();

        // Создаем запрос для получения всех типов из таблицы cars_types
        $query_types = $this->pdo->query("SELECT id, nametype FROM cars_type");
       
        $types_from_types = $query_types->fetchAll();

        // Добавляем в контекст обе выборки
        $context['types_from_objects'] = $types_from_objects; // Типы из cars_objects
        $context['types_from_types'] = $types_from_types; // Типы из cars_types
        return $context;
    }
}