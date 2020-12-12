<?php

declare(strict_types=1);

namespace App\Validator\Constraints;

use App\Repository\User\UserRepository;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class ExistsUserValidator extends ConstraintValidator
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * {@inheritdoc}()
     */
    public function validate($value, Constraint $constraint): void
    {
        if (! $constraint instanceof ExistsUser) {
            throw new UnexpectedTypeException($constraint, ExistsUser::class);
        }

        // custom constraints should ignore null and empty values to allow
        // other constraints (NotBlank, NotNull, etc.) take care of that
        if ($value === null || $value === '' || $value <= 0) {
            return;
        }

        if (! $this->userRepository->exists((int) $value)) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ id }}', (string) $value)
                ->addViolation();
        }
    }
}
