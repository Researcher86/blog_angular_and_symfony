<?php

declare(strict_types=1);

namespace App\Entity\Article;

class Status
{
    public const DRAFT = 0;
    public const PUBLISHED = 1;

    public const TEXT_STATUS = [
        self::DRAFT     => 'DRAFT',
        self::PUBLISHED => 'PUBLISHED',
    ];
}
