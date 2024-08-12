<?php

declare(strict_types=1);


// include __DIR__ . "/../Framework/App.php"; // using absolute url path
require  __DIR__ . "/../../vendor/autoload.php"; // when you use autoloader frm composer

use Framework\App;
use function App\Config\registerRoutes; // import the fn
use App\Config\Paths;

//use App\Controllers\{HomeController, AboutController}; -> Routes.php


$app = new App(Paths::SOURCE . "App/container-definitions.php");


registerRoutes($app); //  need to manually load this fn doesnt not support by composer to autoload this file -> composer.json

// $app->get('/', ['App\Controllers\HomeController', 'home']); 
// php 8 introduce class a class magic const for getting the full namespace of the class, instead manually typing it

// //Refactoring this routes pwde gumamit ng static class, pero gagamitin natin is function
// $app->addGetRoutes('/', [HomeController::class, 'home']);
// $app->addGetRoutes('/about', [AboutController::class, 'about']);
return $app;
