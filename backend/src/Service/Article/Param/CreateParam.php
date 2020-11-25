<?php

declare(strict_types=1);

namespace App\Service\Article\Param;

use Symfony\Component\Validator\Constraints as Assert;

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
}
