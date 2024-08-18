<?php

declare(strict_types=1);


use Framework\Contracts\MiddlewareInterface;
use Framework\TemplateEngine;

class FlashMiddleware implements MiddlewareInterface
{

    public function __construct(private TemplateEngine $view) {}

    public function process(callable $next)
    {
        // through this instance ($view), the goal is to add data to whatever template gets render

        $this->view->addGlobal('errors', $_SESSION['errors'] ?? []); // global data can be added using this method
        $next();
    }
}
