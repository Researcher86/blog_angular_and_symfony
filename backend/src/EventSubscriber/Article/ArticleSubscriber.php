<?php

declare(strict_types=1);

namespace App\EventSubscriber\Article;

use App\Event\Article\ArticleCommentCreatedEvent;
use App\Event\Article\ArticleCreatedEvent;
use App\Event\Article\ArticlePublishedEvent;
use App\Message\Article\IndexingArticleMessage;
use App\Message\Article\SendEmailMessage;
use App\Message\Article\SendTelegramMessage;
use App\Service\User\UserService;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class ArticleSubscriber implements EventSubscriberInterface
{
    private LoggerInterface $logger;
    private MessageBusInterface $bus;
    private UserService $userService;

    public function __construct(LoggerInterface $logger, MessageBusInterface $bus, UserService $userService)
    {
        $this->logger = $logger;
        $this->bus = $bus;
        $this->userService = $userService;
    }

    public function onArticleCreatedEvent(ArticleCreatedEvent $event)
    {
        $this->logger->debug('ArticleCreatedEvent', \get_object_vars($event));

        $user = $this->userService->getById($event->getArticle()->getUserId());

        $title = 'Article submitted for moderation';
        $this->bus->dispatch(new SendEmailMessage(
            $title,
            $user->getEmail(),
            true,
            $event->getArticle()->getId(),
            \get_class($event->getArticle())
        ));
        $this->bus->dispatch(new SendTelegramMessage($title));
    }

    public function onArticlePublishedEvent(ArticlePublishedEvent $event)
    {
        $this->logger->debug('ArticlePublishedEvent', \get_object_vars($event));

        $user = $this->userService->getById($event->getArticle()->getUserId());

        $this->bus->dispatch(new IndexingArticleMessage($event->getArticle()->getId()));
        $this->bus->dispatch(new SendEmailMessage(
            'Article published',
            $user->getEmail(),
            false,
            $event->getArticle()->getId(),
            \get_class($event->getArticle())
        ));
    }

    public function onArticleCommentCreatedEvent(ArticleCommentCreatedEvent $event)
    {
        $this->logger->debug('ArticleCommentCreatedEvent', \get_object_vars($event));

        $user = $this->userService->getById($event->getComment()->getUserId());

        $title = 'Comment submitted for moderation';

        $this->bus->dispatch(new SendEmailMessage(
            $title,
            $user->getEmail(),
            true,
            $event->getComment()->getId(),
            \get_class($event->getComment())
        ));
        $this->bus->dispatch(new SendTelegramMessage($title));
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
