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

        $this->receiptService->upload($receiptFile, $transaction['id']);

        redirecTo("/");
    }

    public function delete(array $params)
    {
        $transaction = $this->transactionService->getUserTransaction($params['transaction']);

        if (empty($transaction)) {
            redirecTo('/');
        }

        $receipt = $this->receiptService->getReceipt($params['receipt']);

        if (empty($receipt)) {
            redirecTo('/');
        }

        if ($receipt['transaction_id'] !== $transaction['id']) {
            redirecTo('/');
        }

        $this->receiptService->delete($receipt);
    }


    public function download(array $params)
    {
        $transaction = $this->transactionService->getUserTransaction($params['transaction']);

        if (empty($transaction)) {
            redirecTo('/');
        }

        $receipt = $this->receiptService->getReceipt($params['receipt']);

        if (empty($receipt)) {
            redirecTo('/');
        }

        // validating tryig access an receipt from different transaction we should compare the id of transaction into id of reciept
        if ($receipt['transaction_id'] !== $transaction['id']) {
            redirecTo('/');
        }

        // grabbing the file 
        $this->receiptService->read($receipt);
    }
}
