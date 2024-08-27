<?php

declare(strict_types=1);

namespace App\Config;

class Paths
{
    public const VIEW = __DIR__ . "/../views"; //views directory
    public const SOURCE = __DIR__ . "/../../"; // this path point to src dir of our project -> bootstrap.php 
    public const ROOT = __DIR__ . "/../../../";
    public const STORAGE_UPLOAD = __DIR__ . "/../../../storage/uploads";
}
