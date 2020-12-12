<?php

declare(strict_types=1);

namespace App\Service\Article\Command;

use Symfony\Component\Validator\Constraints as Assert;

class CreateComment
{
    /**
     * @Assert\Type("integer")
     *
     * @Assert\NotBlank()
     *
     * @Assert\Positive()
     */
    public ?int $userId = null;

    /**
     * @Assert\Type("string")
     *
     * @Assert\NotBlank(normalizer="trim")
     *
     * @Assert\Length(min = 3, max = 255)
     */
    public ?string $content = null;
}
