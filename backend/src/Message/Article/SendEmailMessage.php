<?php

declare(strict_types=1);

namespace App\Message\Article;

final class SendEmailMessage
{
    private string $email;

    public function __construct(string $email)
    {
        $this->name = $email;
    }

    public function getEmail(): string
    {
        return $this->name;
    }
}
