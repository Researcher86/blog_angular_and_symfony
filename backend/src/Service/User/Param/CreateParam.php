<?php

declare(strict_types=1);

namespace App\Service\User\Param;

use Symfony\Component\Validator\Constraints as Assert;

class CreateParam
{
    /**
     * @Assert\Type("string")
     * @Assert\NotBlank()
     * @Assert\Length(min = 3, max = 45)
     */
    public string $name;
}
