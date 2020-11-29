<?php

declare(strict_types=1);

namespace App\MessageHandler\Article;

use App\Message\Article\IndexerMessage;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

final class IndexerMessageHandler implements MessageHandlerInterface
{
    private LoggerInterface $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function __invoke(IndexerMessage $message)
    {
        \sleep(100);
//        throw new \RuntimeException();
        $this->logger->debug('IndexerMessageHandler', ['id' => $message->getArticleId()]);
    }
}
