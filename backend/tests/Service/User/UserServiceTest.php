<?php

declare(strict_types=1);

namespace App\Tests\Service\User;

use App\Entity\User\User;
use App\Repository\User\UserRepository;
use App\Service\User\UserService;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class UserServiceTest extends TestCase
{
    private UserService $userService;

    private MockObject $userRepositoryMock;

    protected function setUp(): void
    {
        $this->userRepositoryMock = $this->createMock(UserRepository::class);
        $this->userService = new UserService($this->userRepositoryMock);
    }


    public function testGetById()
    {
        $this->userRepositoryMock->method('find')->willReturn(new User(5, 'User 5'));
        $user = $this->userService->getById(5);

        self::assertNotNull($user);
        self::assertEquals(5, $user->getId());
        self::assertEquals('User 5', $user->getName());
    }
}
