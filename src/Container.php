<?php

declare(strict_types=1);

namespace Evyex\ContainerSimple;

use Psr\Container\ContainerInterface;

final class Container implements ContainerInterface
{
    /**
     * @var array<class-string, object>
     */
    private array $services = [];

    /**
     * @var class-string[]
     */
    private array $resolving = [];

    /**
     * @var class-string[]
     */
    private array $postInit = [];

    /**
     * @template T of object
     * @param class-string<T> $id
     * @return T
     */
    public function get(string $id): object
    {
        if (array_key_exists($id, $this->services)) {
            return $this->services[$id];
        }

        $this->services[$id] = $this->resolve($id);

        if (in_array($id, $this->postInit, true)) {
            $service = $this->services[$id];
            foreach ((new \ReflectionClass($service))->getConstructor()?->getParameters() ?? [] as $parameter) {
                (new \ReflectionProperty($service, $parameter->getName()))->setValue(
                    $service,
                    $this->get($parameter->getType()->getName())
                );
            }
            $this->postInit = array_diff($this->postInit, [$id]);
        }

        return $this->services[$id];
    }

    /**
     * @param class-string $id
     */
    public function has(string $id): bool
    {
        return class_exists($id);
    }

    /**
     * @param class-string $id
     */
    private function resolve(string $id): object
    {
        if (in_array($id, $this->resolving, true)) {
            $service = (new \ReflectionClass($id))->newInstanceWithoutConstructor();
            $this->postInit[] = $id;
        } else {
            $this->resolving[] = $id;

            $service = new $id(
                ...array_map(
                    fn (\ReflectionParameter $service) => $this->get($service->getType()->getName()),
                    (new \ReflectionClass($id))->getConstructor()?->getParameters() ?? []
                )
            );
            array_pop($this->resolving);
        }

        return $service;
    }
}
