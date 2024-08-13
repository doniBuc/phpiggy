<?php

declare(strict_types=1);

namespace App\Config;

use Framework\App;
use App\Controllers\{AboutController, AuthController, HomeController,};

function registerRoutes(App $app)
{
    $app->addGetRoutes('/', [HomeController::class, 'home']);
    $app->addGetRoutes('/about', [AboutController::class, 'about']);
    $app->addGetRoutes('/register', [AuthController::class, 'register']);
    $app->addPostRoutes('/register', [AuthController::class, 'registered']);
}
