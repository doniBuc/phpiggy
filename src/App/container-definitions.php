<?php

declare(strict_types=1);

use App\Config\Paths;
use App\Services\ValidatorService;
use Framework\TemplateEngine;
use Framework\Database;

return [
    TemplateEngine::class => fn() => new TemplateEngine(Paths::VIEW),
    ValidatorService::class => fn() => new ValidatorService(),
    ValidatorService::class => fn() => new Database(
        'mysql',
        ['host' => 'localhost', 'port' => 3306, 'dbname' => 'phpiggy'],
        'root',
        ''
    )
];
