<?php

namespace App\database;

use app\database\_Types;
use app\database\Column;
use pdoWrapper;

class Schema
{
    private static $pdo;

    private static function getConnection()
    {
        if (!self::$pdo) {
            self::$pdo = pdoWrapper::connection();
        }
        return self::$pdo;
    }

    public static function create(string $tableName, callable $callback)
    {
        $columns = [];
        $callback($columns);

        $sql = "CREATE TABLE $tableName (";
        $sql .= implode(', ', $columns);
        $sql .= ")";
        var_dump(($columns));
        // self::getConnection()->exec($sql);
    }

    public static function dropIfExists($tableName)
    {
        $sql = "DROP TABLE IF EXISTS $tableName";
        self::getConnection()->exec($sql);
    }

    public static function table($tableName, $callback)
    {
        $columns = [];
        $callback($columns);

        foreach ($columns as $column) {
            $sql = "ALTER TABLE $tableName $column";
            self::getConnection()->exec($sql);
        }
    }

    public static function hasTable($tableName)
    {
        $sql = "SHOW TABLES LIKE '$tableName'";
        $stmt = self::getConnection()->query($sql);
        return $stmt->rowCount() > 0;
    }

    public static function hasColumn($tableName, $columnName)
    {
        $sql = "SHOW COLUMNS FROM $tableName LIKE '$columnName'";
        $stmt = self::getConnection()->query($sql);
        return $stmt->rowCount() > 0;
    }

    public static function addColumn($tableName, $columnName, $columnType)
    {
        $sql = "ALTER TABLE $tableName ADD COLUMN $columnName $columnType";
        self::getConnection()->exec($sql);
    }

    public static function dropColumn($tableName, $columnName)
    {
        $sql = "ALTER TABLE $tableName DROP COLUMN $columnName";
        self::getConnection()->exec($sql);
    }

    public static function changeColumn($tableName, $columnName, $newColumnType)
    {
        $sql = "ALTER TABLE $tableName MODIFY COLUMN $columnName $newColumnType";
        self::getConnection()->exec($sql);
    }
}


