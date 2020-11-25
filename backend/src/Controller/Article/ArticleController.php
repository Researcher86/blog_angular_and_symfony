<?php

declare(strict_types=1);

namespace App\Controller\Article;

use App\Controller\Article\Dto\ViewArticle;
use App\Controller\BaseController;
use App\Service\Article\ArticleService;
use App\Service\Article\Param\CreateParam;
use Doctrine\ORM\EntityNotFoundException;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ArticleController extends BaseController
{
    private ArticleService $articleService;

    public function __construct(ValidatorInterface $validator, SerializerInterface $serializer, ArticleService $articleService)
    {
        parent::__construct($validator, $serializer);
        $this->articleService = $articleService;
    }

    /**
     * @Route("/articles/{id}", methods={"GET"}, name="article_show")
     * @OA\Response(
     *     response=200,
     *     description="Return article",
     *     @OA\JsonContent(
     *        type="object",
     *        @OA\Property(property="id", type="integer"),
     *        @OA\Property(property="userId", type="integer"),
     *        @OA\Property(property="name", type="string"),
     *        @OA\Property(property="text", type="string"),
     *     )
     * ),
     * @OA\Response(
     *     response=404,
     *     description="Article not found"
     * ),
     * @OA\Tag(name="Articles")
     * @Security(name="Bearer")
     *
     * @param int $id
     * @return Response
     */
    public function show(int $id): Response
    {
        try {
            $article = $this->articleService->getById($id);
            return $this->json(ViewArticle::createFrom($article));
        } catch (EntityNotFoundException $e) {
            return $this->json([], Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * @Route("/articles/{id}", methods={"DELETE"}, name="article_delete")
     * @OA\Response(
     *     response=204,
     *     description="Delete article",
     * ),
     * @OA\Response(
     *     response=404,
     *     description="Article not found"
     * ),
     * @OA\Tag(name="Articles")
     * @Security(name="Bearer")
     *
     * @param int $id
     * @return Response
     */
    public function delete(int $id): Response
    {
        try {
            $this->articleService->delete($id);
            return $this->json([], Response::HTTP_NO_CONTENT);
        } catch (EntityNotFoundException $e) {
            return $this->json([], Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * @Route("/articles", methods={"GET"}, name="article_list")
     * @OA\Response(
     *     response=200,
     *     description="Return articles",
     *     @OA\JsonContent(
     *        type="array",
     *        @OA\Items(
     *          @OA\Property(property="id", type="integer"),
     *          @OA\Property(property="userId", type="integer"),
     *          @OA\Property(property="name", type="string"),
     *          @OA\Property(property="text", type="string"),
     *        )
     *     )
     * )
     * @OA\Tag(name="Articles")
     * @Security(name="Bearer")
     *
     * @return Response
     */
    public function list(): Response
    {
        $articles = $this->articleService->getAll();
        return $this->json(
            \array_map(fn ($article) => ViewArticle::createFrom($article), $articles)
        );
    }

    /**
     * @Route("/articles", methods={"POST"}, name="article_create")
     * @OA\RequestBody(
     *     @OA\JsonContent(
     *         type="object",
     *         required={"name", "userId", "text"},
     *         @OA\Property(property="name", type="string"),
     *         @OA\Property(property="userId", type="integer"),
     *         @OA\Property(property="text", type="string"),
     *     ),
     * ),
     * @OA\Response(
     *     response=201,
     *     description="Created article",
     *     @OA\JsonContent(
     *        type="object",
     *        @OA\Property(property="id", type="integer"),
     *        @OA\Property(property="name", type="string"),
     *        @OA\Property(property="userId", type="integer"),
     *        @OA\Property(property="text", type="string"),
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
     * @OA\Tag(name="Articles")
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
            $article = $this->articleService->create($param);
            return $this->json(ViewArticle::createFrom($article), Response::HTTP_CREATED);
        } catch (EntityNotFoundException $e) {
            return $this->json($e->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }
}
