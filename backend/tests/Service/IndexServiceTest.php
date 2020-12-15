<?php

declare(strict_types=1);

namespace App\Tests\Service;

use App\Entity\Article\Article;
use App\Repository\Article\ArticleRepository;
use App\Service\IndexService;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class IndexServiceTest extends KernelTestCase
{
    private ?IndexService $indexService;
    private ?ArticleRepository $articleRepository;

    protected function setUp(): void
    {
        self::bootKernel();
        $container = self::$container;

        $this->indexService = $container->get(IndexService::class);
        $this->articleRepository = $container->get(ArticleRepository::class);
    }

    public function testPing()
    {
        self::assertTrue($this->indexService->ping());
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
        $article = $this->articleRepository->getById(2);
        $this->indexService->add($article);

        $result = $this->indexService->search($article->getName())[0];

        $expected = [
            'name' => ['<b>Name</b> <b>2</b>'],
            'content' => ['Text <b>2</b>'],
        ];
        self::assertEquals($expected, $result);
    }
}
