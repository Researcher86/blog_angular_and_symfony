<?php

declare(strict_types=1);

namespace App\Message\Article;

final class SendEmailMessage
{
    private string $email;
    private bool $isCopyToAdmins;
    private string $subject;
    private int $id;
    private string $type;

    public function __construct(string $subject, string $email, bool $isCopyToAdmins, int $id, string $type)
    {
        $this->subject = $subject;
        $this->email = $email;
        $this->isCopyToAdmins = $isCopyToAdmins;
        $this->id = $id;
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

    public function getId(): int
    {
        return $this->id;
    }

    public function getType(): string
    {
        return $this->type;
    }
}
