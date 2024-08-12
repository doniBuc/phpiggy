<?php

namespace Framework;


use App\Config\Paths;
use ReflectionClass;

class Container
{

    private array $definitions = [];

    public function addDefinitions(array $newDefinitions)
    {
        //using merge_array()fn
        // $this->definitions = array_merge($this->definitions, $newDefinitions);

        //using spread operator
        $this->definitions = [...$this->definitions, ...$newDefinitions];
    }

    public function resolve($className)
    {
        $reflectionClass =  new ReflectionClass($className);
        dd($reflectionClass);
    }
}
