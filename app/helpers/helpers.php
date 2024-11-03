<?php

use app\lib\Page;

function includeAllFilesFromFolder($folderPath)
{
    // Проверяем, существует ли папка
    if (!is_dir($folderPath)) {
        trigger_error("Папка '$folderPath' не существует.", E_USER_WARNING);
        return;
    }

    // Ищем все PHP-файлы в папке
    $files = glob($folderPath . '/*.php');

    // Подключаем каждый файл
    foreach ($files as $file) {
        if (is_file($file)) {
            include_once $file;
        }
    }
}

function scanAndInitPlugins()
{
    $pluginsDir = SERVER_NAME . "/plugins";
    if (!is_dir($pluginsDir)) {
        echo "Директория с плагинами не найдена.";
        return;
    }
    $pluginFolders = scandir($pluginsDir);

    foreach ($pluginFolders as $folder) {
        if ($folder === '.' || $folder === '..') {
            continue;
        }
        $pluginFilePath = $pluginsDir . "/$folder/$folder.php";
        if (file_exists($pluginFilePath)) {
            require_once $pluginFilePath;
            $pluginClassName = "plugins\\$folder\\$folder";
            if (class_exists($pluginClassName) && in_array('app\interfaces\IPlugin', class_implements($pluginClassName))) {
                $pluginClassName::init();
                echo "Подключен $pluginClassName <br/>";
            }
        }
    }
}


function includeAllRoutes()
{
    includeAllFilesFromFolder(__DIR__ . "/../../routes");
}

function view(string $pagePath)
{
    return [new Page(SERVER_NAME . "/app/views/$pagePath"), 'render'];
}

spl_autoload_register(function ($className) {
    $className = str_replace('\\', '/', $className);
    $filePath = SERVER_NAME . "/$className.php";

    if (file_exists($filePath)) {
        require_once $filePath;
    } else {
        throw new Exception("Class file not found: $filePath");
    }
});



function renderSinglePageAplictaion(Page $page)
{
    $page->addScript("singlePageAplication.js");
}
