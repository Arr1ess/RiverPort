<?php


namespace app\router\responses;

use app\router\Response;

class RedirectResponse extends Response
{
    private $location;

    public function __construct($location, $code = 302, $headers = [])
    {
        parent::__construct($code, '', $headers);
        $this->location = $location;
        $this->addHeader('Location', $location);
    }

    public function send()
    {
        http_response_code($this->getCode());
        foreach ($this->headers as $name => $value) {
            header("$name: $value");
        }
    }
}
