<?php

namespace app\router;

class Route
{
    private $responseFunction;

    public function __construct(callable $responseFunction)
    {
        $this->responseFunction = $responseFunction;
    }

    public function execute()
    {
        $response = call_user_func($this->responseFunction);
        if ($response instanceof Response) {
            $response->send();
        } else {
            throw new \Exception('The response function must return an instance of Response.');
        }
        exit;
    }
}
