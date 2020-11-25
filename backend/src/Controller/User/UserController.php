<?php

declare(strict_types=1);

namespace App\Controller\User;

use App\Controller\BaseController;
use App\Controller\User\Dto\ViewUser;
use App\Core\Exception\AppException;
use App\Service\User\Param\CreateParam;
use App\Service\User\UserService;
use Doctrine\ORM\EntityNotFoundException;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserController extends BaseController
{
    private UserService $userService;

    public function __construct(ValidatorInterface $validator, SerializerInterface $serializer, UserService $userService)
    {
        parent::__construct($validator, $serializer);
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
        try {
            $user = $this->userService->getById($id);
            return $this->json(ViewUser::createFrom($user));
        } catch (EntityNotFoundException $e) {
            return $this->json([], Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * @Route("/users/{id}", methods={"DELETE"}, name="user_delete")
     * @OA\Response(
     *     response=204,
     *     description="Delete user",
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
    public function delete(int $id): Response
    {
        try {
            $this->userService->delete($id);
            return $this->json([], Response::HTTP_NO_CONTENT);
        } catch (EntityNotFoundException $e) {
            return $this->json([], Response::HTTP_NOT_FOUND);
        }
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
     *     response=201,
     *     description="Created user",
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
        /** @var CreateParam $param */
        $param = $this->deserialize($request, CreateParam::class);
        $errors = $this->validate($param);

        if ($errors) {
            return $this->json($errors, Response::HTTP_BAD_REQUEST);
        }

        try {
            $user = $this->userService->create($param);
            return $this->json(ViewUser::createFrom($user), Response::HTTP_CREATED);
        } catch (AppException $e) {
            return $this->json($e->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }
}
