<?php

declare(strict_types=1);

namespace App\Service\User;

use App\Entity\User\User;
use App\Repository\User\UserRepository;
use App\Service\User\Command\CreateUser;
use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;

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
        $user = $this->userRepository->getById($id);
        \assert($user instanceof User);

        return $user;
    }

    /**
     * @return array<User>
     */
    public function getAll(): array
    {
        return $this->userRepository->findAll();
    }

    public function create(CreateUser $command): User
    {
        $user = new User(
            (string) $command->name,
            (string) $command->email,
            $this->passwordEncoder->encodePassword((string) $command->plainPassword, 'salt'),
        );

        $user = $this->userRepository->save($user);
        \assert($user instanceof User);

        return $user;
    }

    public function delete(int $id): User
    {
        $this->getById($id);

        $user = $this->userRepository->delete($id);
        \assert($user instanceof User);

        return $user;
    }
}
