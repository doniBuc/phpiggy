<?php

declare(strict_types=1);


// include __DIR__ . "/../Framework/App.php"; // using absolute url path
require  __DIR__ . "/../../vendor/autoload.php"; // when you use autoloader frm composer

use Framework\App;
use App\Config\Paths;
use Dotenv\Dotenv;

use function App\Config\{registerRoutes, registerMiddleware}; // import the fn


$dotenv = Dotenv::createImmutable(Paths::ROOT); // this class has a method for loading env file-> this method create a inst of Dotenv Class pass root dir of our project it will detect the env files
$dotenv->load(); // we can load the file after call our env var is now accessible in our apps

//use App\Controllers\{HomeController, AboutController}; -> Routes.php


$app = new App(Paths::SOURCE . "App/container-definitions.php");


registerRoutes($app); //  need to manually load this fn doesnt not support by composer to autoload this file -> composer.json
registerMiddleware($app);
// $app->get('/', ['App\C ontrollers\HomeController', 'home']); 
// php 8 introduce class a class magic const for getting the full namespace of the class, instead manually typing it

// //Refactoring this routes pwde gumamit ng static class, pero gagamitin natin is function
// $app->addGetRoutes('/', [HomeController::class, 'home']);
// $app->addGetRoutes('/about', [AboutController::class, 'about']);
return $app;
