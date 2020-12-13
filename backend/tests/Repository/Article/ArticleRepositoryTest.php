<?php

declare(strict_types=1);

namespace App\Tests\Repository\Article;

use App\Entity\Article\Article;
use App\Entity\Article\Comment;
use App\Repository\Article\ArticleRepository;
use App\Repository\Article\CommentRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ArticleRepositoryTest extends KernelTestCase
{
    private ?ArticleRepository $articleRepository;
    private ?CommentRepository $commentRepository;

    protected function setUp(): void
    {
        self::bootKernel();
        $container = self::$container;

        $this->articleRepository = $container->get(ArticleRepository::class);
        $this->commentRepository = $container->get(CommentRepository::class);
    }

    public function testGetCommentById()
    {
        $article = $this->articleRepository->getById(1);
        $article->addComment(1, 'Message');
        $this->articleRepository->save($article);

        /** @var Article $articleStore */
        $articleStore = $this->articleRepository->getById($article->getId());
        $commentStore = $this->commentRepository->getById($articleStore->getComments()[0]->getId());

        self::assertEquals($articleStore->getComments()[0]->getId(), $commentStore->getId());
        self::assertEquals($articleStore->getComments()[0]->getArticle()->getId(), $commentStore->getArticle()->getId());
        self::assertEquals($articleStore->getComments()[0]->getUserId(), $commentStore->getUserId());
        self::assertEquals($articleStore->getComments()[0]->getContent(), $commentStore->getContent());
    }
}
