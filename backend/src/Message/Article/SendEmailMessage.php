<?php

declare(strict_types=1);

namespace App\Message\Article;

final class SendEmailMessage
{
    private string $subject;
    private string $email;
    private bool $isCopyToAdmins;
    private int $articleIdOrCommentId;
    private string $type;

    public function __construct(
        string $subject,
        string $email,
        bool $copyToAdmins,
        int $articleIdOrCommentId,
        string $type
    ) {
        $this->subject = $subject;
        $this->email = $email;
        $this->isCopyToAdmins = $copyToAdmins;
        $this->articleIdOrCommentId = $articleIdOrCommentId;
        $this->type = $type;
    }

    public function getSubject(): string
    {
        return $this->subject;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function isCopyToAdmins(): bool
    {
        return $this->isCopyToAdmins;
    }

    public function getArticleIdOrCommentId(): int
    {
        return $this->articleIdOrCommentId;
    }

    public function getType(): string
    {
        return $this->type;
    }
}
