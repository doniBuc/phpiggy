<?php

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
    http_response_code(302); // redirection status code temporary 302
    exit; // exit because after redirection not do anymorr
}
