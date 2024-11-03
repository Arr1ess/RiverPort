<?php

namespace app\models;

use ArrayAccess;
use Stringable;

abstract class Model implements Stringable, ArrayAccess
{
    protected $params = [];
    abstract protected function defineFields(): array;

    public function __construct(array $params = [], ...$args)
    {
        $this->defineFields();
        $this->update($params + $args);
    }

    public function validate(): bool
    {
        foreach ($this->params as $key => $value) {
            if ($value === null) return false;
        }
        return true;
    }

    public function __toString(): string
    {
        return json_encode($this->params, JSON_NUMERIC_CHECK | JSON_PRETTY_PRINT |
            JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR);
    }

    public function __get($name)
    {
        if (array_key_exists($name, $this->params)) {
            return $this->params[$name];
        }
        return null;
    }

    public function __set($name, $value)
    {
        if (array_key_exists($name, $this->params)) {
            $this->params[$name] = $value;
        }
    }

    protected function createFields(...$params): array
    {
        $arr = [];
        foreach ($params as $key => $value) {
            $arr[$value] = null;
        }
        return $arr;
    }

    public function toArray(): array
    {
        return $this->params;
    }

    public function update(array $data = [], ...$args): void
    {
        $data += $args;
        $filteredParameters = array_intersect_key($data, $this->params);
        $this->params = array_merge($this->params, $filteredParameters);
    }

    public function hasField($name): bool
    {
        return array_key_exists($name, $this->params);
    }

    public function unsetField($name): void
    {
        if (array_key_exists($name, $this->params)) {
            unset($this->params[$name]);
        }
    }

    public function clone(): self
    {
        return new static($this->params);
    }

    public function equals(Model $other): bool
    {
        return $this->params === $other->params;
    }

    public function getFields(): array
    {
        return array_keys($this->params);
    }

    public function offsetExists($offset): bool
    {
        return $this->hasField($offset);
    }

    public function offsetGet($offset): mixed
    {
        return $this->__get($offset);
    }

    public function offsetSet($offset, $value): void
    {
        $this->__set($offset, $value);
    }

    public function offsetUnset($offset): void
    {
        $this->unsetField($offset);
    }
}