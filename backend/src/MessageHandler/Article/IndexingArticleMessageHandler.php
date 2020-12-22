<?php

declare(strict_types=1);

namespace App\MessageHandler\Article;

use App\Message\Article\IndexingArticleMessage;
use App\Service\Article\ArticleService;
use App\Service\CentrifugoService;
use App\Service\IndexerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

use function Symfony\Component\String\u;

final class IndexingArticleMessageHandler implements MessageHandlerInterface
{
    private LoggerInterface $logger;
    private CentrifugoService $centrifugoService;
    private ArticleService $articleService;
    private IndexerInterface $indexer;

    public function __construct(
        LoggerInterface $logger,
        CentrifugoService $centrifugoService,
        ArticleService $articleService,
        IndexerInterface $indexer
    ) {
        $this->logger = $logger;
        $this->centrifugoService = $centrifugoService;
        $this->articleService = $articleService;
        $this->indexer = $indexer;
    }

    public function __invoke(IndexingArticleMessage $message): void
    {
        $this->logger->debug('IndexingArticleMessageHandler', ['id' => $message->getArticleId()]);

        $article = $this->articleService->getById($message->getArticleId());
        $this->indexer->add($article);

        $this->centrifugoService->publish('news', [
            'message' => \sprintf('Article [%s] add in index', u($article->getName())->truncate(20)),
        ]);
    }
}
