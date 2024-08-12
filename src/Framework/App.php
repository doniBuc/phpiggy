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

    public function addGetRoutes(string $path, array $controller)
    {
        $this->router->addRoutes('GET', $path, $controller);
    }
}
