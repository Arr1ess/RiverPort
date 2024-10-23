<?php

use app\router\Router;


include_once __DIR__ . "/app/helpers/helpers.php";

includeAllFilesFromFolder(__DIR__ . "/config");
includeAllRoutes();

Router::dispatch();

