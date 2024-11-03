<?php

use app\router\Router;

Router::get("/admin", view("pages/admin.php"))->access("admin");