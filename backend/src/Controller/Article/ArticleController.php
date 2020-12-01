<?php

declare(strict_types=1);

namespace App\Controller\Article;

use App\Controller\Article\Dto\ViewArticle;
use App\Controller\Article\Dto\ViewComment;
use App\Controller\BaseController;
use App\Service\Article\ArticleService;
use App\Service\Article\Command\CreateArticle;
use App\Service\Article\Command\CreateComment;
use App\Service\CentrifugoService;
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
     *        @OA\Property(property="content", type="string"),
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
    public function show(int $id, CentrifugoService $centrifugoService): Response
    {
        $centrifugoService->publish('news');

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
     *          @OA\Property(property="content", type="string"),
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
     *         required={"name", "userId", "content"},
     *         @OA\Property(property="name", type="string"),
     *         @OA\Property(property="userId", type="integer"),
     *         @OA\Property(property="content", type="string"),
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
     *        @OA\Property(property="content", type="string"),
     *        @OA\Property(property="status", type="string"),
     *        @OA\Property(property="createdAt", type="string"),
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
        /** @var CreateArticle $command */
        $command = $this->deserialize($request, CreateArticle::class);
        $errors = $this->validate($command);

        if ($errors) {
            return $this->json($errors, Response::HTTP_BAD_REQUEST);
        }

        try {
            $article = $this->articleService->create($command);
            return $this->json(ViewArticle::createFrom($article), Response::HTTP_CREATED);
        } catch (EntityNotFoundException $e) {
            return $this->json($e->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }
    /**
     * @Route("/articles/{articleId}/comments", methods={"POST"}, name="article_create_comment")
     * @OA\RequestBody(
     *     @OA\JsonContent(
     *         type="object",
     *         required={"userId", "content"},
     *         @OA\Property(property="userId", type="integer"),
     *         @OA\Property(property="content", type="string"),
     *     ),
     * ),
     * @OA\Response(
     *     response=201,
     *     description="Created comment",
     *     @OA\JsonContent(
     *        type="object",
     *        @OA\Property(property="id", type="integer"),
     *        @OA\Property(property="userId", type="integer"),
     *        @OA\Property(property="content", type="string"),
     *        @OA\Property(property="createdAt", type="string"),
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
    public function createComment(int $articleId, Request $request)
    {
        /** @var CreateComment $command */
        $command = $this->deserialize($request, CreateComment::class);
        $errors = $this->validate($command);

        if ($errors) {
            return $this->json($errors, Response::HTTP_BAD_REQUEST);
        }

        try {
            $comment = $this->articleService->createComment($articleId, $command);
            return $this->json(ViewComment::createFrom($comment), Response::HTTP_CREATED);
        } catch (EntityNotFoundException $e) {
            return $this->json($e->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }
}
