<?php

namespace plugins\rBlock\models;

class Template
{
    public function __construct(public ?string $template, private array $parameters)
    {
        // echo $this->template . "<br/><br/>";
        if (!file_exists($this->template)) {
            $this->template = null;
        }
    }

    public function render(array $parameters, $id = 0): void
    {
        extract($parameters, EXTR_SKIP);
        include $this->template ?? SERVER_NAME . "/plugins/rBlock/views/templates/bad_template.php";
    }

    public function checkParametrs(array &$parameters): void
    {
        $filteredParameters = array_intersect_key($parameters, $this->parameters);
        $parameters = array_merge($this->parameters, $filteredParameters);
    }

    public static function __set_state($array)
    {
        $instance = new self($array['template'], $array['parameters']);
        return $instance;
    }
}
