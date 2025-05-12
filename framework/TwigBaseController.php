<?php
//require_once "BaseController.php"; // обязательно импортим BaseController

class TwigBaseController extends BaseController {
    public $title = ""; // название страницы
    public $template = ""; // шаблон страницы
    public $context = []; // контекст страницы
    public $currentUrl;

    protected \Twig\Environment $twig; // ссылка на экземпляр twig, для рендернига
    
    
    public function setTwig($twig) {
        $this->twig = $twig;
    }
    public function setCurrentUrl($url) {
        $this->currentUrl = $url;
    }
    
    
    public function getContext() : array
    {
        $context = parent::getContext(); // вызываем родительский метод
        $context['title'] = $this->title; // добавляем title в контекст
        $context['current_url'] = $this->currentUrl; 
        return array_merge($context, $this->context);
    }
  

    public function get(array $context) { // добавил аргумент в get
        echo $this->twig->render($this->template, $context); // а тут поменяем getContext на просто $context
    }
    
}