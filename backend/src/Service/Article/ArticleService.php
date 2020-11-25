<?php

declare(strict_types=1);

namespace App\Service\Article;

use App\Entity\Article\Article;
use App\Event\Article\ArticleCreatedEvent;
use App\Repository\Article\ArticleRepository;
use App\Repository\User\UserRepository;
use App\Service\Article\Param\CreateParam;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class ArticleService
{
    private ArticleRepository $articleRepository;
    private UserRepository $userRepository;
    private EventDispatcherInterface $eventDispatcher;

    public function __construct(ArticleRepository $articleRepository, UserRepository $userRepository, EventDispatcherInterface $eventDispatcher)
    {
        $this->articleRepository = $articleRepository;
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

    public function create(CreateParam $param): Article
    {
        $this->userRepository->getById($param->userId);

        /** @var Article $article */
        $article = $this->articleRepository->save(
            new Article(
                null,
                $param->userId,
                $param->name,
                $param->text
            )
        );

        $this->eventDispatcher->dispatch(new ArticleCreatedEvent($article));

        return $article;
    }
}
