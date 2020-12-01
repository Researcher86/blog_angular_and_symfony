<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Article\Article;
use App\Entity\Article\Comment;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ArticleCommentFixture extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        /** @var Article $article */
        $article = $manager->getRepository(Article::class)->find(1);
        for ($i = 0; $i < 100; ++$i) {
            $article->addComment(
                new Comment(
                    \random_int(1, 50),
                    \sprintf('Text %d', $i)
                )
            );
        }

        $manager->persist($article);
        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            ArticleFixtures::class,
        ];
    }
}
