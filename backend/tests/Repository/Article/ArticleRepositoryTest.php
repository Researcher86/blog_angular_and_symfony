<?php

declare(strict_types=1);

namespace App\Tests\Repository\Article;

use App\Entity\Article\Comment;
use App\Repository\Article\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ArticleRepositoryTest extends KernelTestCase
{
    private ?ArticleRepository $repository;

    protected function setUp(): void
    {
        self::bootKernel();
        $container = self::$container;

        $this->repository = $container->get(ArticleRepository::class);
    }

    public function testGetCommentById()
    {
        $article = $this->repository->find(1);
        $article->addComment(1, 'Message');
        $this->repository->save($article);
//
//        $commentStore = $this->repository->getCommentById($article->getComments()[0]->getId());
//
//        self::assertEquals($comment->getId(), $commentStore->getId());
//        self::assertEquals($comment->getArticle()->getId(), $commentStore->getArticle()->getId());
//        self::assertEquals($comment->getUserId(), $commentStore->getUserId());
//        self::assertEquals($comment->getContent(), $commentStore->getContent());
    }
}
