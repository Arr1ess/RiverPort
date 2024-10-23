<?php

namespace app\router\responses;

use app\router\Response;

class HtmlResponse extends Response
{
    public function __construct($body = '', $code = 200, $headers = [])
    {
        parent::__construct($code, $body, $headers);
        $this->addHeader('Content-Type', 'text/html; charset=UTF-8');
    }

    public function send()
    {
        http_response_code($this->getCode());
        foreach ($this->headers as $name => $value) {
            header("$name: $value");
        }
        echo $this->getBody();
    }
}