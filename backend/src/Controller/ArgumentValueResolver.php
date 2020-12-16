<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\CommandInterface;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\Serializer\SerializerInterface;

class ArgumentValueResolver implements ArgumentValueResolverInterface
{
    private SerializerInterface $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    /**
     * @phpcsSuppress() SlevomatCodingStandard.Functions.UnusedParameter
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     *
     * {@inheritdoc ()}
     */
    public function supports(Request $request, ArgumentMetadata $argument): bool
    {
        return \is_subclass_of((string) $argument->getType(), CommandInterface::class);
    }

    /**
     * {@inheritdoc ()}
     */
    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        $result = $this->serializer->deserialize($request->getContent(), (string) $argument->getType(), 'json');
        if (! \is_object($result)) {
            throw new BadRequestException('An object was expected and an array arrived.');
        }

        yield (object) $result;
    }
}
