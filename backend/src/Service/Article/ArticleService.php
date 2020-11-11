<?php

declare(strict_types=1);

namespace App\Service\Article;

use App\Core\Exception\AppEntityNotFoundException;
use App\Entity\Article\Article;
use App\Repository\Article\ArticleRepository;
use App\Service\Article\Param\CreateParam;

class ArticleService
{
    private ArticleRepository $articleRepository;

    public function __construct(ArticleRepository $articleRepository)
    {
        $this->articleRepository = $articleRepository;
    }

    public function getById(int $id): Article
    {
        $article = $this->articleRepository->find($id);

        if (!$article) {
            throw new AppEntityNotFoundException();
        }

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
        /** @var Article $article */
        $article = $this->articleRepository->save(
            new Article(
                null,
                $param->userId,
                $param->name,
                $param->text
            )
        );

        return $article;
    }
}
