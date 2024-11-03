<?php

namespace plugins\rBlock\controllers;

use app\router\Response;
use app\router\responses\TextResponse;
use Exception;
use plugins\rBlock\models\Template;

class Container
{
    public static function create(string $container_path, Template $template, string $template_path)
    {
        if (self::getBlocks($container_path) === null)
            self::exportBlocks($container_path, [], $template, $template_path);
    }

    public static function renderContainer(string $container_path)
    {
        // echo "hi";
        $data = self::getBlocks($container_path);
        if ($data === null) {
            return; // или выбросить исключение, если нужно
        }
        [$template_path, $template, $blocks] = $data;
        include $template_path;
    }

    public static function removeBlock(string $container_path): Response
    {
        try {
            // Получаем данные из тела запроса
            $input = file_get_contents('php://input');
            $data = json_decode($input, true);

            // Проверяем наличие обязательного параметра block_id
            if (!isset($data['block_id'])) {
                return new TextResponse(400, "block_id not specified");
            }

            // Преобразуем block_id в целое число
            $block_id = (int)$data['block_id'];

            // Получаем блоки и шаблон
            $data = self::getBlocks($container_path);
            if ($data === null) {
                return new TextResponse(500, "matching container not found");
            }
            [$template_path, $template, $blocks] = $data;

            // Проверяем наличие блока с указанным ID
            if (!isset($blocks[$block_id])) {
                return new TextResponse(400, "the specified block does not exist");
            }

            // Фильтруем блоки, удаляя блок с указанным ID
            $blocks = array_filter($blocks, function ($id) use ($block_id) {
                return $id != $block_id;
            }, ARRAY_FILTER_USE_KEY);

            // Экспортируем обновленные блоки
            self::exportBlocks($container_path, $blocks, $template, $template_path);

            // Возвращаем успешный ответ
            return new TextResponse(204);
        } catch (Exception $e) {
            // Обрабатываем исключения
            return new TextResponse(500, "Internal Server Error: " . $e->getMessage());
        }
    }

    public static function addBlock(string $container_path, array $parametrs): int
    {
        $data = self::getBlocks($container_path);
        if ($data === null) {
            return -1; // или выбросить исключение, если нужно
        }
        // array_walk($data, function ($value, $key) {
        //     echo "<br/>$key<br/>";
        //     var_dump($value);
        // });
        [$template_path, $template, $blocks] = $data;
        $template->checkParametrs($parametrs);
        $blocks[] = $parametrs;
        self::exportBlocks($container_path, $blocks, $template, $template_path);
        return sizeof($blocks) - 1;
    }

    public static function updateBlock(string $container_path, int $block_id, array $newBlock): bool
    {
        $data = self::getBlocks($container_path);
        if ($data === null) {
            return false;
        }
        [$template_path, $template, $blocks] = $data;
        if (!isset($blocks[$block_id])) {
            return false;
        }
        $blocks[$block_id] = array_merge($blocks[$block_id], $newBlock);
        $template->checkParametrs($newBlock);
        self::exportBlocks($container_path, $blocks, $template, $template_path);
        return true;
    }

    private static function getBlocks(string $filename): ?array
    {
        if (!file_exists($filename)) return null;
        else {
            $arr = include($filename);
            return (is_array($arr)) ? $arr : null;
        }
    }

    private static function exportBlocks(string $filename, array $blocks, Template $template, string $template_path)
    {
        $contents = "<?php return ['$template_path', " . var_export($template, true) . ", " . var_export($blocks, true) . "];";
        file_put_contents($filename, $contents);
        // echo "файл создан по пуьт - $filename";
    }
}
