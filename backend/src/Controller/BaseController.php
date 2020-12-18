<?php

declare(strict_types=1);

namespace App\Controller;

use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

abstract class BaseController extends AbstractController
{
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
