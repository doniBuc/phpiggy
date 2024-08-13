<?php

declare(strict_types=1);

namespace Framework\Exceptions;

use RuntimeException;

class ValidationExceptions extends RuntimeException
{
    public function __construct(int $code = 422) // Unprocessable Content
    {
        parent::__construct(code: $code);
    }
}
