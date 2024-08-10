<?php

declare(strict_types=1);

namespace Framework;

class TemplateEngine
{

    public function __construct(private string $basePath) // basePath prop store the absolute path to the dir of our template
    {}

    public function render(string $template, array $data = [])
    {
        extract($data, EXTR_SKIP); // extract return the key of each array as variable
        include "{$this->basePath}/{$template}";
    }
}
