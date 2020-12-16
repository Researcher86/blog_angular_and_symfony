<?php

declare(strict_types=1);

namespace App\Controller\User;

use App\Controller\BaseController;
use App\Controller\User\Dto\ViewUser;
use App\Entity\User\User;
use App\Service\User\Command\CreateUser;
use App\Service\User\UserService;
use Exception;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
class UserController extends BaseController
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
        return $this->makeResponse(
            fn (): object => $this->userService->getById($id),
            static fn (User $user): array => [ViewUser::createFrom($user)],
            static fn (): array => [[], Response::HTTP_NOT_FOUND]
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
        return $this->makeResponse(
            fn (): object => $this->userService->delete($id),
            static fn (): array => [[], Response::HTTP_NO_CONTENT],
            static fn (): array => [[], Response::HTTP_NOT_FOUND]
        );
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
        return $this->isValid($command) ?? $this->makeResponse(
            fn (): object => $this->userService->create($command),
            static fn (User $user): array => [ViewUser::createFrom($user), Response::HTTP_CREATED],
            static fn (Exception $exception): array => [$exception->getMessage(), Response::HTTP_BAD_REQUEST]
        );
    }
}
