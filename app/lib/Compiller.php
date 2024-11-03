<?php

namespace app\lib;

abstract class Compiller implements Compile
{
    abstract public static function save();
    abstract public static function load();

    protected static function saveArray(string $filePath, array $arr)
    {
        $content = var_export($arr, 1);
        file_put_contents($filePath, "<?php\nreturn $content;");
    }

    protected static function loadArrFromFile(string $filePath, array &$arr)
    {
        $arr = include($filePath);
    }
}
