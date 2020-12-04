<?php

declare(strict_types=1);

namespace App\Controller\User\Dto;

use App\Entity\User\User;

class ViewUser
{
    public ?int $id;
    public string $name;
    public string $email;

    public static function createFrom(User $user): self
    {
        $dto = new self();
        $dto->id = $user->getId();
        $dto->name = $user->getName();
        $dto->email = $user->getEmail();
        return $dto;
    }
}
