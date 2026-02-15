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
     * @template T of object
     * @param class-string<T> $id
     * @return T
     */
    public function get(string $id): object
    {
        return $this->services[$id] ??= new $id(
            ...array_map(
                fn (\ReflectionParameter $service) => $this->get($service->getType()->getName()),
                (new \ReflectionClass($id))->getConstructor()?->getParameters() ?? []
            )
        );
    }

    /**
     * @param class-string $id
     */
    public function has(string $id): bool
    {
        return class_exists($id);
    }
}
