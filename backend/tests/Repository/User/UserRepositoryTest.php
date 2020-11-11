<?php

declare(strict_types=1);

namespace App\Tests\Repository\User;

use App\Entity\User\User;
use App\Repository\User\UserRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class UserRepositoryTest extends KernelTestCase
{
    private ?EntityManager $entityManager;
    private UserRepository $userRepository;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();
        $this->entityManager = $kernel->getContainer()->get('doctrine')->getManager();
        $this->userRepository = $this->entityManager->getRepository(User::class);
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        // doing this is recommended to avoid memory leaks
        $this->entityManager->close();
        $this->entityManager = null;
    }

    public function testGetById()
    {
        $user = $this->userRepository->find(5);
        $this->assertNotNull($user);
    }

    public function testExists()
    {
        $result = $this->userRepository->exists(5);
        $this->assertTrue($result);

        $result = $this->userRepository->exists(-5);
        $this->assertFalse($result);
    }
}
