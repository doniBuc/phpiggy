<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Config\Paths;
use Framework\TemplateEngine;

class HomeController
{
    private TemplateEngine $view;

    public function __construct()
    {
        $this->view = new TemplateEngine(Paths::VIEW); // we can hard code the path of tempplate view pero dahil mag rereference tayo ng ibat - ibang view path mas mainam meron isang single file contain all path -> Config folder
    }

    public function home()
    {
        echo $this->view->render("index.php", ["title" => "Home Page"]);
    }
}
