<?php

namespace app\router;

abstract class Response
{
    private $code;
    protected $body;
    protected $headers = [];

    public function __construct($code = 200, $body = '', $headers = [])
    {
        $this->code = $code;
        $this->body = $body;
        $this->headers = $headers;
    }

    public function getCode()
    {
        return $this->code;
    }

    public function setCode($code)
    {
        $this->code = $code;
    }

    public function getBody()
    {
        return $this->body;
    }

    public function setBody($body)
    {
        $this->body = $body;
    }

    public function addHeader($name, $value)
    {
        $this->headers[$name] = $value;
    }

    public function removeHeader($name)
    {
        unset($this->headers[$name]);
    }

    abstract public function send();

    public function __invoke()
    {
        return $this;
    }
}
