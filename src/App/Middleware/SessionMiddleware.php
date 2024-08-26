<?php

declare(strict_types=1);

namespace App\Middleware;

use Framework\Contracts\MiddlewareInterface;
use App\Exceptions\SessionException;

class SessionMiddleware implements MiddlewareInterface
{
    public function process($next)
    {
        // Check if the session has already started.technically si developer should start the session our apps, 
        // but if we install a package with composer that uses a session. multiple session is possible but only single session what we want
        // if session is active were going to throw a exception and i think custom exception should be benificial for debugging the error
        // src->App->Exceptions
        if (session_status() === PHP_SESSION_ACTIVE) {
            throw new SessionException("Session already active");
        }

        // ob_end_clean(); ////Testing header_sent => if we accidentally output content before session started we be able to catch the error
        // echo 'Hello';


        // session have restriction,were not allowed to start a sess  after data has been sent to the browser,
        // PHP does not wait for the page to be completed generated to begins sending it the browser, 
        // PHP sents data to pieces,  its been official so it allowed users download a page right away as  page being process.
        // however this behavior can conflict to session.
        // Session cant be enable while PHP send data to the browser
        // So we should check to check this

        if (headers_sent($fileName, $line)) // if true the data is already send to the browser therefore,  we cant activate the session
        {
            throw new SessionException("Headers already sent. Considering enabling outoput buffering. Data Outputted from {$fileName} -Line:{$line}");
        }

        // Configuring the cookie must be done before the session started
        session_set_cookie_params([
            'secure' => $_ENV['APP_ENV'] === "production", // this option prevent cookies for being sent on insecure connection, since we are on development the setting should be disabled
            'httponly' => true, // this setting prevent javascript for access the cookie
            'samesite' => 'lax' // this setting is useful for restricting cookie to our site, by setting in into lax we allowing cookie to be accessible in our site, however if the user visit our site from external link the cookie will not sent, best option availble, strict and none other avail options 

        ]);


        session_start();
        $next();
        // is possible to terminate the session earlier it can be benifical to performance
        // next() is moving on next middleware or controller after controller recieve a request and generate a response, 
        // so the session should terminate/close after the response has been generated

        session_write_close();
    }
}
