<?php

declare(strict_types=1);

namespace Evyex\ContainerSimple\Tests;

use Evyex\ContainerSimple\Container;
use PHPUnit\Framework\TestCase;

class ServiceA
{
}
class ServiceB
{
    public function __construct(public ServiceA $serviceA)
    {
    }
}

class ContainerTest extends TestCase
{
    private Container $container;

    protected function setUp(): void
    {
        $this->container = new Container();
    }

    public function testGetSimpleService(): void
    {
        $service = $this->container->get(ServiceA::class);
        $this->assertInstanceOf(ServiceA::class, $service);
    }

    public function testGetRecursiveService(): void
    {
        $serviceB = $this->container->get(ServiceB::class);
        $this->assertInstanceOf(ServiceB::class, $serviceB);
        $this->assertInstanceOf(ServiceA::class, $serviceB->serviceA);
    }

    public function testGetSameInstance(): void
    {
        $service1 = $this->container->get(ServiceA::class);
        $service2 = $this->container->get(ServiceA::class);
        $this->assertSame($service1, $service2);
    }

    public function testHas(): void
    {
        $this->assertTrue($this->container->has(ServiceA::class));
        $this->assertFalse($this->container->has('NonExistentClass'));
    }
}
