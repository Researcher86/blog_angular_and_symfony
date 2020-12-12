<?php

declare(strict_types=1);

namespace App\Controller\Article\Dto;

use App\Entity\Article\Article;
use DateTimeInterface;

class ViewArticle
{
    public ?int $id = null;
    public ?int $userId = null;
    public ?string $name = null;
    public ?string $content = null;
    public ?string $status = null;
    public ?DateTimeInterface $createdAt = null;

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
