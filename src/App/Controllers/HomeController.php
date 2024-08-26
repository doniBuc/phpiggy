<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Config\Paths;
use Framework\TemplateEngine;
use App\Services\TransactionService;

class HomeController
{
    // private TemplateEngine $view; // dahil gumawa na tayo ng container kaya niremove na natin ito

    public function __construct(
        private TemplateEngine $view,
        private TransactionService $transactionService
    ) {
        // $this->view = new TemplateEngine(Paths::VIEW); // we can hard code the path of tempplate view pero dahil mag rereference tayo ng ibat - ibang view path mas mainam meron isang single file contain all path -> Config folder
    }

    public function home()
    {
        $pageNumber = $_GET['p'] ?? 1;
        $pageNumber = (int) $pageNumber;
        $searchTerm = $_GET['s'] ?? null;

        $length = 3;
        $offset = ($pageNumber - 1) * $length;

        [$transactions, $count] = $this->transactionService->getUserTransactions($length, $offset);

        $lastPage = ceil($count / $length);

        //Pagelinks
        $pages = $lastPage ? range(1, $lastPage) : [];
        $pageLinks = array_map(
            fn($pageNum) => http_build_query([
                'p' => $pageNum,
                's' => $searchTerm
            ]),
            $pages
        );


        echo $this->view->render("index.php", [
            'transactions' => $transactions,
            'currentPage' => $pageNumber,
            'previousPageQuery' => http_build_query( // no need na ng 2nd arg na arg_seperator by default it used ampersand &
                [
                    'p' => $pageNumber - 1,
                    's' => $searchTerm
                ]
            ),
            'lastPage' => $lastPage,
            'nextPageQuery' => http_build_query(
                [
                    'p' => $pageNumber + 1,
                    's' => $searchTerm
                ]
            ),
            'pageLinks' => $pageLinks,
            'searchTerm' => $searchTerm
        ]);
    }
}
