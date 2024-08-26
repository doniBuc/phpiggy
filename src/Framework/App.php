<?php

declare(strict_types=1);

namespace Framework;

class App
{

    private Router $router;
    private Container $container;


    function __construct($containerDefinitionsPath = null)
    {
        $this->router = new Router();
        $this->container = new Container();

        if ($containerDefinitionsPath) {
            $containerDefinitionsPath = include $containerDefinitionsPath;
            $this->container->addDefinitions($containerDefinitionsPath);
        }
    }

    public function run()
    {
        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $httpMethod = $_SERVER['REQUEST_METHOD'];
        $this->router->dispatch($path, $httpMethod, $this->container);
    }

    public function addGetRoutes(string $path, array $controller): App
    {
        $this->router->addRoutes('GET', $path, $controller);
        return $this;
    }
    public function addDeleteRoutes(string $path, array $controller): App
    {

        $this->router->addRoutes('DELETE', $path, $controller);
        return $this;
    }

    public function addPostRoutes(string $path, array $controller): App
    {
        $this->router->addRoutes('POST', $path, $controller);
        return $this;
    }

    public function addMiddleware(string $middleware)
    {
        $this->router->addMiddleware($middleware);
    }

    public function addSpecificMiddleware(string $middleware)
    {
        $this->router->addRouteMiddleware($middleware);
    }
}
