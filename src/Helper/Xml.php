<?php

namespace App\Helper;

class Xml
{
    private string $content;
    public function __construct(string $content)
    {
        $this->content = json_encode(simplexml_load_string($content));
    }

    public function toStd()
    {
        return json_decode($this->content);
    }

    public function toArray()
    {
        return json_decode($this->content, true);
    }
}
