<?php

declare(strict_types=1);

namespace App\Service\Article;

interface ComparatorInterface
{
    public function compare(string $textA, string $textB): float;
}
