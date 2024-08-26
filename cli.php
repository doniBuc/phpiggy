<?php
// Refactoring this code seperate the logic

// $driver = 'mysql';

// // method for convert array into string url
$config = http_build_query(data: [
    "host" => "localhost",
    "port" => 3306,
    "dbname" => "phpiggy"
], arg_separator: ';');

// $dsn = "{$driver}:{$config}";
// $username = 'root';
// $password = '';
// try {
//     $db = new PDO($dsn, $username, $password); //create a connection to our db
// } catch (PDOException $e) // this class of catching error in PDO
// {
//     die("Unable to connect to database"); // calling this fn die() cause our script to stop running
// }

// echo "Connected to database";

include __DIR__ . "/src/Framework/Database.php";

use Framework\Database;

$db = new Database(
    'mysql',
    ["host" => "localhost", "port" => 3306, "dbname" => "phpiggy"],
    'root',
    ''
);

// Executing Sql Command in this script
// try {

//     $db->connection->beginTransaction();

//     $db->connection->query("INSERT INTO products(name) VALUES ('item7')");

//     $search = "item1";

//     $query = "SELECT * FROM products WHERE name =:name"; // WHERE name = '$search';
//     // $stmt = $db->connection->query($query, PDO::FETCH_ASSOC);
//     $stmt = $db->connection->prepare($query); // to prevent sql injection but not execute the query, unlike query that immediately executed ()

//     $stmt->bindValue('name', 'item7', PDO::PARAM_STR);

//     $stmt->execute(); // 1. ->execute([$search]) for placeholder (?) OR ->execute(['name'=>$search]) for name paramater (:name) OR using bindValue()

//     var_dump($stmt->fetchAll(PDO::FETCH_OBJ));

//     $db->connection->commit();
// } catch (Exception $error) {

//     if ($db->connection->inTransaction()) // need to check if does have active transanction
//     {
//         $db->connection->rollBack(); // if transaction is not active this method produce error,
//     }

//     echo "Transaction failed";
// }

// LOADING OF OUR database.sql file into this command line

// There are difference solution of loading a file in cmd file
// In our situation we only interested reading a file contents -> simply fn called file_get_contents

$sqlfile = file_get_contents("./database.sql");

// $db->connection->query($sqlfile);// nag change due to function in Database Class
$db->query($sqlfile);
