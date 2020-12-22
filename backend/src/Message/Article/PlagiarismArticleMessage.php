<?php

declare(strict_types=1);

namespace App\Message\Article;

final class PlagiarismArticleMessage
{
    private int $articleId;
    private int $userId;

    public function __construct(int $articleId, int $userId)
    {
        $this->articleId = $articleId;
        $this->userId = $userId;
    }

    public function getArticleId(): int
    {
        return $this->articleId;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }
}
