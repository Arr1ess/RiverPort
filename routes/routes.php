<?php

use app\router\responses\HtmlResponse;
use app\router\Router;

Router::get("/", new HtmlResponse("hi"));
Router::get("/home", page(__DIR__ . "/../app/views/home.php"));