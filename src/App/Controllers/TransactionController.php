<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Services\TransactionService;
use App\Services\ValidatorService;
use Framework\TemplateEngine;

class TransactionController
{
    public function __construct(
        private TemplateEngine $view,
        private ValidatorService $validatorService,
        private TransactionService $transactionService
    ) {}

    public function createView()
    {
        echo $this->view->render("transactions/create.php");
    }

    public function create()
    {
        $this->validatorService->validateTransaction($_POST);

        $this->transactionService->createTransaction($_POST);

        redirecTo('/');
    }

    public function editView(array $params)
    {
        $transaction = $this->transactionService->getUserTransaction($params['transaction']);

        if (!$transaction) {
            redirecTo('/');
        }

        echo $this->view->render('transactions/edit.php', [
            'transaction' => $transaction
        ]);
    }

    public function edit(array $params)
    {

        $this->validatorService->validateTransaction($_POST);


        $transaction = $this->transactionService->getUserTransaction($params['transaction']);

        if (!$transaction)
            redirecTo('/');

        $this->transactionService->updateTransaction($_POST, $transaction['id']);

        redirecTo($_SERVER['HTTP_REFERER']);
    }

    public function delete(array $params)
    {

        $this->transactionService->deleteTransaction($params['transaction']);

        redirecTo('/');
    }
}
