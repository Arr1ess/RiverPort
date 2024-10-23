<?php

use app\router\responses\RedirectResponse;
use app\router\Router;

Router::get("/", view("start.php"));
Router::get("/home", view("home.php"));
Router::get("/admin", new RedirectResponse("/"));