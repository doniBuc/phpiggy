<?php

declare(strict_types=1);

namespace Framework;

use Framework\Contracts\RuleInterface;
use Framework\Exceptions\ValidationException;

class Validator
{
    private array $rules = [];

    public function addRule(string $alias, RuleInterface $rule)
    {

        $this->rules[$alias] = $rule;
    }
    public function validate(array $formData, array $fields)
    {
        $errors = [];

        foreach ($fields as $fieldName => $rules) {
            foreach ($rules as $rule) {

                $ruleParams = [];

                if (str_contains($rule, ':')) // checking the rule if has a param by checking the string for colon param
                {
                    [$rule, $ruleParams] = explode(':', $rule); // convert a string into array by splittng the string with char from within the string
                    $ruleParams = explode(',', $ruleParams); // then convert the param into array at the moment the ruleparam is a string 

                }

                $ruleValidator = $this->rules[$rule];

                // [] for the meantime dahil wala pa tayo params when we have rule param no need this empty array pass the ruleparams
                if ($ruleValidator->validate($formData, $fieldName, $ruleParams)) {
                    continue;
                }

                $errors[$fieldName][] = $ruleValidator->getMessage($formData, $fieldName, $ruleParams); // change third param [] empty array to ruleParams
            }
        }

        if (count($errors))
            throw new ValidationException($errors);
        //  dd($errors);     // replace it with custom validation exception



    }
}
