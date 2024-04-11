<?php

declare(strict_types=1);

namespace Framework\Http;

class Request
{
    public function __construct(
        public readonly array $getParams,
        public readonly array $postParams,
        public readonly array $cookies,
        public readonly array $files,
        public readonly array $server,
    ) {
    }

    public static function createFromGlobals(): static
    {
        return new static($_GET, $_POST,$_COOKIE, $_FILES, $_SERVER);
    }

    public function getParam(string $key)
    {
        if (in_array($key, $this->postParams)) {
            return $this->postParams[$key];
        }

        return $this->getParams[$key];
    }

    public function getPathInfo(): string
    {
        return strtok($this->server['REQUEST_URI'], '?');
    }

    public function getMethod(): string
    {
        return $this->server['REQUEST_METHOD'];
    }

    public function addParam(string $key, $value)
    {
        $this->getParams[$key] = $value;
    }

    public function addParams(array $params)
    {
        $this->getParams = array_merge($this->getParams, $params);
    }
}
