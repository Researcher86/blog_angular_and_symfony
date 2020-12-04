<?php

declare(strict_types=1);

namespace App\Service\User;

use App\Entity\User\User;
use App\Repository\User\UserRepository;
use App\Service\User\Command\CreateUser;
use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;
use Symfony\Component\Security\Core\Encoder\SodiumPasswordEncoder;

class UserService
{
    private UserRepository $userRepository;
    private PasswordEncoderInterface $passwordEncoder;

    public function __construct(UserRepository $userRepository, PasswordEncoderInterface $passwordEncoder)
    {
        $this->userRepository = $userRepository;
        $this->passwordEncoder = $passwordEncoder;
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
        $user = $this->userRepository->save(new User(
            $command->name,
            $command->email,
            $this->passwordEncoder->encodePassword($command->plainPassword, 'salt'),
        ));

        return $user;
    }

    public function delete(int $id): void
    {
        $this->getById($id);
        $this->userRepository->delete($id);
    }
}
