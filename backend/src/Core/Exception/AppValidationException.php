<?php

declare(strict_types=1);

namespace App\Core\Exception;

class AppValidationException extends AppException
{
    private array $errors;

    public function __construct(array $errors)
    {
        parent::__construct();
        $this->errors = $errors;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }
}
