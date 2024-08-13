<?php

declare(strict_types=1);


namespace App\Middleware;

use Framework\Contracts\MiddlewareInterface;
use Framework\Exceptions\ValidationExceptions;

class ValidationExceptionsMiddleware implements MiddlewareInterface
{

    public function process(callable $next)
    {
        $next();
    }
}
