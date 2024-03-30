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

    public function addHeader(string $key, string $value)
    {
        $this->headers[$key] = $value;
        return $this;
    }

    public function json(array $object): static
    {
        $this->addHeader('Content-type', 'application/json');
        $this->content = json_encode($object, JSON_UNESCAPED_UNICODE);
        return $this;
    }

    public function send()
    {
        foreach ($this->headers as $header => $value) {
            header("$header: $value");
        }

        http_response_code($this->status);
        echo $this->content;
    }

    public function __toString()
    {
        return $this->content;
    }
}
