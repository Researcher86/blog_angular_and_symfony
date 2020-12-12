<?php

declare(strict_types=1);

namespace App\Notification\Article;

use App\Entity\Article\Article;
use Symfony\Bridge\Twig\Mime\NotificationEmail;
use Symfony\Component\Notifier\Message\EmailMessage;
use Symfony\Component\Notifier\Notification\EmailNotificationInterface;
use Symfony\Component\Notifier\Notification\Notification;
use Symfony\Component\Notifier\Recipient\Recipient;

class ArticleReviewNotification extends Notification implements EmailNotificationInterface
{
    private Article $article;

    public function __construct(Article $article, string $subject = 'New article posted')
    {
        $this->article = $article;
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
        $rawMessage->htmlTemplate('emails/article_notification.html.twig')
            ->context(['article' => $this->article]);
        return $message;
    }
}
