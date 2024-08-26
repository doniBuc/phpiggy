<?php

namespace Framework\Rules;

use Framework\Contracts\RuleInterface;

class DateFormatRule implements RuleInterface
{
    public function validate(array $data, string $field, array $params): bool
    {

        //checking if the date follow the format assigned
        //1 Sol. Using RegEx 2. simplier this weve been used

        //fn grabbing info related to spicific date > if format for the date and date itself should match(format,date)
        $parsedDate = date_parse_from_format($params[0], $data[$field]); // return an array check in php manual for more info

        return $parsedDate['error_count'] === 0 && $parsedDate['warning_count'] === 0;
    }
    public function getMessage(array $data, string $field, array $params): string
    {

        return "Invalid date";
    }
}
