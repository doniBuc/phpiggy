<?php

declare(strict_types=1);

namespace Framework;

class Router
{
    private array $routes = [];
    private array $middlewares = [];
    private array $errorHandler;

    public function addRoutes(string $httpMethod, string $path, array $controller)
    {
        $path = $this->pathNormalize($path);

        $regexPath = preg_replace('#{[^/]+}#', '([^/]+)', $path); // for route parameter

        $this->routes[] = [
            'path' => $path,
            'httpMethod' => strtoupper($httpMethod),
            'controller' => $controller,
            'middlewares' => [],
            'regexPath' => $regexPath
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
        $httpMethod = strtoupper($_POST['_METHOD'] ?? $httpMethod); //$_POST[_METHOD] checking if form request POST with have input element with name _METHOD get the value as httpmethod

        foreach ($this->routes as $route) {

            // $paramValues is reference value that passed by preg_match we can use it outside of this preg_match fn
            if (!preg_match("#^{$route['regexPath']}$#", $path, $paramValues) || $route['httpMethod'] !== $httpMethod) // for path route parameter change ['path'] into ['regexPath']
                continue;

            array_shift($paramValues); // $paramValue return array of 2 index and we need the 2nd property which contain the paramValue or id of transaction

            preg_match_all('#{([^/]+)}#', $route['path'], $paramKeys); //return single result getting the key we used for combining to $paramValues

            $paramKeys = $paramKeys[1]; // Same with $paramValues return 2 index of array we need the 2nd only 

            $params = array_combine($paramKeys, $paramValues);

            [$classController, $function] = $route['controller'];

            $controllerInstance = $container ? $container->resolve($classController) : new $classController();

            // $controllerInstance->$function(); // or   controllerInstance->{$function}(); This syntax used when the name of the method is stored in a variable ->$function() instead of ->function().

            // we looping the middleware
            $action = fn() => $controllerInstance->$function($params); // first stored not invoke immediately  in var //pass the $param so each controller that need params array for supplied for edit.php

            $allMiddleware = [...$route['middlewares'], ...$this->middlewares]; // order is matter

            foreach ($allMiddleware as $middleware) { // change the $this->middlewares into $allMiddleware in which the specific middleware for route is store

                $middlewareInstance = $container ? $container->resolve($middleware) : new $middleware; // need instantiate the middleware before use i  t
                $action = fn() => $middlewareInstance->process($action);
            }

            $action();

            return;
        }
        // if the route not find dispatch 404

        $this->dispatchNotFound($container);
    }

    public function addMiddleware(string $middleware) // $middlware define as classes not instance->because we want our middleware to access to our container  to inject dependencies 
    {
        $this->middlewares[] = $middleware;
    }

    public function addRouteMiddleware(string $middleware)
    {
        $lastRouteKey = array_key_last($this->routes); // return the last key
        $this->routes[$lastRouteKey]['middlewares'][] = $middleware;
    }

    public function setErrorHandler(array $controller)
    {
        $this->errorHandler = $controller;
    }

    public function dispatchNotFound(?Container $container)
    {

        [$classController, $function] = $this->errorHandler;

        $controllerInstance = $classController ? $container->resolve($classController) : new $classController();

        $action = fn() => $controllerInstance->$function();

        foreach ($this->middlewares as $middleware) {
            $middlewareInstance = $container ? $container->resolve($middleware) : new $middleware();
            $action = fn() => $middlewareInstance->process($action);
        }
        $action();
    }
}
