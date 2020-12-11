<?php

declare(strict_types=1);

namespace App\Entity\Article;

use DateTime;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CommentRepository::class)
 * @ORM\Table(name="comments")
 */
class Comment
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $userId;

    /**
     * @ORM\Column(type="string", length=65000)
     */
    private $content;

    /**
     * @ORM\Column(type="integer")
     */
    private $status;

    /**
     * @ORM\ManyToOne(targetEntity=Article::class, inversedBy="comments")
     * @ORM\JoinColumn(nullable=false)
     */
    private $article;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    public function __construct(int $userId, string $content)
    {
        $this->userId = $userId;
        $this->content = $content;
        $this->status = Status::DRAFT;

        $this->createdAt = new DateTime();
    }

    public function getId(): int
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

    public function setStatusDraft()
    {
        $this->status = Status::DRAFT;
    }

    public function setStatusPublished()
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

    public function setArticle(?Article $article): self
    {
        $this->article = $article;

        return $this;
    }
}
