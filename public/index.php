<?php

declare(strict_types=1);


use Framework\Http\Kernel;
use Framework\Http\Request;
use Framework\Http\Router;

require_once dirname(__DIR__) . '/vendor/autoload.php';

define('VIEWS_PATH', dirname(__DIR__) . '/views');

$router = new Router();
$router->addRoute('GET', '/', [App\Controller\HomeController::class, 'index']);

$kernel = new Kernel($router);

$request = Request::createFromGlobals();

$response =  $kernel->handle($request);
$response->send();
