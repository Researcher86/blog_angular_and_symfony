<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Article\Article;

interface IndexerInterface
{
    public function add(Article $article): void;

    /**
     * @return array<int, string>
     */
    public function get(int $articleId): array;

    public function delete(int $articleId): void;

    /**
     * @return array<int, array<string, array<string>>>
     */
    public function search(string $text, int $page = 1, int $limit = 10): array;
}
