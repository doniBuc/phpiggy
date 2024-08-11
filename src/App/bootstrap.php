<?php

declare(strict_types=1);


// include __DIR__ . "/../Framework/App.php"; // using absolute url path
require  __DIR__ . "/../../vendor/autoload.php"; // when you use autoloader frm composer

use Framework\App;
use App\Controllers\{HomeController, AboutController};

$app = new App();

// $app->get('/', ['App\Controllers\HomeController', 'home']); 
// php 8 introduce class a class magic const for getting the full namespace of the class, instead manually typing it
$app->addGetRoutes('/', [HomeController::class, 'home']);
$app->addGetRoutes('/about', [AboutController::class, 'about']);

return $app;
