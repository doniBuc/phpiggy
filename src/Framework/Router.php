<?php

declare(strict_types=1);

namespace Framework;

class Router
{
    private array $routes = [];

    public function addRoutes(string $httpMethod, string $path, array $controller)
    {
        $path = $this->pathNormalize($path);
        $this->routes[] = [
            'path' => $path,
            'httpMethod' => strtoupper($httpMethod),
            'controller' => $controller
        ];
    }

    private function pathNormalize(string $path): string
    {

        $path = trim($path, '/');
        $path = "/{$path}/";
        $path = preg_replace('#[/]{2,}#', '/', $path);

        return $path;
    }

    public function dispatch(string $path, string $httpMethod, Container $container = null)
    {

        $path = $this->pathNormalize($path);
        $httpMethod = strtoupper($httpMethod);

        foreach ($this->routes as $route) {
            if (!preg_match("#^{$route['path']}$#", $path) || $route['httpMethod'] !== $httpMethod)
                continue;

            [$classController, $function] = $route['controller'];

            $controllerInstance = $container ? $container->resolve($classController) : new $classController();

            $controllerInstance->$function(); // or $controllerInstance->{$function}();
        }
    }
}
