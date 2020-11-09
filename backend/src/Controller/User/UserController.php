<?php

declare(strict_types=1);

namespace App\Controller\User;

use App\Controller\User\Dto\ViewUser;
use App\Core\Exception\AppValidationException;
use App\Service\User\Param\CreateParam;
use App\Service\User\UserService;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Annotations as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class UserController extends AbstractController
{
    private SerializerInterface $serializer;
    private UserService $userService;

    public function __construct(SerializerInterface $serializer, UserService $userService)
    {
        $this->userService = $userService;
        $this->serializer = $serializer;
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
     * ),
     * @OA\Response(
     *     response=404,
     *     description="User not found"
     * ),
     * @OA\Tag(name="Users")
     * @Security(name="Bearer")
     *
     * @param int $id
     * @return Response
     */
    public function show(int $id): Response
    {
        $user = $this->userService->getById($id);
        if (!$user) {
            return $this->json('User not found', 404);
        }

        $user = ViewUser::createFrom($user);

        return $this->json($user);
    }

    /**
     * @Route("/users", methods={"GET"}, name="user_list")
     * @OA\Response(
     *     response=200,
     *     description="Return users",
     *     @OA\JsonContent(
     *        type="array",
     *        @OA\Items(
     *          @OA\Property(property="id", type="integer"),
     *          @OA\Property(property="name", type="string")
     *        )
     *     )
     * )
     * @OA\Tag(name="Users")
     * @Security(name="Bearer")
     *
     * @return Response
     */
    public function list(): Response
    {
        $users = $this->userService->getAll();
        $users = \array_map(fn ($user) => ViewUser::createFrom($user), $users);

        return $this->json($users);
    }

    /**
     * @Route("/users", methods={"POST"}, name="user_create")
     * @OA\RequestBody(
     *     @OA\JsonContent(
     *         type="object",
     *         required={"name"},
     *         @OA\Property(property="name", type="string"),
     *     ),
     * ),
     * @OA\Response(
     *     response=200,
     *     description="Return user",
     *     @OA\JsonContent(
     *        type="object",
     *        @OA\Property(property="id", type="integer"),
     *        @OA\Property(property="name", type="string")
     *     )
     * ),
     * @OA\Response(
     *     response=400,
     *     description="Invalid input",
     *     @OA\JsonContent(
     *        type="object",
     *        @OA\Property(property="field", type="string")
     *     )
     * ),
     * @OA\Tag(name="Users")
     * @Security(name="Bearer")
     *
     * @param Request $request
     * @return Response
     */
    public function create(Request $request)
    {
        $param = $this->serializer->deserialize($request->getContent(), CreateParam::class, 'json');
        try {
            $user = $this->userService->create($param);
            return $this->json(ViewUser::createFrom($user), 201);
        } catch (AppValidationException $e) {
            return $this->json($e->getErrors(), 400);
        }
    }
}
