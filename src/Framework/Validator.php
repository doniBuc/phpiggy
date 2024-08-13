<?php

declare(strict_types=1);

namespace Framework;

use Framework\Contracts\RuleInterface;
use Framework\Exceptions\ValidationExceptions;

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
                $ruleValidator = $this->rules[$rule];

                // [] for the meantime dahil wala pa tayo params
                if ($ruleValidator->validate($formData, $fieldName, [])) {
                    continue;
                }

                $errors[$fieldName][] = $ruleValidator->getMessage($formData, $fieldName, []);
            }
        }

        if (count($errors))
            throw new ValidationExceptions();
        //  dd($errors);     // replace it with custom validation exception



    }
}
