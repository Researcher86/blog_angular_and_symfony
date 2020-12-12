<?php

declare(strict_types=1);

namespace App\Controller;

use Exception;
use Stringable;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

abstract class BaseController extends AbstractController
{
    protected ValidatorInterface $validator;
    protected SerializerInterface $serializer;

    public function __construct(ValidatorInterface $validator, SerializerInterface $serializer)
    {
        $this->validator = $validator;
        $this->serializer = $serializer;
    }

    /**
     * @return array<int|string, string|Stringable>
     */
    protected function validate(object $param): array
    {
        $constraints = $this->validator->validate($param);
        if ($constraints->count() > 0) {
            $errors = [];
            foreach ($constraints as $error) {
                assert($error instanceof ConstraintViolation);
                $errors[$error->getPropertyPath()] = $error->getMessage();
            }

            return $errors;
        }

        return [];
    }

    protected function isValid(object $object): ?Response
    {
        $errors = $this->validate($object);

        if ($errors) {
            return $this->json($errors, Response::HTTP_BAD_REQUEST);
        }

        return null;
    }

    protected function deserialize(Request $request, string $type, string $format = 'json'): object
    {
        $result = $this->serializer->deserialize($request->getContent(), $type, $format);
        if (! \is_object($result)) {
            throw new BadRequestException('An object was expected and an array arrived.');
        }

        return (object) $result;
    }

    protected function makeResponse(callable $fun, callable $success, callable $fail): Response
    {
        try {
            /** @var object $result */
            $result = $fun();
            /** @psalm-suppress MixedArgument */
            return $this->json(...$success($result));
        } catch (Exception $exception) {
            /** @psalm-suppress MixedArgument */
            return $this->json(...$fail($exception));
        }
    }
}
