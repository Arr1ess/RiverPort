<?php

namespace app\database;

class _Types
{
    public static function getInt($params = ''): string
    {
        return "INT";
    }

    public static function getTinyInt($params = ''): string
    {
        return "TINYINT";
    }

    public static function getSmallInt($params = ''): string
    {
        return "SMALLINT";
    }

    public static function getMediumInt($params = ''): string
    {
        return "MEDIUMINT";
    }

    public static function getBigInt($params = ''): string
    {
        return "BIGINT";
    }

    public static function getFloat($params = ''): string
    {
        return "FLOAT";
    }

    public static function getDouble($params = ''): string
    {
        return "DOUBLE";
    }

    public static function getDecimal($params = ''): string
    {
        if (is_array($params) && count($params) === 2) {
            return "DECIMAL({$params[0]}, {$params[1]})";
        }
        return "DECIMAL";
    }

    public static function getVarchar($params = 255): string
    {
        return "VARCHAR($params)";
    }

    public static function getChar($params = 255): string
    {
        return "CHAR($params)";
    }

    public static function getText($params = ''): string
    {
        return "TEXT";
    }

    public static function getTinyText($params = ''): string
    {
        return "TINYTEXT";
    }

    public static function getMediumText($params = ''): string
    {
        return "MEDIUMTEXT";
    }

    public static function getLongText($params = ''): string
    {
        return "LONGTEXT";
    }

    public static function getDate($params = ''): string
    {
        return "DATE";
    }

    public static function getTime($params = ''): string
    {
        return "TIME";
    }

    public static function getDateTime($params = ''): string
    {
        return "DATETIME";
    }

    public static function getTimestamp($params = ''): string
    {
        return "TIMESTAMP";
    }

    public static function getYear($params = ''): string
    {
        return "YEAR";
    }

    public static function getBinary($params = 255): string
    {
        return "BINARY($params)";
    }

    public static function getVarBinary($params = 255): string
    {
        return "VARBINARY($params)";
    }

    public static function getBlob($params = ''): string
    {
        return "BLOB";
    }

    public static function getTinyBlob($params = ''): string
    {
        return "TINYBLOB";
    }

    public static function getMediumBlob($params = ''): string
    {
        return "MEDIUMBLOB";
    }

    public static function getLongBlob($params = ''): string
    {
        return "LONGBLOB";
    }

    public static function getBoolean($params = ''): string
    {
        return "BOOLEAN";
    }

    public static function getEnum($params = []): string
    {
        if (!is_array($params) || empty($params)) {
            throw new \InvalidArgumentException("ENUM requires an array of values");
        }
        $values = implode(', ', array_map(function ($value) {
            return "'$value'";
        }, $params));
        return "ENUM($values)";
    }

    public static function getJson($params = ''): string
    {
        return "JSON";
    }
}

class Column
{
    private array $modifiers = [];

    private function __construct(private string $name, private string $type)
    {
        // $this->name .= " $type";
    }

    public function unique(): self
    {
        $this->modifiers[] = 'UNIQUE';
        return $this;
    }

    public function nullable(): self
    {
        $this->modifiers[] = 'NULL';
        return $this;
    }

    public function notNullable(): self
    {
        $this->modifiers[] = 'NOT NULL';
        return $this;
    }

    public function primary(): self
    {
        $this->modifiers[] = 'PRIMARY KEY';
        return $this;
    }

    public function autoIncrement(): self
    {
        $this->modifiers[] = 'AUTO_INCREMENT';
        return $this;
    }

    public function default($value): self
    {
        $this->modifiers[] = "DEFAULT $value";
        return $this;
    }

    public static function __callStatic($name, $arguments)
    {
        $methodName = 'get' . ucfirst($name);
        if (method_exists(_Types::class, $methodName)) {
            if (count($arguments) < 1) {
                throw new \InvalidArgumentException("Column name is required");
            }
            $column_name  = array_shift($arguments);
            return new Column($column_name, _Types::$methodName(...$arguments));
        }
        throw new \BadMethodCallException("Method $methodName does not exist");
    }

    public static function INT($name): self
    {
        return new self($name, _Types::getInt());
    }

    public static function VARCHAR($name, ...$params): self
    {
        return new self($name, _Types::getVarchar(...$params));
    }

    public static function DECIMAL($name, ...$params): self
    {
        return new self($name, _Types::getDecimal(...$params));
    }

    public static function ENUM($name, ...$params): self
    {
        return new self($name, _Types::getEnum(...$params));
    }

    public function __toString()
    {
        $modifiers = implode(' ', $this->modifiers);
        return "$this->name $this->type $modifiers";
    }
}
