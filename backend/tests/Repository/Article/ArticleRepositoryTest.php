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
        $article = $this->articleRepository->getById(3);
        $article->addComment(1, 'Message');
        $this->articleRepository->save($article);

        /** @var Article $articleStore */
        $articleStore = $this->articleRepository->getById($article->getId());
        self::assertCount(1, $articleStore->getComments());

        /** @var Comment $comment */
        $comment = $articleStore->getComments()[0];

        $commentStore = $this->commentRepository->getById($comment->getId());

        self::assertEquals($comment->getId(), $commentStore->getId());
        self::assertEquals($comment->getArticle()->getId(), $commentStore->getArticle()->getId());
        self::assertEquals($comment->getUserId(), $commentStore->getUserId());
        self::assertEquals($comment->getContent(), $commentStore->getContent());

        $articleStore->removeComment($comment);
        $this->articleRepository->save($articleStore);

        /** @var Article $articleStore */
        $articleStore = $this->articleRepository->getById($article->getId());
        self::assertCount(0, $articleStore->getComments());
    }
}
