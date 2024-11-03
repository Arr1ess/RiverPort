<?php


namespace plugins\rText\models;

class rTextModel
{
    private string $file;
    private string $text = "can't connect to file";

    public function __construct(string $filename, string $text = "")
    {
        $this->file = SERVER_NAME . "/public/uploads/$filename.php";

        if (file_exists($this->file)) { //если файл уже существует
            $this->text = file_get_contents($this->file);
        } else {
            $this->setText($text);
        }
    }

    public function getText(): string
    {
        return $this->text;
    }

    public function setText(string $text)
    {
        if (file_put_contents($this->file, $text)) {
            $this->text = $text;
            return true;
        }
        return false;
    }

    public function clear()
    {
        $this->setText("");
    }

    public function delete()
    {
        rmdir($this->file);
        // удаление или блокировка класса
    }
}
