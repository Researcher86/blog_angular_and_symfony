<?php

declare(strict_types=1);

namespace App\Controller;

use Doctrine\ORM\EntityNotFoundException;
use Stringable;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Exception\ValidatorException;

class ExceptionListener implements EventSubscriberInterface
{
    private SerializerInterface $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        // You get the exception object from the received event
        $exception = $event->getThrowable();

        // Customize your response object to display the exception details
        $response = new JsonResponse();
        $response->setContent($this->serialize($exception->getMessage()));
        $response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);

        // HttpExceptionInterface is a special type of exception that
        // holds status code and header details
        if ($exception instanceof HttpExceptionInterface) {
            $response->setStatusCode($exception->getStatusCode());
            $response->headers->replace($exception->getHeaders());
        }

        switch (\get_class($exception)) {
            case ValidatorException::class:
                $response->setContent($exception->getMessage());
                $response->setStatusCode(Response::HTTP_BAD_REQUEST);
                break;
            case EntityNotFoundException::class:
                $response->setStatusCode(Response::HTTP_NOT_FOUND);
                break;
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

    /**
     * @param string|array<int|string, int|string|Stringable> $message
     */
    private function serialize($message): string
    {
        return $this->serializer->serialize($message, 'json', \array_merge([
            'json_encode_options' => JsonResponse::DEFAULT_ENCODING_OPTIONS,
        ]));
    }
}
