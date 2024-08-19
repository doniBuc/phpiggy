<?php

declare(strict_types=1);

namespace Framework\Rules;

use Framework\Contracts\RuleInterface;

class InRule implements RuleInterface
{
    public function validate(array $data, string $field, array $params): bool
    {
        // the in rule validate a value by checking the value exist with in the array ->in_array()
        return in_array($data[$field], $params);
    }
    public function getMessage(array $data, string $field, array $params): string
    {
        return "Invalid Selection";
    }
}
