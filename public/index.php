<?php
require_once '../vendor/autoload.php';
require_once '../framework/autoload.php';
require_once "../controllers/MainController.php";
require_once "../controllers/ObjectController.php";
require_once "../controllers/Controller404.php";
require_once "../controllers/SearchController.php";
require_once "../controllers/CrazyObjectCreateController.php";
require_once "../controllers/CrazyObjectDeleteController.php";
require_once "../controllers/TypesObjectCreateController.php";
require_once "../controllers/CrazyObjectUpdateController.php";
require_once "../controllers/LogoutController.php";
require_once "../controllers/LoginController.php";



require_once "../middlewares/LoginRequiredMiddleware.php";
require_once "../middlewares/UserHistoryMiddleware.php";

session_set_cookie_params(60 * 60 * 10);
session_start();

// $url = $_SERVER["REQUEST_URI"];
$loader = new \Twig\Loader\FilesystemLoader('../views');
$twig = new \Twig\Environment($loader, [
        "debug" => true // добавляем тут debug режим
]);
$twig->addExtension(new \Twig\Extension\DebugExtension());

// $controller = new Controller404($twig);

// создаем экземпляр класса и передаем в него параметры подключения
// создание класса автоматом открывает соединение
$pdo = new PDO("mysql:host=localhost;dbname=crazy_cars;charset=utf8", "root", "");



$router = new Router($twig, $pdo);

$router->add("/", MainController::class)
        ->middleware(new UserHistoryMiddleware())->middleware(new LoginRequiredMiddleware());
$router->add("/crazy_objects/(?P<id>\d+)", ObjectController::class)
        ->middleware(new UserHistoryMiddleware())->middleware(new LoginRequiredMiddleware());
$router->add("/search", SearchController::class)
        ->middleware(new UserHistoryMiddleware())->middleware(new LoginRequiredMiddleware());

$router->add("/", MainController::class)
        ->middleware(new UserHistoryMiddleware());
$router->add("/crazy_objects/(?P<id>\d+)", ObjectController::class)
        ->middleware(new UserHistoryMiddleware());
$router->add("/search", SearchController::class)
        ->middleware(new UserHistoryMiddleware());
$router->add("/crazy_object/create", CrazyObjectCreateController::class)
        ->middleware(new LoginRequiredMiddleware());
$router->add("/crazy_object/add", TypesObjectCreateController::class)
        ->middleware(new LoginRequiredMiddleware());
$router->add("/crazy_object/delete", CrazyObjectDeleteController::class)
        ->middleware(new LoginRequiredMiddleware());
$router->add("/crazy_object/(?P<id>\d+)/update", CrazyObjectUpdateController::class)
        ->middleware(new LoginRequiredMiddleware());
$router->add("/login", LoginController::class);
$router->add("/logout", LogoutController::class);






$router->get_or_default(Controller404::class);
