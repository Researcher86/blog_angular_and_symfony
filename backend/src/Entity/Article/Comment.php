<?php

declare(strict_types=1);

namespace App\Entity\Article;

use DateTime;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CommentRepository::class)
 *
 * @ORM\Table(name="comments")
 */
class Comment
{
    /**
     * @ORM\Id()
     *
     * @ORM\GeneratedValue()
     *
     * @ORM\Column(type="integer")
     */
    private ?int $id = null;

    /**
     * @ORM\Column(type="integer")
     */
    private int $userId;

    /**
     * @ORM\Column(type="text")
     */
    private string $content;

    /**
     * @ORM\Column(type="integer")
     */
    private int $status;

    /**
     * @ORM\ManyToOne(targetEntity=Article::class, inversedBy="comments")
     *
     * @ORM\JoinColumn(nullable=false)
     */
    private ?Article $article = null;

    /**
     * @ORM\Column(type="datetime")
     */
    private DateTime $createdAt;

    public function __construct(int $userId, string $content, Article $article)
    {
        $this->userId = $userId;
        $this->content = $content;
        $this->article = $article;
        $this->status = Status::DRAFT;

        $this->createdAt = new DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function getCreatedAt(): DateTimeInterface
    {
        return $this->createdAt;
    }

    public function isStatusDraft(): bool
    {
        return $this->status === Status::DRAFT;
    }

    public function isStatusPublished(): bool
    {
        return $this->status === Status::PUBLISHED;
    }

    public function toStatusDraft(): void
    {
        $this->status = Status::DRAFT;
    }

    public function toStatusPublished(): void
    {
        $this->status = Status::PUBLISHED;
    }

    public function statusToString(): string
    {
        return Status::TEXT_STATUS[$this->status];
    }

    public function getArticle(): ?Article
    {
        return $this->article;
    }
}
