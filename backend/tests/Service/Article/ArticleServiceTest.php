<?php

declare(strict_types=1);

namespace App\Tests\Service\Article;

use App\Entity\Article\Article;
use App\Repository\Article\ArticleRepository;
use App\Repository\User\UserRepository;
use App\Service\Article\ArticleService;
use App\Service\Article\Command\CreateArticle;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class ArticleServiceTest extends TestCase
{
    private ArticleService $articleService;

    private MockObject $userRepositoryMock;
    private MockObject $articleRepositoryMock;
    private MockObject $eventDispatcherMock;

    protected function setUp(): void
    {
        $this->articleService = new ArticleService(
            $this->articleRepositoryMock = $this->createMock(ArticleRepository::class),
            $this->userRepositoryMock = $this->createMock(UserRepository::class),
            $this->eventDispatcherMock = $this->createMock(EventDispatcherInterface::class)
        );
    }

    public function testGetByIdSuccess()
    {
        $this->articleRepositoryMock->method('getById')->willReturn(new Article(1, 'Article', 'Text'));
        $article = $this->articleService->getById(1);

        $this->assertNotNull($article);
        $this->assertEquals(1, $article->getUserId());
        $this->assertEquals('Article', $article->getName());
        $this->assertEquals('Text', $article->getContent());
    }

    public function testCreateArticleSuccess()
    {
        $this->markTestSkipped();
        $this->articleRepositoryMock->method('save')->willReturn(new Article(1, 'Article', 'Text'));
//        $this->eventDispatcherMock->method('dispatch')->

        $command = new CreateArticle();
        $command->userId = 1;
        $command->name = 'Article';
        $command->content = 'Text';
        $article = $this->articleService->create($command);

        $this->assertEquals(1, $article->getUserId());
        $this->assertEquals('Article', $article->getName());
        $this->assertEquals('Text', $article->getContent());
    }
}
