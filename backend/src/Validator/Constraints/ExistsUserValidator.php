<?php

declare(strict_types=1);

namespace App\Validator\Constraints;

use App\Repository\User\UserRepository;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

class ExistsUserValidator extends ConstraintValidator
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof ExistsUser) {
            throw new UnexpectedTypeException($constraint, ExistsUser::class);
        }

//        if (is_string($value)) {
//            // throw this exception if your validator cannot handle the passed type so that it can be marked as invalid
//            throw new UnexpectedValueException($value, 'integer');
//        }

        // custom constraints should ignore null and empty values to allow
        // other constraints (NotBlank, NotNull, etc.) take care of that
        if (null === $value || '' === $value || 0 >= $value) {
            return;
        }

        if (!$this->userRepository->exists($value)) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ id }}', (string) $value)
                ->addViolation();
        }
    }
}
