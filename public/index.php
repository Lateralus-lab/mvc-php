<?php

use Base\Application;
use Base\Route;
use Base\RouteException;
use App\Controller\User;

include '../vendor/autoload.php';

$app = new Application();
$app->run();

$route = new Route();
/** @uses \App\Controller\User::loginAction() */
$route->addRoute('/user/login', User::class, 'login');
/** @uses \App\Controller\User::registerAction() */
$route->addRoute('/user/register', User::class, 'register');
/** @uses \App\Controller\Blog::indexAction() */
$route->addRoute('/blog', App\Controller\Blog::class, 'index');
$route->addRoute('/blog/index', App\Controller\Blog::class, 'index');

$controllerName = $route->getControllerName();
$controller =  new $controllerName;

$actionName = $route->getActionName();

if (!method_exists($controller, $actionName)) {
  throw new RouteException('Action' . $actionName . ' not found in ' . $controllerName);
}

$controller->$actionName();
