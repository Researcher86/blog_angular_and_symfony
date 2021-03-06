<?php

declare(strict_types=1);

namespace App\Controller\User;

use App\Controller\User\Dto\ViewUser;
use App\Service\User\Command\CreateUser;
use App\Service\User\UserService;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Annotations as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
class UserController extends AbstractController
{
    private UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * @Route("/users/{id}", methods={"GET"}, name="user_show")
     *
     * @OA\Response(
     *     response=200,
     *     description="Return user",
     *     @OA\JsonContent(
     *        type="object",
     *        @OA\Property(property="id", type="integer"),
     *        @OA\Property(property="name", type="string"),
     *        @OA\Property(property="email", type="string"),
     *     )
     * )
     *
     * @OA\Response(
     *     response=404,
     *     description="User not found"
     * )
     *
     * @OA\Tag(name="Users")
     *
     * @Security(name="Bearer")
     */
    public function show(int $id): Response
    {
        return $this->json(
            ViewUser::createFrom($this->userService->getById($id))
        );
    }

    /**
     * @Route("/users/{id}", methods={"DELETE"}, name="user_delete")
     *
     * @OA\Response(
     *     response=204,
     *     description="Delete user",
     * )
     *
     * @OA\Response(
     *     response=404,
     *     description="User not found"
     * )
     *
     * @OA\Tag(name="Users")
     *
     * @Security(name="Bearer")
     */
    public function delete(int $id): Response
    {
        $this->userService->delete($id);
        return $this->json([], Response::HTTP_NO_CONTENT);
    }

    /**
     * @Route("/users", methods={"GET"}, name="user_list")
     *
     * @OA\Response(
     *     response=200,
     *     description="Return users",
     *     @OA\JsonContent(
     *        type="array",
     *        @OA\Items(
     *          @OA\Property(property="id", type="integer"),
     *          @OA\Property(property="name", type="string"),
     *          @OA\Property(property="email", type="string"),
     *        )
     *     )
     * )
     *
     * @OA\Tag(name="Users")
     *
     * @Security(name="Bearer")
     */
    public function list(): Response
    {
        $users = $this->userService->getAll();
        $users = \array_map(static fn ($user): ViewUser => ViewUser::createFrom($user), $users);

        return $this->json($users);
    }

    /**
     * @Route("/users", methods={"POST"}, name="user_create")
     *
     * @OA\RequestBody(
     *     @OA\JsonContent(
     *         type="object",
     *         required={"name"},
     *         @OA\Property(property="name", type="string"),
     *         @OA\Property(property="email", type="string"),
     *         @OA\Property(property="plainPassword", type="string"),
     *     ),
     * )
     *
     * @OA\Response(
     *     response=201,
     *     description="Created user",
     *     @OA\JsonContent(
     *        type="object",
     *        @OA\Property(property="id", type="integer"),
     *        @OA\Property(property="name", type="string"),
     *        @OA\Property(property="email", type="string"),
     *     )
     * )
     *
     * @OA\Response(
     *     response=400,
     *     description="Invalid input",
     *     @OA\JsonContent(
     *        type="object",
     *        @OA\Property(property="field", type="string")
     *     )
     * )
     *
     * @OA\Tag(name="Users")
     *
     * @Security(name="Bearer")
     */
    public function create(CreateUser $command): Response
    {
        return $this->json(
            ViewUser::createFrom($this->userService->create($command)),
            Response::HTTP_CREATED
        );
    }
}
