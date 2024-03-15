<?php
declare(strict_types=1);


use SefazMonitor\App;


require dirname(__DIR__) . '/vendor/autoload.php';

$app = new App();
$app->run();