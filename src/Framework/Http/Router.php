<?php

declare(strict_types=1);

namespace Framework\Http;

class Router
{
    public const FOUND     = 1;
    public const NOT_FOUND = 2;
    private array $routes  = [];

    public function addRoute(string $method, string $path, array $handler): self
    {
        $this->routes[strtoupper($method)][$path] = $handler;
        return $this;
    }

    public function get(string $path, array $handler): self
    {
        return $this->addRoute('GET', $path, $handler);
    }

    public function post(string $path, array $handler): self
    {
        return $this->addRoute('POST', $path, $handler);
    }

    public function dispatcher(string $method, string $uri): array
    {
        $handler = $this->routes[$method][$uri] ?? null;

        if (!$handler) {
            return [self::NOT_FOUND, null];
        }

        return [self::FOUND, $handler];
    }

    public function getRoutes(): array
    {
        return $this->routes;
    }
}
