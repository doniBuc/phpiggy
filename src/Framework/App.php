<?php

declare(strict_types=1);

namespace Framework;

class App
{
    private Router $router;

    function __construct()
    {
        $this->router = new Router();
    }

    public function run()
    {
        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $httpMethod = $_SERVER['REQUEST_METHOD'];
        $this->router->dispatch($path, $httpMethod);
    }

    public function addGetRoutes(string $path, array $controller)
    {
        $this->router->addRoutes('GET', $path, $controller);
    }
}
