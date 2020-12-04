<?php

declare(strict_types=1);

namespace App\MessageHandler\Article;

use App\Message\Article\IndexingArticleMessage;
use App\Service\CentrifugoService;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

final class IndexingArticleMessageHandler implements MessageHandlerInterface
{
    private LoggerInterface $logger;
    private CentrifugoService $centrifugoService;

    public function __construct(LoggerInterface $logger, CentrifugoService $centrifugoService)
    {
        $this->logger = $logger;
        $this->centrifugoService = $centrifugoService;
    }

    public function __invoke(IndexingArticleMessage $message)
    {
        $this->logger->debug('IndexerMessageHandler', ['id' => $message->getArticleId()]);

        for ($i = 0; $i < 10; ++$i) {
            $this->centrifugoService->publish('news', ['message' => $i]);
        }
    }
}