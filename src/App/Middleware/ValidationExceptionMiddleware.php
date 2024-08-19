<?php

declare(strict_types=1);


namespace App\Middleware;

use Framework\Contracts\MiddlewareInterface;
use Framework\Exceptions\ValidationException;


class ValidationExceptionMiddleware implements MiddlewareInterface
{

    public function process(callable $next)
    {
        try {
            $next();
        } catch (ValidationException $e) {
            $oldFormData = $_POST;

            $excludedFields = ['password', 'confirmPassword'];
            // this fn accept 2 arr and look similar keys and exclude those to new created array 
            $formattedFormData = array_diff_key(
                $oldFormData,
                array_flip($excludedFields)
            ); //array_flip() flip the value of array to became key

            $_SESSION['errors'] = $e->errors; // use session is var to store the errors, need to enabled first
            $referer = $_SERVER['HTTP_REFERER']; // redirect with same url

            // Prefilling the form
            $_SESSION['oldFormData'] = $formattedFormData;
            // dd($e->errors);
            redirecTo($referer);
        }
    }
}
