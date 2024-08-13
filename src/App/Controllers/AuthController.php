<?php

declare(strict_types=1);

namespace App\Controllers;

use Framework\TemplateEngine;
use App\Services\ValidatorService;


class AuthController
{

    public function __construct(
        private TemplateEngine $view,
        private ValidatorService $validatorService
    ) {}

    public function register()
    {
        echo $this->view->render('register.php', ["title" => "Register"]);
    }
    public function registered()
    {
        $this->validatorService->validateRegister($_POST);
    }
}
