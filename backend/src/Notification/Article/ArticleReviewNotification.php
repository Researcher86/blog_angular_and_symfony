<?php

declare(strict_types=1);

namespace App\Notification\Article;

use App\Entity\Article\Article;
use Symfony\Component\Notifier\Message\ChatMessage;
use Symfony\Component\Notifier\Message\EmailMessage;
use Symfony\Component\Notifier\Notification\ChatNotificationInterface;
use Symfony\Component\Notifier\Notification\EmailNotificationInterface;
use Symfony\Component\Notifier\Notification\Notification;
use Symfony\Component\Notifier\Recipient\Recipient;

class ArticleReviewNotification extends Notification implements EmailNotificationInterface, ChatNotificationInterface
{
    private Article $article;
    public function __construct(Article $article, string $subject = 'New article posted')
    {
        $this->article = $article;
        parent::__construct($subject);
    }

    public function asEmailMessage(Recipient $recipient, string $transport = null): ?EmailMessage
    {
        $message = EmailMessage::fromNotification($this, $recipient);
        if (null !== $transport) {
            $message->transport($transport);
        }
        $message->getMessage()
            ->htmlTemplate('emails/article_notification.html.twig')
            ->context(['article' => $this->article]);
        return $message;
    }

    public function asChatMessage(Recipient $recipient, string $transport = null): ?ChatMessage
    {
        if ('telegram' !== $transport) {
            return null;
        }

        $message = ChatMessage::fromNotification($this, $recipient, $transport);
        $message->subject($this->getSubject());
        $message->options((new SlackOptions())
            ->iconEmoji('tada')
            ->iconUrl('https://guestbook.example.com')
            ->username('Guestbook')
            ->block((new SlackSectionBlock())->text($this->getSubject()))
            ->block(new SlackDividerBlock())
            ->block((new SlackSectionBlock())
            ->text(\sprintf('%s (%s) says: %s', $this->comment->getAuthor(), $this->comment->getEmail(), $this->comment->getText()))));
        return $message;
    }
}
