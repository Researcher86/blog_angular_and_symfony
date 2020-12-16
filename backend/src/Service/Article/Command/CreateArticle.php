<?php

declare(strict_types=1);

namespace App\Service\Article\Command;

use App\Service\CommandInterface;
use Symfony\Component\Validator\Constraints as Assert;

class CreateArticle implements CommandInterface
{
    /**
     * @Assert\Type("string")
     *
     * @Assert\NotBlank(normalizer="trim")
     *
     * @Assert\Length(min = 3, max = 255)
     */
    public ?string $name = null;

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
