<?php

declare(strict_types=1);


namespace App\Config;

use Framework\App;
use App\Middleware\TemplateDataMiddleware;
use App\Middleware\ValidationExceptionsMiddleware;
use Framework\Exceptions\ValidationExceptions;

function registerMiddleware(App $app)
{
    $app->addMiddleware(TemplateDataMiddleware::class);
    $app->addMiddleware(ValidationExceptions::class);
}
