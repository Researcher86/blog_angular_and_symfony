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
        /** @var Article $article */
        $article = $this->articleRepository->getById($id);
        return $article;
    }

    public function delete(int $id): void
    {
        $this->getById($id);
        $this->articleRepository->delete($id);
    }

    /**
     * @return Article[]
     */
    public function getAll(): array
    {
        return $this->articleRepository->findAll();
    }

    public function create(CreateArticle $command): Article
    {
        $this->userRepository->getById($command->userId);

        /** @var Article $article */
        $article = $this->articleRepository->save(
            new Article(
                $command->userId,
                $command->name,
                $command->content
            )
        );

        $this->eventDispatcher->dispatch(new ArticleCreatedEvent($article));

        return $article;
    }

    public function createComment(int $articleId, CreateComment $command): Comment
    {
        $this->userRepository->getById($command->userId);
        /** @var Article $article */
        $article = $this->articleRepository->getById($articleId);

        $comment = new Comment($command->userId, $command->content);
        $article->addComment($comment);

        $this->articleRepository->save($article);

        $this->eventDispatcher->dispatch(new ArticleCommentCreatedEvent($comment));

        return $comment;
    }

    public function getCommentById(int $commentId): Comment
    {
        /** @var Comment $comment */
        $comment = $this->commentRepository->getById($commentId);
        return $comment;
    }
}
