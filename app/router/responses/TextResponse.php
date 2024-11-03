<?php

namespace app\router\responses;

use app\router\Response;

class TextResponse extends Response
{
    public function __construct($code = 200, $body = '',  $headers = [])
    {
        parent::__construct($code, $body, $headers);
        $this->addHeader('Content-Type', 'text/plain; charset=UTF-8');
    }
    public function send()
    {
        http_response_code($this->code);
        foreach ($this->headers as $name => $value) {
            header("$name: $value");
        }
        echo $this->body;
    }
}
