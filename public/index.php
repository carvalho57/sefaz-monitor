<?php

declare(strict_types=1);

use Framework\Http\Kernel;
use Framework\Http\Request;
use Framework\Http\Router;

require_once dirname(__DIR__) . '/vendor/autoload.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

define('VIEWS_PATH', dirname(__DIR__) . '/views');
define('STORAGE_PATH', dirname(__DIR__) . '/storage');

$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

$router = new Router();
$router->get('/', [App\Controller\MonitorController::class, 'index']);
$router->get('/index', [App\Controller\MonitorController::class, 'index']);
$router->get('/list', [App\Controller\MonitorController::class, 'listAll']);
$router->post('/update', [App\Controller\MonitorController::class, 'update']);
$router->get('/status', [App\Controller\MonitorController::class, 'status']);

$kernel = new Kernel($router);

$request = Request::createFromGlobals();

$response =  $kernel->handle($request);
$response->send();
