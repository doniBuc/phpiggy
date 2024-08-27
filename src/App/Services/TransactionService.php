<?php

declare(strict_types=1);

namespace App\Services;

use Framework\Database;

class TransactionService
{

    public function __construct(private Database $db) {}

    public function createTransaction(array $transactionData)
    {
        $formattedDate = "{$transactionData['date']} 00:00:00"; // MariaDB DateTime accepted Value YYYY-MM-DD HH:MM:SSs

        $this->db->prepare(
            "INSERT INTO transactions(user_id, description, amount, date) 
            VALUES(:user_id,:description,:amount,:date)",
            [
                'user_id' => $_SESSION['user'],
                'description' => $transactionData['description'],
                'amount' => $transactionData['amount'],
                'date' => $formattedDate
            ]
        );
    }

    public function getUserTransactions(int $length, int $offset)
    {

        $searchTerm = addcslashes($_GET['s'] ?? '', '%_'); // query parameter and addcslashes(value to be escape, list of character should be escape) -> fn for escaping character in sql

        $params = [
            'user_id' => $_SESSION['user'],
            'description' => "%{$searchTerm}%"
        ];

        $transactions = $this->db->prepare(
            "SELECT *, DATE_FORMAT(date ,'%Y-%m-%d') as formatted_date
            FROM transactions
            WHERE user_id = :user_id
            AND description LIKE :description
            LIMIT {$length} OFFSET {$offset}", // LIMIT ${length} OFFSET {$offset} using OFFSET KEYWORD 
            $params
        )->getAll();

        $transactions = array_map(function (array $transaction) {

            $transaction['receipts'] = $this->db->prepare(
                "SELECT * FROM receipts WHERE transaction_id = :transaction_id",
                [
                    'transaction_id' => $transaction['id']
                ]
            )->getAll();
            return $transaction;
        }, $transactions);

        $transactionCount = $this->db->prepare(
            "SELECT COUNT(*)
            FROM transactions
            WHERE user_id = :user_id
            AND description LIKE :description",
            $params
        )->count();

        // return $transactions;

        // by using the array we able to return multiple values
        return [$transactions, $transactionCount];
    }

    public function getUserTransaction(string $id)
    {
        return $this->db->prepare(
            "SELECT *, DATE_FORMAT(date, '%Y-%m-%d') as formatted_date
        FROM transactions
        WHERE id = :id AND user_id = :user_id",
            [
                'id' => $id,
                'user_id' => $_SESSION['user']
            ]
        )->find();
    }


    public function updateTransaction(array $transactionData, int $id)
    {

        $formattedDate = "{$transactionData['date']} 00:00:00)";

        $this->db->prepare(
            "UPDATE transactions
        SET description = :description,
        amount = :amount,
        date = :date
        WHERE id=:id
        AND user_id = :user_id",
            [
                'description' => $transactionData['description'],
                'amount' => $transactionData['amount'],
                'date' => $formattedDate,
                'id' => $id,
                'user_id' => $_SESSION['user']

            ]
        );
    }

    public function deleteTransaction(string $id)
    {

        $this->db->prepare(
            "DELETE FROM transactions 
    WHERE id = :id 
    AND user_id = :user_id ",
            [
                'id' => $id,
                'user_id' => $_SESSION['user']
            ]
        );
    }
}
