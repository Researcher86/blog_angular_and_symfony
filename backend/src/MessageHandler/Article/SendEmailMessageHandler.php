<?php

declare(strict_types=1);

namespace App\MessageHandler\Article;

use App\Message\Article\SendEmailMessage;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

final class SendEmailMessageHandler implements MessageHandlerInterface
{
    private LoggerInterface $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function __invoke(SendEmailMessage $message)
    {
        \sleep(100);
        $this->logger->debug('SendEmailMessageHandler', ['email' => $message->getEmail()]);
    }
}
