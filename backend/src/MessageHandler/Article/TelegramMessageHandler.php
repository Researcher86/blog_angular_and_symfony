<?php

declare(strict_types=1);

namespace App\MessageHandler\Article;

use App\Message\Article\SendTelegramMessage;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Notifier\Notification\Notification;
use Symfony\Component\Notifier\NotifierInterface;
use Symfony\Component\Notifier\Recipient\Recipient;

final class TelegramMessageHandler implements MessageHandlerInterface
{
    private LoggerInterface $logger;
    private NotifierInterface $notifier;

    public function __construct(LoggerInterface $logger, NotifierInterface $notifier)
    {
        $this->logger = $logger;
        $this->notifier = $notifier;
    }

    public function __invoke(SendTelegramMessage $message): void
    {
        $this->logger->debug('TelegramMessageHandler...');
        $notification = new Notification($message->getText(), ['chat/telegram']);
//        $notification->content()
        $this->notifier->send($notification, new Recipient());
    }
}
