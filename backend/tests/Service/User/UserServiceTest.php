<?php

declare(strict_types=1);

namespace App\Tests\Service\User;

use App\Entity\User\User;
use App\Repository\User\UserRepository;
use App\Service\User\Param\CreateParam;
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

    public function testGetByIdSuccess()
    {
        $this->userRepositoryMock->method('getById')->willReturn(new User(5, 'User 5'));
        $user = $this->userService->getById(5);

        $this->assertNotNull($user);
        $this->assertEquals(5, $user->getId());
        $this->assertEquals('User 5', $user->getName());
    }

    public function testCreateUserSuccess()
    {
        $user = new User(5, 'Test');
        $this->userRepositoryMock->method('save')->willReturn($user);

        $param = new CreateParam();
        $param->name = 'Test';
        $result = $this->userService->create($param);

        $this->assertEquals(5, $result->getId());
        $this->assertEquals('Test', $result->getName());
    }
}
