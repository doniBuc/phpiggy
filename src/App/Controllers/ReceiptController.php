<?php

declare(strict_types=1);

namespace App\Controllers; {
}

use App\Services\{TransactionService, ReceiptService};
use Framework\TemplateEngine;

class ReceiptController
{


    public function __construct(
        private TemplateEngine $view,
        private TransactionService $transactionService,
        private ReceiptService $receiptService
    ) {}



    public function uploadView(array $params)
    {
        $transaction = $this->transactionService->getUserTransaction($params['transaction']);

        if (!$transaction)
            redirecTo('/');

        echo $this->view->render('receipts/create.php');
    }

    public function upload(array $params)
    {
        $transaction = $this->transactionService->getUserTransaction($params['transaction']);

        if (!$transaction) {
            redirecTo("/");
        }
        // dd($_FILES[]); //super global variables it contains an array of files upload in the server

        $receiptFile = $_FILES['receipt'] ?? null;

        $this->receiptService->validateFile($receiptFile);

        $this->receiptService->upload($receiptFile);




        redirecTo("/");
    }
}
