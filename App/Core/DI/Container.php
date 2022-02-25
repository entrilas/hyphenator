<?php

declare(strict_types=1);

namespace App\Core\DI;

use Closure;
use Exception;
use ReflectionClass;
use ReflectionException;

class Container
{
    protected array $instances = [];

    public function set(string $name, object $concrete = NULL): void
    {
        if ($concrete === NULL) {
            $concrete = $name;
        }
        $this->instances[$name] = $concrete;
    }

    /**
     * @throws ReflectionException
     */
    public function get(string $name, array $parameters = [])
    {
        if (!isset($this->instances[$name])) {
            $this->set($name);
        }

        return $this->resolve($this->instances[$name], $parameters);
    }

    /**
     * @throws ReflectionException
     * @throws Exception
     */
    public function resolve(string $name, array $parameters)
    {
        if ($name instanceof Closure) {
            return $name($this, $parameters);
        }

        $reflector = new ReflectionClass($name);
        if (!$reflector->isInstantiable()) {
            throw new Exception(sprintf("Class %s is not instantiable", $name));
        }

        $constructor = $reflector->getConstructor();
        if (is_null($constructor)) {
            return $reflector->newInstance();
        }

        $parameters = $constructor->getParameters();
        $dependencies = $this->getDependencies($parameters);

        return $reflector->newInstanceArgs($dependencies);
    }

    /**
     * @throws ReflectionException
     * @throws Exception
     */
    public function getDependencies(array $parameters): array
    {
        $dependencies = [];
        foreach ($parameters as $parameter) {
            $dependency = $parameter->getClass();
            if ($dependency === NULL) {
                if ($parameter->isDefaultValueAvailable()) {
                    $dependencies[] = $parameter->getDefaultValue();
                } else {
                    throw new Exception(sprintf("Can not resolve class dependency %s", $parameter->name));
                }
            } else {
                $dependencies[] = $this->get($dependency->name);
            }
        }

        return $dependencies;
    }
}
