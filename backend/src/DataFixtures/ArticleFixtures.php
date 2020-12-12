<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Article\Article;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ArticleFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        for ($i = 1; $i <= 100; ++$i) {
            $manager->persist(
                new Article(
                    \random_int(1, 10),
                    \sprintf('Name %d', $i),
                    \sprintf('Text %d', $i)
                )
            );
        }

        $manager->flush();
    }

    /**
     * {@inheritdoc}
     */
    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
        ];
    }
}
