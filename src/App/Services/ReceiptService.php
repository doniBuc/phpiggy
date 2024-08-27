<?php

declare(strict_types=1);

namespace App\Services;


use Framework\Database;
use Framework\Exceptions\ValidationException;
use App\Config\Paths;

class ReceiptService
{

    public function __construct(private Database $db) {}


    // the validation will handle by service cause its tricky and have various requirements
    public function validateFile(?array $file) // validating if theres file upload
    {
        if (!$file || $file['error'] !== UPLOAD_ERR_OK) {
            throw new ValidationException([
                'receipt' => ['Failed to upload']
            ]);
        }

        $maxFileSizeMb = 3 * 1024 * 1024; // convert it to bytes 
        if ($file['size'] > $maxFileSizeMb) { /// validating the accepted file size
            throw new ValidationException([
                'receipt' => ['File Upload is too large']
            ]);
        }

        // validating the filename
        $originalFileName = $file['name'];

        if (!preg_match('/^[A-za-z0-9\s._-]+$/', $originalFileName))
            throw new ValidationException(['receipt' => ['Invalid Filename']]);


        // MIME Type limiting the file could upload much better than extension

        $fileMimeType = $file['type'];
        $allowedMimeType = ['image/jpeg', 'image/png', 'application/pdf'];

        if (!in_array($fileMimeType, $allowedMimeType)) {
            throw new ValidationException([
                'receipt' => ['Invalid file type']
            ]);
        }
    }

    public function upload(array $file, int $transaction_id)
    {

        // To store the file after
        //1. generate random file name, storing file with original file name could lead different issue like same filename
        $fileExtension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $newFileName = bin2hex(random_bytes(16)) . "." . $fileExtension; // bytes to hex, hex is suitable for filename

        //moving the file for storing
        $uploadPath = Paths::STORAGE_UPLOAD . "/" . $newFileName;

        if (!move_uploaded_file($file['tmp_name'], $uploadPath)) { // it will move the file upload in our server to new directory
            throw new ValidationException([
                'receipt' => ['Failed to upload file']
            ]);
        }

        $this->db->prepare(
            "INSERT INTO receipts(transaction_id, original_filename, storage_filename, media_type)
            VALUES(:transaction_id, :original_filename, :storage_filename, :media_type)",
            [
                'transaction_id' => $transaction_id,
                'original_filename' => $file['name'],
                'storage_filename' => $newFileName,
                'media_type' => $file['type']
            ]
        );
    }

    public function getReceipt(string $id)
    {

        $receipt = $this->db->prepare(
            "SELECT * FROM receipts WHERE id = :id",
            [
                'id' => $id
            ]
        )->find();

        return $receipt;
    }

    public function read(array $receipt)
    {

        // get and check if file receipt is exist
        $filePath = Paths::STORAGE_UPLOAD . '/' . $receipt['storage_filename'];

        if (!file_exists($filePath)) {
            redirecTo('/');
        }

        // we can proceed to download a file
        // 1st we need to modify the header with header() ,by default server sent responses as plain text or html
        // we want to sent a file type differ form html or plain text by 

        // using header we can tell to browser we sending a file in response

        // 2nd arg(:inline -> tell to browser to attemp render the file into browser, :attachment -> this options tell to download the file)
        //"1st arg tell how to download the file, since receipt can be uploaded as pdf or images so we used inline 

        // $mimeType = $receipt['media_file'] ?? mime_content_type($filePath); // pwde din gamin itong function to get mime type 

        header("Content-Disposition: inline;filename={$receipt['original_filename']}");
        header("Content-Type: {$receipt['media_type']}"); //telling the browser the type of file 


        readfile($filePath); //we can start sending a file after configure the header
    }

    public function delete(array $receipt)
    {
        $filePath = Paths::STORAGE_UPLOAD . '/' . $receipt['storage_filename'];

        unlink($filePath); // delete a file from a system

        // delete the receipt in database

        $this->db->prepare("DELETE FROM receipts WHERE id = :id", ['id' => $receipt['id']]);
        redirecTo('/');
    }
}
