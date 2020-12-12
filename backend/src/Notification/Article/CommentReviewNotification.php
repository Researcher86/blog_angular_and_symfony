<?php

declare(strict_types=1);

namespace App\Notification\Article;

use App\Entity\Article\Comment;
use Symfony\Bridge\Twig\Mime\NotificationEmail;
use Symfony\Component\Notifier\Message\EmailMessage;
use Symfony\Component\Notifier\Notification\EmailNotificationInterface;
use Symfony\Component\Notifier\Notification\Notification;
use Symfony\Component\Notifier\Recipient\Recipient;

class CommentReviewNotification extends Notification implements EmailNotificationInterface
{
    private Comment $comment;

    public function __construct(Comment $comment, string $subject = 'New comment posted')
    {
        $this->comment = $comment;
        parent::__construct($subject);
    }

    /**
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    public function asEmailMessage(Recipient $recipient, ?string $transport = null): ?EmailMessage
    {
        $message = EmailMessage::fromNotification($this, $recipient);
        if ($transport) {
            $message->transport($transport);
        }

        /** @var NotificationEmail $rawMessage */
        $rawMessage = $message->getMessage();
        $rawMessage->htmlTemplate('emails/comment_notification.html.twig')
            ->context(['comment' => $this->comment]);

        return $message;
    }
}
