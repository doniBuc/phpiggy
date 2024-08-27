<?php

declare(strict_types=1);

namespace App\Config;

use Framework\App;
use App\Controllers\{
    AboutController,
    AuthController,
    HomeController,
    TransactionController,
    ReceiptController,
    ErrorController
};
use App\Middleware\{AuthRequiredMiddleware, GuestOnlyMiddleware};

function registerRoutes(App $app)
{
    $app->addGetRoutes('/', [HomeController::class, 'home'])->addSpecificMiddleware(AuthRequiredMiddleware::class);
    $app->addGetRoutes('/about', [AboutController::class, 'about']);
    $app->addGetRoutes('/register', [AuthController::class, 'registerView'])->addSpecificMiddleware(GuestOnlyMiddleware::class);
    $app->addPostRoutes('/register', [AuthController::class, 'register'])->addSpecificMiddleware(GuestOnlyMiddleware::class);
    $app->addGetRoutes('/login', [AuthController::class, 'loginView'])->addSpecificMiddleware(GuestOnlyMiddleware::class);
    $app->addPostRoutes('/login', [AuthController::class, 'login'])->addSpecificMiddleware(GuestOnlyMiddleware::class);
    $app->addGetRoutes('/logout', [AuthController::class, 'logout'])->addSpecificMiddleware(AuthRequiredMiddleware::class);
    $app->addGetRoutes('/transaction', [TransactionController::class, 'createView'])->addSpecificMiddleware(AuthRequiredMiddleware::class);
    $app->addPostRoutes('/transaction', [TransactionController::class, 'create'])->addSpecificMiddleware(AuthRequiredMiddleware::class);
    $app->addGetRoutes('/transaction/{transaction}', [TransactionController::class, 'editView']);
    $app->addPostRoutes('/transaction/{transaction}', [TransactionController::class, 'edit']);
    $app->addDeleteRoutes('/transaction/{transaction}', [TransactionController::class, 'delete']);
    $app->addGetRoutes('/transaction/{transaction}/receipt', [ReceiptController::class, 'uploadView']);
    $app->addPostRoutes('/transaction/{transaction}/receipt', [ReceiptController::class, 'upload']);
    $app->addGetRoutes('/transaction/{transaction}/receipt/{receipt}', [ReceiptController::class, 'download']);
    $app->addDeleteRoutes('/transaction/{transaction}/receipt/{receipt}', [ReceiptController::class, 'delete']);

    $app->setErrorHandler([ErrorController::class, 'notFound']);
}
