<?php
// ini_set('memory_limit', '255M');

// echo ini_get('memory_limit');
// // phpinfo();

// echo "<pre>";
// print_r($_SERVER);
// echo "</pre>";

include __DIR__ . "/../src/App/function.php";

$app = include __DIR__ . "/../src/App/bootstrap.php";

$app->run();
