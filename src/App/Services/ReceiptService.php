<?php

declare(strict_types=1);

namespace App\Services;

use Dotenv\Exception\ValidationException as ExceptionValidationException;
use Framework\Database;
use Framework\Exceptions\ValidationException;

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

    public function upload(array $file)
    {

        // To store the file after
        //1. generate random file name, storing file with original file name could lead different issue like same filename
        $fileExtension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $newFileName = bin2hex(random_bytes(16)) . "." . $fileExtension; // bytes to hex, hex is suitable for filename



        dd($newFileName);
    }
}
