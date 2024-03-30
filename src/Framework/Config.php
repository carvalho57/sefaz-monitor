<?php

namespace Framework;

class Config
{
    public static function get($key): string
    {
        return $_ENV[$key] ?? '';
    }
}
