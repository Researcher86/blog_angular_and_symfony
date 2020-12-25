<?php

declare(strict_types=1);

namespace App\MessageHandler\Article;

use App\Entity\Article\Article;
use App\Message\Article\PlagiarismArticleMessage;
use App\Message\Article\SendEmailMessage;
use App\Service\Article\ArticleIndexerInterface;
use App\Service\Article\ArticleService;
use App\Service\Article\ComparatorInterface;
use App\Service\CentrifugoService;
use App\Service\User\UserService;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Messenger\MessageBusInterface;

use function Symfony\Component\String\u;

final class PlagiarismArticleMessageHandler implements MessageHandlerInterface
{
    private LoggerInterface $logger;
    private CentrifugoService $centrifugoService;
    private ArticleIndexerInterface $indexer;
    private ComparatorInterface $comparator;
    private ArticleService $articleService;
    private MessageBusInterface $bus;
    private UserService $userService;

    public function __construct(
        LoggerInterface $logger,
        CentrifugoService $centrifugoService,
        ArticleService $articleService,
        UserService $userService,
        ArticleIndexerInterface $indexer,
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
                $msg = \sprintf(
                    'Article "%s" received a plagiarism rating below the norm [%.2f].',
                    $this->truncateArticleName($article),
                    $result
                );
                $this->logger->debug('PlagiarismArticleMessageHandler', [$msg]);
                $this->sendMessageToFrontend($msg, $article);

                return;
            }
        }

        $this->sendMessageToFrontend(\sprintf(
            'Article "%s" passed plagiarism.',
            $this->truncateArticleName($article)
        ), $article);

        $this->bus->dispatch(new SendEmailMessage(
            'Article passed plagiarism.',
            $user->getEmail(),
            true,
            (int) $article->getId(),
            $article
        ));
    }

    private function sendMessageToFrontend(string $message, Article $article): void
    {
        $this->centrifugoService->publish('news', [
            'message' => $message,
            'id' => (int) $article->getId(),
            'name' => 'article',
        ]);
    }

    private function truncateArticleName(Article $article): string
    {
        return u($article->getName())->truncate(30, '...')->toString();
    }
}
