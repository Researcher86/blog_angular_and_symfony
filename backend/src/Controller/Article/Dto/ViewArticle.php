<?php

declare(strict_types=1);

namespace App\Controller\Article\Dto;

use App\Entity\Article\Article;

class ViewArticle
{
    public ?int $id;
    public int $userId;
    public string $name;
    public string $text;

    public static function createFrom(Article $article): self
    {
        $dto = new self();
        $dto->id = $article->getId();
        $dto->userId = $article->getUserId();
        $dto->name = $article->getName();
        $dto->text = $article->getText();
        return $dto;
    }
}