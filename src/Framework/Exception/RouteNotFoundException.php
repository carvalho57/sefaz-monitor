<?php

namespace Framework\Exception;

class RouteNotFoundException extends \RuntimeException
{
    protected $message = 'Route Not Found';
}