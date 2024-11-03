<?php

namespace plugins\rBlock;

use app\interfaces\IPlugin;
use app\models\View;
use app\router\Response;
use app\router\Router;
use InvalidArgumentException;
use plugins\rBlock\controllers\Container;
use plugins\rBlock\models\Template;

class rBlock implements IPlugin
{
    private const CONTAINER_PATH = __DIR__ .  "/public/uploads/containers/";
    private const TEMPLATE_PATH = __DIR__ . "/views/templates/";

    public static function init()
    {
        $files = glob(self::CONTAINER_PATH . "*.php");

        foreach ($files as $file) {
            $filename = pathinfo($file, PATHINFO_FILENAME);

            // Используем анонимную функцию для передачи $file в качестве аргумента
            Router::DELETE("/api/rBlock/$filename", function () use ($file) {
                return Container::removeBlock($file);
            });
        }
    }

    private static function deleteHandler($file): Response
    {
        return Container::removeBlock($file);
    }
}
