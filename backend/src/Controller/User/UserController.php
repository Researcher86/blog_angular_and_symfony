<?php

declare(strict_types=1);

namespace App\Controller\User;

use App\Entity\User\User;
use App\Service\User\UserService;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Annotations as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    private UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * @Route("/users/{id}", methods={"GET"}, name="user_show")
     * @OA\Response(
     *     response=200,
     *     description="Return user",
     *     @OA\JsonContent(
     *        type="object",
     *        @OA\Property(property="id", type="integer"),
     *        @OA\Property(property="name", type="string")
     *     )
     * )
     * @OA\Tag(name="Users")
     * @Security(name="Bearer")
     *
     * @param int $id
     * @return Response
     */
    public function show(int $id): Response
    {
        $user = $this->userService->getById($id);

//        return $this->json($user);
        return $this->json([
            'id'   => $user->getId(),
            'name' => $user->getName(),
        ]);
    }
}
