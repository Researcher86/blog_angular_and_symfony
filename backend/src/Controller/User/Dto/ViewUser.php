<?php

declare(strict_types=1);

namespace App\Controller\User\Dto;

use App\Entity\User\User;

class ViewUser
{
    public ?int $id;
    public string $name;

    public static function createFrom(User $user)
    {
        $dto = new self();
        $dto->id = $user->getId();
        $dto->name = $user->getName();
        return $dto;
    }
}
