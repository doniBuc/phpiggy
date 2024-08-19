<?php

declare(strict_types=1);

namespace App\Services;

use Framework\Validator;
use Framework\Rules\{
    RequiredRule,
    EmailRule,
    MinRule,
    InRule,
    UrlRule,
    MatchRule
};


class ValidatorService
{

    private Validator $validator;

    public function __construct()
    {
        $this->validator = new Validator();

        //create or register the rule\
        $this->validator->addRule('required', new RequiredRule()); // we passed an instance, previously we provide only class name however our validator is simple 
        $this->validator->addRule('email', new EmailRule());
        $this->validator->addRule('min', new MinRule());
        $this->validator->addRule('in', new InRule());
        $this->validator->addRule('url', new UrlRule);
        $this->validator->addRule('match', new MatchRule);
    }

    public function validateRegister(array $formData)
    {
        $this->validator->validate($formData, [ //manually hard code the list of field to be validate and rule
            'email' => ['required', 'email'],
            'age' => ['required', 'min:18'], // min rule doesnt exist-> adding colon then 18(:18 -> demonstrate rule parameter)
            'country' => ['required', 'in:USA,Canada,Mexico'],
            'socialMediaUrl' => ['required', 'url'],
            'password' => ['required'],
            'confirmPassword' => ['required', 'match:password'],
            'tos' => ['required']
        ]);
    }
}
