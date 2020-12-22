<?php

declare(strict_types=1);

namespace App\MessageHandler\Article;

use App\Entity\Article\Article;
use App\Entity\Article\Comment;
use App\Message\Article\SendEmailMessage;
use App\Notification\Article\ArticleReviewNotification;
use App\Notification\Article\CommentReviewNotification;
use App\Service\Article\ArticleService;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Notifier\Notifier;
use Symfony\Component\Notifier\NotifierInterface;
use Symfony\Component\Notifier\Recipient\Recipient;

final class SendEmailMessageHandler implements MessageHandlerInterface
{
    private LoggerInterface $logger;
    private NotifierInterface $notifier;
    private ArticleService $articleService;

    public function __construct(LoggerInterface $logger, NotifierInterface $notifier, ArticleService $articleService)
    {
        $this->logger = $logger;
        $this->notifier = $notifier;
        $this->articleService = $articleService;
    }

    public function __invoke(SendEmailMessage $message): void
    {
        $this->logger->debug('SendEmailMessageHandler...', ['email' => $message->getEmail()]);

        $recipients = [new Recipient($message->getEmail())];

        if ($message->isCopyToAdmins() && $this->notifier instanceof Notifier) {
            $this->logger->debug('Copy email to admins');
            $recipients += $this->notifier->getAdminRecipients();
        }

        if ($message->getType() === Article::class) {
            $this->logger->debug('Send article email...');
            $article = $this->articleService->getById($message->getArticleOrCommentId());
            $this->notifier->send(new ArticleReviewNotification($article), ...$recipients);
        } elseif ($message->getType() === Comment::class) {
            $this->logger->debug('Send comment email...');
            $comment = $this->articleService->getCommentById($message->getArticleOrCommentId());
            $this->notifier->send(new CommentReviewNotification($comment), ...$recipients);
        }
    }
}
