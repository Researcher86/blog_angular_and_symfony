<?php

declare(strict_types=1);

namespace App\MessageHandler\Article;

use App\Message\Article\PlagiarismArticleMessage;
use App\Message\Article\SendEmailMessage;
use App\Service\Article\ArticleService;
use App\Service\Article\ComparatorInterface;
use App\Service\CentrifugoService;
use App\Service\IndexerInterface;
use App\Service\User\UserService;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Messenger\MessageBusInterface;

final class PlagiarismArticleMessageHandler implements MessageHandlerInterface
{
    private LoggerInterface $logger;
    private CentrifugoService $centrifugoService;
    private IndexerInterface $indexer;
    private ComparatorInterface $comparator;
    private ArticleService $articleService;
    private MessageBusInterface $bus;
    private UserService $userService;

    public function __construct(
        LoggerInterface $logger,
        CentrifugoService $centrifugoService,
        ArticleService $articleService,
        UserService $userService,
        IndexerInterface $indexer,
        ComparatorInterface $comparator,
        MessageBusInterface $bus
    ) {
        $this->logger = $logger;
        $this->centrifugoService = $centrifugoService;
        $this->articleService = $articleService;
        $this->userService = $userService;
        $this->indexer = $indexer;
        $this->comparator = $comparator;
        $this->bus = $bus;
    }

    public function __invoke(PlagiarismArticleMessage $message): void
    {
        $this->logger->debug('PlagiarismArticleMessageHandler', ['id' => $message->getArticleId()]);

        $user = $this->userService->getById($message->getUserId());
        $article = $this->articleService->getById($message->getArticleId());
        $articlesInIndex = $this->indexer->search($article->getContent(), 1, 3);

        foreach ($articlesInIndex as $articleInIndex) {
            $result = $this->comparator->compare($article->getContent(), $articleInIndex['content'][0]);
            if ($result > 50) {
                $msg = [
                    'message' => \sprintf(
                        'Article [%d] received a plagiarism rating below the norm [%.2f].',
                        $article->getId(),
                        $result
                    ),
                ];
                $this->logger->debug('PlagiarismArticleMessageHandler', $msg);

                $this->centrifugoService->publish('news', $msg);
                return;
            }
        }

        $this->centrifugoService->publish('news', [
            'message' => \sprintf('Article [%d] passed plagiarism.', $article->getId()),
        ]);

        $this->bus->dispatch(new SendEmailMessage(
            \sprintf('Article [%d] passed plagiarism.', $article->getId()),
            $user->getEmail(),
            true,
            (int) $article->getId(),
            \get_class($article)
        ));
    }
}
