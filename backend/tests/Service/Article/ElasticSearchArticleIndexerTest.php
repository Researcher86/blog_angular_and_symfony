<?php

declare(strict_types=1);

namespace App\Tests\Service\Article;

use App\Entity\Article\Article;
use App\Repository\Article\ArticleRepository;
use App\Service\Article\ElasticSearchArticleIndexer;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ElasticSearchArticleIndexerTest extends KernelTestCase
{
    private ?ElasticSearchArticleIndexer $indexService;
    private ?ArticleRepository $articleRepository;

    protected function setUp(): void
    {
        self::bootKernel();
        $container = self::$container;

        $this->indexService = $container->get(ElasticSearchArticleIndexer::class);
        $this->articleRepository = $container->get(ArticleRepository::class);
    }

    public function testAdd()
    {
        /** @var Article $article */
        $article = $this->articleRepository->getById(1);

        $this->indexService->add($article);

        $expected = [
            'name' => $article->getName(),
            'content' => $article->getContent(),
            'user_id' => $article->getUserId(),
        ];
        self::assertEquals($expected, $this->indexService->get($article->getId()));
    }

    public function testDelete()
    {
        /** @var Article $article */
        $article = $this->articleRepository->getById(2);
        $this->indexService->add($article);

        $this->indexService->delete($article->getId());

        self::assertEquals([], $this->indexService->get($article->getId()));
    }

    public function testSearch()
    {
        /** @var Article $article */
        $article = $this->articleRepository->getById(3);
        $this->indexService->add($article);

        $result = $this->indexService->search($article->getName())[0];

        self::assertEquals('<b>Name</b> <b>3</b>', $result['name'][0]);
    }
}
