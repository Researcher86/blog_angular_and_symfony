<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\User\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;

/**
 * @psalm()-suppress PropertyNotSetInConstructor
 */
class UserFixtures extends Fixture
{
    private PasswordEncoderInterface $passwordEncoder;

    public function __construct(PasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager): void
    {
        for ($i = 1; $i <= 10; ++$i) {
            $manager->persist(new User(
                \sprintf('User %d', $i),
                \sprintf('test%d@test.com', $i),
                $this->passwordEncoder->encodePassword(\sprintf('test%d@test.com', $i), 'salt')
            ));
        }

        $manager->flush();
    }
}
