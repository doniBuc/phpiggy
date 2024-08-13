<?php

namespace Framework;


use App\Config\Paths;
use Framework\Exceptions\ContainerException;
use ReflectionClass, ReflectionNamedType;


class Container
{

    private array $definitions = [];
    private array $resolved = []; // for singleton pattern -> only one instance can create of a class
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

        if (!$reflectionClass->isInstantiable()) {
            throw new ContainerException("Class {$reflectionClass} is not instantiable");
        }

        $constructor = $reflectionClass->getConstructor(); // grabbing the constructor

        if (!$constructor) {
            return new $className();
        }

        $params = $constructor->getParameters(); // grabbing the list of parameters of constructor by doing so we can instantiate the class  and pass to controller

        if (count($params) === 0) {
            return new $className();
        }

        //validation of parameters -> ReflectionNamedType

        $dependencies = []; // this variable  will be the stored the instances or dependencies required by our controller

        foreach ($params as $param) {

            $name = $param->getName();
            $type = $param->getType();  // validating the type of params if the type is string or boolean cant be instantiate we only accept class

            if (!$type) {
                throw new ContainerException("Failed to resolve {$className} because the param {$name} is missing the type hinmt");
            }

            if (!$type instanceof ReflectionNamedType || $type->isBuiltin()) {  // there is other validation we can perform but this is ok 
                throw new ContainerException("Failed to resolve {$className} because invalid param name. ");
            }
        }

        $dependencies[] = $this->get($type->getName()); // since we required a string id we passed along the name of type associated with current parameter since paramater so its pointed to the name of the class

        // dd($dependencies);
        return $reflectionClass->newInstanceArgs($dependencies);
    }

    public function get(string $id)
    {

        if (!array_key_exists($id, $this->definitions)) {

            throw new ContainerException("Class {$id} does not exist in container");
        }

        if (array_key_exists($id, $this->resolved)) {
            return $this->resolved[$id];
        }

        $factory = $this->definitions[$id];

        $dependecy = $factory(); // invoke the fn

        $this->resolved[$id] = $dependecy;

        return $dependecy;
    }
}
