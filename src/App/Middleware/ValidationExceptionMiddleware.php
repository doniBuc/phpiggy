<?php

declare(strict_types=1);


namespace App\Middleware;

use Framework\Contracts\MiddlewareInterface;
use Framework\Exceptions\ValidationException;


class ValidationExceptionMiddleware implements MiddlewareInterface
{

    public function process(callable $next)
    {
        try {
            $next();
        } catch (ValidationException $e) {
            $_SESSION['errors'] = $e->errors; // use session is var to store the errors, need to enabled first
            $referer = $_SERVER['HTTP_REFERER']; // redirect with same url
            // dd($e->errors);
            redirecTo($referer);
        }
    }
}
