<?php

declare(strict_types=1);

namespace App\Service\Article;

use App\Entity\Article\Article;
use App\Entity\Article\Comment;
use App\Event\Article\ArticleCommentCreatedEvent;
use App\Event\Article\ArticleCreatedEvent;
use App\Repository\Article\ArticleRepository;
use App\Repository\Article\CommentRepository;
use App\Repository\User\UserRepository;
use App\Service\Article\Command\CreateArticle;
use App\Service\Article\Command\CreateComment;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class ArticleService
{
    private ArticleRepository $articleRepository;
    private CommentRepository $commentRepository;
    private UserRepository $userRepository;
    private EventDispatcherInterface $eventDispatcher;

    public function __construct(
        ArticleRepository $articleRepository,
        CommentRepository $commentRepository,
        UserRepository $userRepository,
        EventDispatcherInterface $eventDispatcher
    ) {
        $this->articleRepository = $articleRepository;
        $this->commentRepository = $commentRepository;
        $this->userRepository = $userRepository;
        $this->eventDispatcher = $eventDispatcher;
    }

    public function getById(int $id): Article
    {
        $article = $this->articleRepository->getById($id);
        \assert($article instanceof Article);

        return $article;
    }

    public function delete(int $id): Article
    {
        $this->articleRepository->getById($id);
        $article = $this->articleRepository->delete($id);
        \assert($article instanceof Article);

        return $article;
    }

    /**
     * @return array<Article>
     */
    public function getAll(): array
    {
        return $this->articleRepository->findAll();
    }

    public function create(CreateArticle $command): Article
    {
        $this->userRepository->getById((int) $command->userId);

        /** @var Article $article */
        $article = $this->articleRepository->save(
            new Article(
                (int) $command->userId,
                (string) $command->name,
                (string) $command->content
            )
        );

        $this->eventDispatcher->dispatch(new ArticleCreatedEvent($article));

        return $article;
    }

    public function createComment(int $articleId, CreateComment $command): Comment
    {
        $this->userRepository->getById((int) $command->userId);
        /** @var Article $article */
        $article = $this->articleRepository->getById($articleId);

        $comment = $article->addComment((int) $command->userId, (string) $command->content);

        $this->articleRepository->save($article);

        $this->eventDispatcher->dispatch(new ArticleCommentCreatedEvent($comment));

        return $comment;
    }

    public function getCommentById(int $commentId): Comment
    {
        $comment = $this->commentRepository->getById($commentId);
        \assert($comment instanceof Comment);

        return $comment;
    }
}
