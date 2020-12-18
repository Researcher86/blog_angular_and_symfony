<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\CommandInterface;
use Stringable;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ArgumentValueResolver implements ArgumentValueResolverInterface
{
    private SerializerInterface $serializer;
    private ValidatorInterface $validator;

    public function __construct(SerializerInterface $serializer, ValidatorInterface $validator)
    {
        $this->serializer = $serializer;
        $this->validator = $validator;
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
            throw new BadRequestHttpException('An object was expected and an array arrived.');
        }

        $errors = $this->validate((object) $result);

        if ($errors) {
            $json = $this->serializer->serialize($errors, 'json', \array_merge([
                'json_encode_options' => JsonResponse::DEFAULT_ENCODING_OPTIONS,
            ]));
            throw new BadRequestHttpException($json);
        }

        yield (object) $result;
    }

    /**
     * @return array<int|string, string|Stringable>
     */
    private function validate(object $param): array
    {
        $constraints = $this->validator->validate($param);
        if ($constraints->count() > 0) {
            $errors = [];
            foreach ($constraints as $error) {
                \assert($error instanceof ConstraintViolation);
                $errors[$error->getPropertyPath()] = $error->getMessage();
            }

            return $errors;
        }

        return [];
    }
}
