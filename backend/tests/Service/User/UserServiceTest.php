<?php

declare(strict_types=1);

namespace App\Tests\Service\User;

use App\Entity\User\User;
use App\Repository\User\UserRepository;
use App\Service\User\Command\CreateUser;
use App\Service\User\UserService;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;
use Symfony\Component\Security\Core\Encoder\SodiumPasswordEncoder;

class UserServiceTest extends TestCase
{
    private UserService $userService;

    private MockObject $userRepositoryMock;
    private MockObject $passwordEncoderMock;

    protected function setUp(): void
    {
        $this->userService = new UserService(
            $this->userRepositoryMock = $this->createMock(UserRepository::class),
            $this->passwordEncoderMock = $this->createMock(PasswordEncoderInterface::class),
        );
    }

    public function testGetByIdSuccess()
    {
        $user = new User('User 5', 'test@test.com', 'password');
        $this->userRepositoryMock->method('getById')->willReturn($user);
        $user = $this->userService->getById(5);

        $this->assertNotNull($user);
        $this->assertEquals('User 5', $user->getName());
    }

    public function testCreateUserSuccess()
    {
        $user = new User('User 5', 'test@test.com', 'password');
        $this->userRepositoryMock->method('save')->willReturn($user);
        $this->passwordEncoderMock->method('encodePassword')->willReturn('encodePassword');

        $command = new CreateUser();
        $command->name = 'User 5';
        $command->email = 'test@test.com';
        $command->plainPassword = 'Test@test.com';
        $result = $this->userService->create($command);

        $this->assertEquals('User 5', $result->getName());
    }
}
