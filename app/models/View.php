<?php

namespace app\models;

class View
{
    public function __construct(public string $template, public array $parameters) {}
    public function render(string $prefix = ''): void
    {
        if (!file_exists($this->template)) return;
        extract($this->parameters, EXTR_PREFIX_SAME, $prefix);
        include $this->template;
    }
    public function export(): string
    {
        $template = addslashes($this->template);
        $parameters = $this->parameters;
        $exported = "['" . $template . "', " . var_export($parameters, true) . "]";
        return $exported;
    }

    public static function __set_state($array)
    {
        $instance = new self($array['template'], $array['parameters']);
        return $instance;
    }


}
