<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\CentrifugoService;
use OpenApi\Annotations as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/auth")
 *
 * @package App\Controller
 */
class AuthController extends AbstractController
{
    private CentrifugoService $centrifugoService;

    public function __construct(CentrifugoService $centrifugoService)
    {
        $this->centrifugoService = $centrifugoService;
    }

    /**
     * @Route("/centrifugo/token/{userId}", methods={"GET"}, name="auth_centrifugo_token")
     *
     * @OA\Response(
     *     response=200,
     *     description="Return token",
     *     @OA\JsonContent(
     *        type="object",
     *        @OA\Property(property="token", type="string"),
     *     )
     * )
     *
     * @OA\Tag(name="Auth")
     */
    public function getCentrifugoToken(int $userId): Response
    {
        return $this->json(['token' => $this->centrifugoService->generateToken($userId)]);
    }
}
