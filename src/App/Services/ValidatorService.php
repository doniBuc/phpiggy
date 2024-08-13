<?php

declare(strict_types=1);

namespace App\Services;

use Framework\Rules\RequiredRule;
use Framework\Validator;


class ValidatorService
{

    private Validator $validator;

    public function __construct()
    {
        $this->validator = new Validator();

        //create or register the rule
        $this->validator->addRule('required', new RequiredRule()); // we passed an instance, previously we provide only class name however our validator is simple 
    }

    public function validateRegister(array $formData)
    {
        $this->validator->validate($formData, [ //manually hard code the list of field to be validate and rule
            'email' => ['required'],
            'age' => ['required'],
            'country' => ['required'],
            'socialMediaUrl' => ['required'],
            'password' => ['required'],
            'confirmPassword' => ['required'],
            'tos' => ['required']
        ]);
    }
}
