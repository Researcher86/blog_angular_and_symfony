<?php

declare(strict_types=1);

namespace App\Event\Article;

use App\Entity\Article\Comment;

class ArticleCommentCreatedEvent
{
    private Comment $comment;
    public function __construct(Comment $comment)
    {
        $this->comment = $comment;
    }

    public function getComment(): Comment
    {
        return $this->comment;
    }
}
