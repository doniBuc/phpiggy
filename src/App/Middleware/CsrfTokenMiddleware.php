<?php

declare(strict_types=1);

namespace App\Middleware;

use Framework\Contracts\MiddlewareInterface;
use Framework\TemplateEngine;

class CsrfTokenMiddleware implements MiddlewareInterface
{
    public function __construct(private TemplateEngine $view) {}

    public function process(callable $next)
    {
        // grabbing a token process method 
        // during form submission were verified the user has correct token by comparing the token submitted with the form with token in our session

        $_SESSION['token'] = $_SESSION['token'] ?? bin2hex(random_bytes(32)); // token must secretly generated, user should not genereated, it have different way this simpliest solutiong generating random byte value
        $this->view->addGlobal('csrfToken', $_SESSION['token']); // in this ex we generating a token for every request, that is completely acceptable in some application, however it can be harder to manage token when a new token is generated on every request
        // before rendering this token in our template lets register this middleware

        $next();
    }
}
