<?php

declare(strict_types=1);

namespace App\EventSubscriber\Article;

use App\Event\Article\ArticleCommentCreatedEvent;
use App\Event\Article\ArticleCreatedEvent;
use App\Event\Article\ArticlePublishedEvent;
use App\Message\Article\IndexerMessage;
use App\Message\Article\SendEmailMessage;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class ArticleSubscriber implements EventSubscriberInterface
{
    private LoggerInterface $logger;
    private MessageBusInterface $bus;

    public function __construct(LoggerInterface $logger, MessageBusInterface $bus)
    {
        $this->logger = $logger;
        $this->bus = $bus;
    }

    public function onArticleCreatedEvent(ArticleCreatedEvent $event)
    {
        $this->logger->debug('ArticleCreatedEvent', \get_object_vars($event));

        $this->bus->dispatch(new SendEmailMessage('test@test.com'));
    }

    public function onArticlePublishedEvent(ArticlePublishedEvent $event)
    {
        $this->logger->debug('ArticlePublishedEvent', \get_object_vars($event));

        $this->bus->dispatch(new IndexerMessage($event->getArticle()->getId()));
        $this->bus->dispatch(new SendEmailMessage('test@test.com'));
    }

    public function onArticleCommentCreatedEvent(ArticleCommentCreatedEvent $event)
    {
        $this->logger->debug('ArticleCommentCreatedEvent', \get_object_vars($event));
        $this->bus->dispatch(new SendEmailMessage('test@test.com'));
    }

    public static function getSubscribedEvents()
    {
        return [
            ArticleCreatedEvent::class        => 'onArticleCreatedEvent',
            ArticlePublishedEvent::class      => 'onArticlePublishedEvent',
            ArticleCommentCreatedEvent::class => 'onArticleCommentCreatedEvent',
        ];
    }
}
