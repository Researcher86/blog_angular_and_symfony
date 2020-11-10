<?php

namespace App\DataFixtures;

use App\Entity\Article\Article;
use App\Entity\User\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ArticleFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $users = $manager->getRepository(User::class)->findAll();
        $userIds = \array_map(fn ($user) => $user->getId(), $users);

        for ($i = 1; $i <= 100; ++$i) {
            $manager->persist(
                new Article(
                    null,
                    $userIds[\random_int(1, 50)],
                    \sprintf('Name %d', $i),
                    \sprintf('Text %d', $i)
                )
            );
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            UserFixtures::class
        ];
    }
}
