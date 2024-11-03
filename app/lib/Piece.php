<?php

namespace plugins\Component\models;

abstract class Piece
{
    private static string $text;
    public static function load(string $text)
    {
        self::$text = $text;
    }
    public static function con()
    {
        echo "<!-- " . get_called_class() . " -->";
        echo self::$text;
    }
}
