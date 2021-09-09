<?php

use Base\Application;

include '../vendor/autoload.php';
include '../src/config.php';

$route = new \Base\Route();
$route->add('/', \App\Controller\Login::class);
$route->add('/register', \App\Controller\Register::class);

$app = new \Base\Application($route);
$app->run();
