<?php

use app\router\responses\HtmlResponse;
use app\router\responses\RedirectResponse;
use app\router\Router;

Router::get("/", new HtmlResponse("hi"));
Router::get("/home", view("home.php"));
Router::get("/admin", new RedirectResponse("/"));