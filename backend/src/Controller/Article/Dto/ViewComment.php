<?php

declare(strict_types=1);

namespace App\Controller\Article\Dto;

use App\Entity\Article\Comment;
use DateTimeInterface;

class ViewComment
{
    public ?int $id = null;
    public ?int $userId = null;
    public ?string $content = null;
    public ?string $status = null;
    public ?DateTimeInterface $createdAt = null;

    public static function createFrom(Comment $comment): self
    {
        $dto = new self();
        $dto->id = $comment->getId();
        $dto->userId = $comment->getUserId();
        $dto->content = $comment->getContent();
        $dto->status = $comment->statusToString();
        $dto->createdAt = $comment->getCreatedAt();
        return $dto;
    }
}
