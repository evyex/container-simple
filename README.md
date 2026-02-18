# container-simple

Simple PHP Dependency Injection Container implementing PSR-11 ContainerInterface with Autowiring.

## Installation

```bash
composer require evyex/container-simple
```

## Usage

```php
use Evyex\ContainerSimple\Container;

$container = new Container();

// Get a service (auto-resolves dependencies)
$service = $container->get(MyService::class);

// Check if a service exists
if ($container->has(MyService::class)) {
    // ...
}
```

## Requirements

- PHP 8.1+
- psr/container ^2.0
