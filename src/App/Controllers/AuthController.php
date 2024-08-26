<?php

declare(strict_types=1);

namespace App\Controllers;


use Framework\TemplateEngine;
use App\Services\{ValidatorService, UserService};


class AuthController
{

    public function __construct(
        private TemplateEngine $view,
        private ValidatorService $validatorService,
        private UserService $userService
    ) {}

    public function registerView()
    {
        // echo $this->view->render('register.php', ['errors' => $_SESSION['errors']]); pwede gamitin it to inject error data sa template, but dahil if marami tayo form we repeat this process to better gumawa ng middleware ->FlashMidleware 
        echo $this->view->render('register.php');
    }
    public function register()
    {

        $this->validatorService->validateRegister($_POST);
        $this->userService->isEmailTaken(($_POST['email']));


        $this->userService->createUser($_POST);

        redirecTo('/');
    }

    public function loginView()
    {
        echo $this->view->render('login.php');
    }

    public function login()
    {

        $this->validatorService->validateLogin($_POST);
        $this->userService->login($_POST);

        redirecTo('/');
    }

    public function logout()
    {
        unset($_SESSION['user']);

        session_regenerate_id();

        redirecTo('/login');
    }
}
