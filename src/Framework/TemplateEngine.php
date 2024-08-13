<?php

declare(strict_types=1);

namespace Framework;

class TemplateEngine
{

    private array $globalTemplateData = [];

    public function __construct(private string $basePath) // basePath prop store the absolute path to the dir of our template
    {}

    public function render(string $template, array $data = [])
    {
        extract($data, EXTR_SKIP); // extract return the key of each array as variable 
        extract($this->globalTemplateData, EXTR_SKIP); // extra_skip -> prevent var to overwritten/skip the similar name

        ob_start();

        include $this->resolvePath($template);

        $output = ob_get_contents(); // return as a string 
        ob_end_clean();
        return $output;
    }

    public function resolvePath($path)
    {
        return  "{$this->basePath}/{$path}";
    }

    public function addGlobal(string $key, mixed $value)
    {
        $this->globalTemplateData[$key] = $value;
    }
}
