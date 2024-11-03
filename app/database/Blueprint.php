<?php

namespace App\database;

class Blueprint
{
    protected $columns = [];

    public function id()
    {
        $this->columns[] = 'id INT AUTO_INCREMENT PRIMARY KEY';
    }

    public function string($name, $length = 255)
    {
        $this->columns[] = "$name VARCHAR($length)";
    }

    public function integer($name)
    {
        $this->columns[] = "$name INT";
    }

    public function timestamps()
    {
        $this->columns[] = 'created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP';
        $this->columns[] = 'updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP';
    }

    public function unique()
    {
        $lastColumn = array_pop($this->columns);
        $this->columns[] = "$lastColumn UNIQUE";
    }

    public function nullable()
    {
        $lastColumn = array_pop($this->columns);
        $this->columns[] = "$lastColumn NULL";
    }

    public function getColumns()
    {
        return $this->columns;
    }
}