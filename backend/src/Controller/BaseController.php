<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;
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

    public function validate($param): array
    {
        $constraints = $this->validator->validate($param);
        if ($constraints->count() > 0) {
            $errors = [];
            foreach ($constraints as $error) {
                $errors[$error->getPropertyPath()] = $error->getMessage();
            }

            return $errors;
        }

        return [];
    }

    public function deserialize(Request $request, string $type, string $format = 'json'): object
    {
        return $this->serializer->deserialize($request->getContent(), $type, $format);
    }
}
