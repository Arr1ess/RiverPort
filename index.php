<?php

use app\models\UserRole;
use plugins\rText\rText;
use app\router\Router;


include_once __DIR__ . "/app/helpers/helpers.php";


includeAllFilesFromFolder(__DIR__ . "/config");
includeAllRoutes();
scanAndInitPlugins();

UserRole::set("admin");




rText::init();


Router::dispatch();
