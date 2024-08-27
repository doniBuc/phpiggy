<?php

use Framework\Http;

function dd(mixed $value)
{
    echo "<pre>";
    var_dump($value);
    echo "<pre>";
    die();
};

function escaping(mixed $value)
{

    return htmlspecialchars((string) $value);
}
function redirecTo($path)
{
    header("Location: {$path}"); // adding header to our response(URL or path)
    http_response_code(Http::REDIRECT_STATUS_CODE); // redirection status code temporary 302 // create this class for ex of magic number and clarity
    exit; // exit because after redirection not do anymorr
}
