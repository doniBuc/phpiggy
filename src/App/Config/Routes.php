<?php

declare(strict_types=1);

namespace App\Config;

use Framework\App;
use App\Controllers\{AboutController, HomeController};

function registerRoutes(App $app)
{
    $app->addGetRoutes('/', [HomeController::class, 'home']);
    $app->addGetRoutes('/', [AboutController::class, 'about']);
}
