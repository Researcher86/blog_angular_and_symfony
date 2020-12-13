<?php

declare(strict_types=1);

namespace App\Entity\Article;

use App\Repository\Article\ArticleRepository;
use DateTime;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ArticleRepository::class)
 *
 * @ORM\Table(name="articles")
 */
class Article
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
     * @ORM\Column(type="string")
     */
    private string $name;

    /**
     * @ORM\Column(type="text")
     */
    private string $content;

    /**
     * @ORM\OneToMany(targetEntity=Comment::class, mappedBy="article", orphanRemoval=true, cascade={"persist"})
     *
     * @var ArrayCollection<int, Comment>
     */
    private Collection $comments;

    /**
     * @ORM\Column(type="integer")
     */
    private int $status;

    /**
     * @ORM\Column(type="datetime")
     */
    private DateTime $createdAt;

    public function __construct(int $userId, string $name, string $content)
    {
        $this->userId = $userId;
        $this->name = $name;
        $this->content = $content;
        $this->comments = new ArrayCollection();
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

    public function getName(): string
    {
        return $this->name;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * @return ArrayCollection<int, Comment>
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(int $userId, string $content): Comment
    {
        $comment = new Comment($userId, $content, $this);

        if (! $this->comments->contains($comment)) {
            $this->comments[] = $comment;
        }

        return $comment;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getArticle() === $this) {
                $comment->unsetArticle();
            }
        }

        return $this;
    }

    public function isStatusDraft(): bool
    {
        return $this->status === Status::DRAFT;
    }

    public function isStatusPublished(): bool
    {
        return $this->status === Status::PUBLISHED;
    }

    public function statusToString(): string
    {
        return Status::TEXT_STATUS[$this->status];
    }

    public function toStatusDraft(): void
    {
        $this->status = Status::DRAFT;
    }

    public function toStatusPublished(): void
    {
        $this->status = Status::PUBLISHED;
    }

    public function getCreatedAt(): DateTimeInterface
    {
        return $this->createdAt;
    }
}
