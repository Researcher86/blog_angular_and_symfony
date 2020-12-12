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

    /**
     * @psalm-suppress MoreSpecificReturnType
     */
    public function getById(int $id): User
    {
        /** @psalm-suppress LessSpecificReturnStatement */
        return $this->userRepository->getById($id); /** @phpstan-ignore-line */
    }

    /**
     * @return array<User>
     */
    public function getAll(): array
    {
        return $this->userRepository->findAll();
    }

    /**
     * @psalm-suppress MoreSpecificReturnType
     */
    public function create(CreateUser $command): User
    {
        $user = new User(
            (string) $command->name,
            (string) $command->email,
            $this->passwordEncoder->encodePassword((string) $command->plainPassword, 'salt'),
        );

        /** @psalm-suppress LessSpecificReturnStatement */
        return $this->userRepository->save($user); /** @phpstan-ignore-line */
    }

    /**
     * @psalm-suppress MoreSpecificReturnType
     */
    public function delete(int $id): User
    {
        $this->getById($id);
        /** @psalm-suppress LessSpecificReturnStatement */
        return $this->userRepository->delete($id); /** @phpstan-ignore-line */
    }
}
