<?php

declare(strict_types=1);

namespace App\Service\User;

use App\Entity\User\User;
use App\Repository\User\UserRepository;
use App\Service\AbstractService;
use App\Service\User\Param\CreateParam;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserService extends AbstractService
{
    private UserRepository $userRepository;

    public function __construct(ValidatorInterface $validator, UserRepository $userRepository)
    {
        parent::__construct($validator);
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

    public function create(CreateParam $param): User
    {
        $this->validate($param);
        return $this->userRepository->save(new User(null, $param->name));
    }
}
