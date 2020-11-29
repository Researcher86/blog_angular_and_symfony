<?php

declare(strict_types=1);

namespace App\Message\Article;

final class IndexerMessage
{
    private int $articleId;

    public function __construct(int $articleId)
    {
        $this->articleId = $articleId;
    }

    public function getArticleId(): int
    {
        return $this->articleId;
    }
}
