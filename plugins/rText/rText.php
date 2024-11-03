<?php

namespace plugins\rText;

use app\interfaces\IPlugin;
use app\router\Router;
use plugins\rText\controllers\rTextController;

class rText implements IPlugin
{
    public static function init()
    {
        Router::put("/rText/update", rTextController::update());
    }
}
