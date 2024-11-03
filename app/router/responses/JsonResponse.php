<?php


namespace app\router\responses;

use app\router\Response;

class JsonResponse extends Response
{
    public function __construct($body = [], $code = 200, $headers = [])
    {
        parent::__construct($code, json_encode($body), $headers);
        $this->addHeader('Content-Type', 'application/json; charset=UTF-8');
    }

    public function send()
    {
        http_response_code($this->getCode());
        foreach ($this->headers as $name => $value) {
            header("$name: $value");
        }
        echo $this->getBody();
    }
    public static function __set_state($array)
    {
        $instance = new self($array['body'], $array['code'], $array['headers']);
        return $instance;
    }
}
