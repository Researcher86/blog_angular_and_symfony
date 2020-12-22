<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Article\Article;
use App\Service\IndexerInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

/**
 * @psalm()-suppress PropertyNotSetInConstructor
 */
class ArticleIndexFixtures extends Fixture implements DependentFixtureInterface
{
    private IndexerInterface $indexService;

    public function __construct(IndexerInterface $indexService)
    {
        $this->indexService = $indexService;
    }

    public function load(ObjectManager $manager): void
    {
        $articles = $manager->getRepository(Article::class)->findAll();

        foreach ($articles as $article) {
            $this->indexService->add($article);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getDependencies(): array
    {
        return [
            ArticleFixtures::class,
        ];
    }
}
