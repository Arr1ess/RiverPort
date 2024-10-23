<?php

use app\router\Router;

include_once __DIR__ . "/app/helpers/helpers.php";

includeAllRoutes();

Router::dispatch();

