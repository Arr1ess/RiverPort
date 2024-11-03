<?php

use app\lib\Page;
use app\router\Router;

$arr = [new Page(SERVER_NAME . "/app/views/pages/home.php"), 'render'];

Router::get("/", view("pages/start.php"));
Router::get("/index.php", view("pages/start.php"));
Router::get("/home", $arr);
Router::get("/404", view("pages/404.php"));

