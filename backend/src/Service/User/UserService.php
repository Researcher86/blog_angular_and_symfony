<?php

declare(strict_types=1);

namespace App\Service\User;

use App\Core\Exception\AppEntityNotFoundException;
use App\Entity\User\User;
use App\Repository\User\UserRepository;
use App\Service\User\Param\CreateParam;

class UserService
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function getById(int $id): User
    {
        $user = $this->userRepository->find($id);

        if (!$user) {
            throw new AppEntityNotFoundException();
        }

        return $user;
    }

    /**
     * @return User[]
     */
    public function getAll(): array
    {
        return $this->userRepository->findAll();
    }

    public function create(CreateParam $param): User
    {
        /** @var User $user */
        $user = $this->userRepository->save(new User(null, $param->name));

        return $user;
    }

    public function delete(int $id): void
    {
        $this->getById($id);
        $this->userRepository->delete($id);
    }
}
