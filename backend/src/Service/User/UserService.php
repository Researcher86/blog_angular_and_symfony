<?php

declare(strict_types=1);

namespace App\Service\User;

use App\Entity\User\User;
use App\Repository\User\UserRepository;

class UserService
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function getById(int $id): ?User
    {
        return $this->userRepository->find($id);
    }

    /**
     * @return User[]
     */
    public function getAll(): array
    {
        return $this->userRepository->findAll();
    }
}
