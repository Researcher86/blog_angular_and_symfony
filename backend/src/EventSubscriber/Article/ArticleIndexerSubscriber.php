<?php

declare(strict_types=1);

namespace App\EventSubscriber\Article;

use App\Event\Article\ArticleCreatedEvent;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ArticleIndexerSubscriber implements EventSubscriberInterface
{
    private LoggerInterface $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function onArticleCreatedEvent(ArticleCreatedEvent $event)
    {
        $this->logger->debug('ArticleCreatedEvent', \get_object_vars($event));
    }

    public static function getSubscribedEvents()
    {
        return [
            ArticleCreatedEvent::class => 'onArticleCreatedEvent',
        ];
    }
}
