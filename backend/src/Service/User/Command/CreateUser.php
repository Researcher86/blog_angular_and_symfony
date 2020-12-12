<?php

declare(strict_types=1);

namespace App\Service\User\Command;

use Symfony\Component\Validator\Constraints as Assert;

class CreateUser
{
    /**
     * @Assert\Type("string")
     *
     * @Assert\NotBlank(normalizer="trim")
     *
     * @Assert\Length(min = 3, max = 45)
     */
    public ?string $name = null;

    /**
     * @Assert\Type("string")
     *
     * @Assert\NotBlank(normalizer="trim")
     *
     * @Assert\Email()
     */
    public ?string $email = null;

    /**
     * @Assert\Type("string")
     *
     * @Assert\NotBlank(normalizer="trim")
     *
     * @Assert\Length(min = 8, max = 45)
     */
    public ?string $plainPassword = null;
}
