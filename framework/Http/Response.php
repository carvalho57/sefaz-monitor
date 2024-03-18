<?php

declare(strict_types=1);

namespace Framework\Http;

class Response
{
    public function __construct(
        private string $content = '',
        private int $status = 200,
        private array $headers = []
    ) {
    }

    public function setContent(string $content)
    {
        $this->content = $content;
    }

    public function setStatusCode(int $status)
    {
        $this->status = $status;
    }

    public function send()
    {
        http_response_code($this->status);
        echo $this->content;
    }

    public function __toString()
    {
        return $this->content;
    }
}
