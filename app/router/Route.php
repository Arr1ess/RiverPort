<?php

namespace app\router;

use app\models\UserRole;
use app\router\responses\Forbidden;

class Route
{
    private $responseFunction;

    public function __construct(callable $responseFunction, private ?string $accessRole = null)
    {
        $this->responseFunction = $responseFunction;
    }

    public function execute()
    {
        if (!UserRole::accessCheck($this->accessRole)) {
            (new Forbidden())->send();
            include_once SERVER_NAME . "/app/views/pages/404.php";
            exit;
        }
        $response = call_user_func($this->responseFunction);
        if ($response instanceof Response) {
            $response->send();
        } else {
            throw new \Exception('The response function must return an instance of Response.');
        }
        exit;
    }
    public function access(string $role)
    {
        $this->accessRole = $role;
        return $this;
    }
    public static function __set_state($array)
    {
        $instance = new self($array['responseFunction']);
        return $instance;
    }
    public function render(): string
    {
        $answer =  "new app\\router::Route(";
        $answer .= var_export($this->responseFunction, 1);
        $answer .= ",";
        $answer .= $this->accessRole;
        return $answer . ")\n";
    }
}
