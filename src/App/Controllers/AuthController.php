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
        // echo $this->view->render('register.php', ['errors' => $_SESSION['errors']]); pwede gamitin it to inject error data sa template, but dahil if marami tayo form we repeat this process to better gumawa ng middleware ->FlashMidleware 
        echo $this->view->render('register.php');
    }
    public function registered()
    {
        $this->validatorService->validateRegister($_POST);
    }
}
