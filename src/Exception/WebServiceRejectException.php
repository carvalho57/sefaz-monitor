<?php

declare(strict_types=1);

namespace App\Exception;

class WebServiceRejectException extends \DomainException
{
    public function __construct(string $message = '', int $code = 0, \Throwable $previous = null)
    {
    }
}
