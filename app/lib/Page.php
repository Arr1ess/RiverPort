<?php

namespace app\lib;

use app\router\responses\HtmlResponse as ResponsesHtmlResponse;
use app\router\responses\JsonResponse;

class Page
{
    private array $scripts = [];
    private array $styles = [];
    private array $seoData = [];
    private bool $isSinglePageAplication = false;

    public function __construct(private string $filePath)
    {
        $queryString = $_SERVER['QUERY_STRING'] ?? '';
        parse_str($queryString, $queryParams);
        if (isset($queryParams['singlePageApplication']) && $queryParams['singlePageApplication'] === 'true') {
            $this->isSinglePageAplication = true;
        }
    }

    public function addScript(string $src, array $attributes = [])
    {
        $this->scripts[] = ['src' => "/public/js/$src", 'attributes' => $attributes];
    }

    public function addStyle(string $href, array $attributes = [])
    {
        $this->styles[] = ['href' => "/public/css/$href", 'attributes' => $attributes];
    }

    public function setSeoData(array $seoData)
    {
        $this->seoData = $seoData;
    }

    private function renderSeoTags()
    {
        $seoData = $this->seoData;
        include __DIR__ . "/../views/seo_meta_tags.php";
    }

    private function renderScripts()
    {
        $scriptsHtml = '';
        foreach ($this->scripts as $script) {
            $attributes = '';
            foreach ($script['attributes'] as $name => $value) {
                $attributes .= " $name=\"$value\"";
            }
            $scriptsHtml .= "<script src=\"{$script['src']}\"$attributes></script>";
        }
        return $scriptsHtml;
    }

    private function renderStyles()
    {
        $stylesHtml = '';
        foreach ($this->styles as $style) {
            $attributes = '';
            foreach ($style['attributes'] as $name => $value) {
                $attributes .= " $name=\"$value\"";
            }
            $stylesHtml .= "<link href=\"{$style['href']}\" rel=\"stylesheet\"$attributes>";
        }
        return $stylesHtml;
    }

    private function getBody()
    {
        include $this->filePath;
    }

    public function render()
    {
        ob_start();
        $this->getBody();
        $body = ob_get_clean();



        if ($this->isSinglePageAplication) {
            return new JsonResponse(['body' => $body, 'scripts' => $this->scripts, 'styles' => $this->styles, 'seoTags' => $this->seoData]);
        } else {
            $seoTags = $this->renderSeoTags();
            $scripts = $this->renderScripts();
            $styles = $this->renderStyles();

            $page = <<<HTML
            <!DOCTYPE html>
            <html lang="ru">
            <head>
                $seoTags
                $styles
            </head>
            <body>
                $body
                $scripts
            </body>
            </html>
            HTML;

            return new ResponsesHtmlResponse($page);
        }
    }

    public static function __set_state($array)
    {
        $instance = new self($array['filePath']);
        return $instance;
    }
}
