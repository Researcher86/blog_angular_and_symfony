<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\KernelEvents;

class ExceptionListener implements EventSubscriberInterface
{
    public function onKernelException(ExceptionEvent $event): void
    {
        // You get the exception object from the received event
        $exception = $event->getThrowable();

        // Customize your response object to display the exception details
        $response = new JsonResponse();
        $response->setContent($exception->getMessage());
        $response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);

        // HttpExceptionInterface is a special type of exception that
        // holds status code and header details
        if ($exception instanceof HttpExceptionInterface) {
            $response->setStatusCode($exception->getStatusCode());
            $response->headers->replace($exception->getHeaders());
        }

        // sends the modified response object to the event
        $event->setResponse($response);
    }

    /**
     * @return array<string, array<array<int, string|int>>>
     */
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::EXCEPTION => [
                ['onKernelException', 50],
            ],
        ];
    }
}
