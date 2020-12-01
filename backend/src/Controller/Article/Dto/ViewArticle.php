<?php

declare(strict_types=1);

namespace App\Controller\Article\Dto;

use App\Entity\Article\Article;
use DateTimeInterface;

class ViewArticle
{
    public int $id;
    public int $userId;
    public string $name;
    public string $content;
    public string $status;
    public DateTimeInterface $createdAt;

    public static function createFrom(Article $article): self
    {
        $dto = new self();
        $dto->id = $article->getId();
        $dto->userId = $article->getUserId();
        $dto->name = $article->getName();
        $dto->content = $article->getContent();
        $dto->status = $article->statusToString();
        $dto->createdAt = $article->getCreatedAt();
        return $dto;
    }
}
