<?php

declare(strict_types=1);

namespace Framework\Rules;

use Framework\Contracts\RuleInterface;
use InvalidArgumentException; // we can create custom exception but this built in is good

class MinRule implements RuleInterface
{
    public function validate(array $data, string $field, array $params): bool
    {

        if (empty($params[0])) //checking if the param are provided  
        {
            throw new InvalidArgumentException('Minimum length not specified');
        }

        $length = (int)$params[0]; // 

        return $data[$field] >= $length; //if value of field >than the length
    }
    public function getMessage(array $data, string $field, array $params): string
    {
        return "Must be at least {$params[0]}";
    }
}
