<?php

declare(strict_types=1);

namespace Evyex\ContainerSimple\Tests;

use Evyex\ContainerSimple\Container;
use PHPUnit\Framework\TestCase;

class A
{
    public function __construct(private B $b, private C $c)
    {
    }

    public function getB(): B
    {
        return $this->b;
    }

    public function getC(): C
    {
        return $this->c;
    }
}

class B
{
    public function __construct(private A $a)
    {
    }

    public function getA(): A
    {
        return $this->a;
    }
}

class C
{
    public function __construct(private A $a)
    {
    }

    public function getA(): A
    {
        return $this->a;
    }
}

class RecursiveTest extends TestCase
{
    public function testRecursiveResolution(): void
    {
        $container = new Container();
        $a = $container->get(A::class);
        self::assertInstanceOf(A::class, $a);
        self::assertInstanceOf(B::class, $a->getB());
        self::assertInstanceOf(C::class, $a->getC());
        $b = $container->get(B::class);
        self::assertInstanceOf(B::class, $b);
        self::assertInstanceOf(A::class, $b->getA());
        $c = $container->get(C::class);
        self::assertInstanceOf(C::class, $c);
        self::assertInstanceOf(A::class, $c->getA());
    }
}
