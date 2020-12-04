<?php

declare(strict_types=1);

namespace App\Message\Article;

final class SendTelegramMessage
{
    private string $text;

    public function __construct(string $text)
    {
        $this->text = $text;
    }

    public function getText(): string
    {
        return $this->text;
    }
}
