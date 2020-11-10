<?php

declare(strict_types=1);

namespace App\Service\Article\Param;

use App\Core\Exception\AppEntityNotFoundException;
use App\Service\User\UserService;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class CreateParam
{
    /**
     * @Assert\Type("string")
     * @Assert\NotBlank(normalizer="trim")
     * @Assert\Length(min = 3, max = 255)
     */
    public ?string $name;

    /**
     * @Assert\Type("integer")
     * @Assert\NotBlank()
     * @Assert\Positive()
     */
    public ?int $userId;

    /**
     * @Assert\Type("string")
     * @Assert\NotBlank(normalizer="trim")
     * @Assert\Length(min = 3, max = 255)
     */
    public ?string $text;

    private UserService $userService;

    public function setUserService(UserService $userService): void
    {
        $this->userService = $userService;
    }

    /**
     * @Assert\Callback()
     * @param ExecutionContextInterface $context
     * @param mixed $payload
     */
    public function validate(ExecutionContextInterface $context, $payload)
    {
        try {
            $this->userService->getById($this->userId);
        } catch (AppEntityNotFoundException $e) {
            $context->buildViolation('User not found')
                ->atPath('userId')
                ->addViolation();
        }
    }
}
