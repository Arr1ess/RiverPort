<?php

namespace app\lib;

use ArrayAccess;
use ArrayIterator;
use Countable;
use IteratorAggregate;
use Traversable;



class cArray implements ArrayAccess, Countable, IteratorAggregate
{
    private string $filePath;
    private const PAGE_PATH = SERVER_NAME . "/public/uploads/compile/";
    public function __construct(string $fileName, private array $container = [], bool $fullPath = false)
    {
        $this->filePath = $fullPath ? $fileName : self::PAGE_PATH . $fileName . ".php";
        if (!DEBUG_MODE) {
            $this->container = file_exists($this->filePath) ? include($this->filePath) : [];
        } else {
            // $content = var_export($this->container, 1);
            // file_put_contents($this->filePath, "<?php\nreturn $content;");
        }
    }

    // Реализация методов интерфейса ArrayAccess
    public function offsetExists($offset): bool
    {
        return isset($this->container[$offset]);
    }

    public function offsetGet($offset): mixed
    {
        if (isset($this->container[$offset])) {
            return $this->container[$offset];
        }
        $null = null;
        return $null;
    }

    public function offsetSet($offset, $value): void
    {
        if (!DEBUG_MODE) return;
        if (is_null($offset)) {
            $this->container[] = $value;
        } else {
            $this->container[$offset] = $value;
        }
        // $content = var_export($this->container, 1);
        // file_put_contents($this->filePath, "<?php\nreturn $content;");
    }

    public function offsetUnset($offset): void
    {
        if (DEBUG_MODE) unlink($this->filePath);
        unset($this->container[$offset]);
    }

    public static function __set_state($array)
    {
        $instance = new self($array['filePath'], $array['container'], true);
        return $instance;
    }

    // Реализация методов интерфейса Countable
    public function count(): int
    {
        return count($this->container);
    }

    // Реализация методов интерфейса IteratorAggregate
    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->container);
    }

    public function toArray(): array
    {
        return $this->container;
    }

    public function render(): string
    {
        $answer = "[";
        foreach ($this->container as $key => $value) {
            $answer .= "'$key'=>";
            if (is_object($value)) {
                if (method_exists($value, "render")) {
                    $answer .= $value->render();
                } else {
                    $answer .= var_export($value, 1);
                }
            } else {
                $answer .= "'$value'";
            }
            $answer .= ",";
        }
        return $answer . "]\n";
    }
}
