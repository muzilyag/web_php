<?php

require_once __DIR__ . "/bootstrap.php";

use App\Container;
use App\Router;
use App\Controllers\ProjectController;
use App\Controllers\AuthController;
use Doctrine\ORM\EntityManager;

$container = new Container();

$container->set(EntityManager::class, function () use ($entityManager) {
    return $entityManager;
});

$container->set(ProjectController::class, function (Container $c) {
    return new ProjectController($c->get(EntityManager::class));
});

$container->set(AuthController::class, function (\App\Container $c) {
    return new AuthController($c->get(EntityManager::class));
});

$router = new Router($container);

$router->add('GET', '/register', [AuthController::class, 'show_register']);
$router->add('POST', '/register', [AuthController::class, 'register']);

$router->add('GET', '/login', [AuthController::class, 'show_login']);
$router->add('POST', '/login', [AuthController::class, 'login']);
$router->add('GET', '/logout', [AuthController::class, 'logout']);
$router->add('GET', '/', [ProjectController::class, 'index']);
$router->add('POST', '/add', [ProjectController::class, 'store']);

$router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);

?>
