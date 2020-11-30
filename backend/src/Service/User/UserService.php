<?php

declare(strict_types=1);

namespace App\Service\User;

use App\Entity\User\User;
use App\Repository\User\UserRepository;
use App\Service\User\Command\CreateUser;

class UserService
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function getById(int $id): User
    {
        /** @var User $user */
        $user = $this->userRepository->getById($id);
        return $user;
    }

    /**
     * @return User[]
     */
    public function getAll(): array
    {
        return $this->userRepository->findAll();
    }

    public function create(CreateUser $command): User
    {
        /** @var User $user */
        $user = $this->userRepository->save(new User(null, $command->name));

        return $user;
    }

    public function delete(int $id): void
    {
        $this->getById($id);
        $this->userRepository->delete($id);
    }
}
