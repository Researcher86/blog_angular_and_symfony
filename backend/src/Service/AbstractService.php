<?php

declare(strict_types=1);

namespace App\Service;

use App\Core\Exception\AppValidationException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

abstract class AbstractService
{
    private ValidatorInterface $validator;

    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    public function validate($param)
    {
        $constraints = $this->validator->validate($param);
        if ($constraints->count() > 0) {
            $errors = [];
            foreach ($constraints as $error) {
                $errors[$error->getPropertyPath()] = $error->getMessage();
            }

            throw new AppValidationException($errors);
        }
    }
}
