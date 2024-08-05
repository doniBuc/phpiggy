<?php

declare(strict_types=1);


// include __DIR__ . "/../Framework/App.php"; // using absolute url path
require  __DIR__ . "/../../vendor/autoload.php"; // when you use autoloader frm composer

use Framework\App;

$app = new App();

return $app;
