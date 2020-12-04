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

    public function __invoke(SendEmailMessage $message)
    {
        $this->logger->debug('SendEmailMessageHandler...', ['email' => $message->getEmail()]);

        if ($message->isCopyToAdmins()) {
            $this->logger->debug('Copy email to admins');
        }

        $recipients = $message->isCopyToAdmins() ?
            [new Recipient($message->getEmail()), ...$this->notifier->getAdminRecipients()] :
            [new Recipient($message->getEmail())]
        ;

        if ($message->getType() === Article::class) {
            $this->logger->debug('Send article email...');
            $article = $this->articleService->getById($message->getId());
            $this->notifier->send(new ArticleReviewNotification($article), ...$recipients);
        } elseif ($message->getType() === Comment::class) {
            $this->logger->debug('Send comment email...');
            $comment = $this->articleService->getCommentById($message->getId());
            $this->notifier->send(new CommentReviewNotification($comment), ...$recipients);
        }
    }
}
