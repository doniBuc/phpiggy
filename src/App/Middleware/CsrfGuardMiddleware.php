<?php

declare(strict_types=1);

namespace App\Middleware;

use Framework\Contracts\MiddlewareInterface;
use Framework\TemplateEngine;

class CsrfGuardMiddleware implements MiddlewareInterface
{
    public function __construct(private TemplateEngine $view) {}

    public function process(callable $next)
    {
        // Validating the token is going to required  few steps
        //1st. we dont want to perform validation unless it is POST request, form submission always POST request

        $requestMethod = strtoupper($_SERVER['REQUEST_METHOD']); // httpmethod is case-sensitive
        $validMethods = ['POST', 'PATCH', 'DELETE']; // lets create an array of valid http method

        if (!in_array($requestMethod, $validMethods)) {
            $next();
            return;
        }

        if ($_SESSION['token'] !== $_POST['token']) {
            redirecTo('/'); //redirecting to homepage, the better solution is throw an exception howerver were not going to handle the exception instaed it is simplier to redirect a user to home page, if you want you can create a custom exception for CSR Token
        }

        // we can assume that the token is valid, lets destroy a token
        // token should only use once for every form request by deleting a token our application force to generate a new token
        unset($_SESSION['token']); // reusing token can lead an issue if an hacker can grab a token, they can perform multiple action on user account

        $next();
    }
}
