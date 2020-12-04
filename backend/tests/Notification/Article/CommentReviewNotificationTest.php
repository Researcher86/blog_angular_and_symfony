<?php

namespace App\Tests\Notification\Article;

use App\Entity\Article\Comment;
use App\Notification\Article\CommentReviewNotification;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Notifier\Notification\Notification;
use Symfony\Component\Notifier\NotifierInterface;
use Symfony\Component\Notifier\Recipient\Recipient;

class CommentReviewNotificationTest extends KernelTestCase
{
    private ?NotifierInterface $notifier;

    protected function setUp(): void
    {
        //  self::bootKernel();
        //
        //  // returns the real and unchanged service container
        //  $container = self::$kernel->getContainer();
        //
        //  // gets the special container that allows fetching private services
        //  $container = self::$container;
        //
        //  $notifier = self::$container->get('notifier');

        self::bootKernel();
        $container = self::$container;

        $this->notifier = $container->get(NotifierInterface::class);
    }

    public function testSendEmail()
    {
        $notification = (new CommentReviewNotification(
            new Comment(1, 'New comment'),
            'CommentReviewNotificationTest->testSendEmail()'
        )
        )->importance(Notification::IMPORTANCE_LOW);

        $this->notifier->send($notification, ...$this->notifier->getAdminRecipients());
        self::assertTrue(true);
    }

    public function testSendMessageToTelegram()
    {
        $notification = (new Notification('CommentReviewNotificationTest->testSendMessageToTelegram()'))
            ->importance(Notification::IMPORTANCE_MEDIUM);

        $this->notifier->send($notification, new Recipient());
        self::assertTrue(true);
    }
}
