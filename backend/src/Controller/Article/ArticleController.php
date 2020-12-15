<?php

declare(strict_types=1);

namespace App\Controller\Article;

use App\Controller\Article\Dto\ViewArticle;
use App\Controller\Article\Dto\ViewComment;
use App\Controller\BaseController;
use App\Entity\Article\Article;
use App\Entity\Article\Comment;
use App\Service\Article\ArticleService;
use App\Service\Article\Command\CreateArticle;
use App\Service\Article\Command\CreateComment;
use Exception;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
class ArticleController extends BaseController
{
    private ArticleService $articleService;

    public function __construct(ArticleService $articleService)
    {
        parent::__construct();

        $this->articleService = $articleService;
    }

    /**
     * @Route("/articles/{id}", methods={"GET"}, name="article_show")
     *
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
     * )
     *
     * @OA\Response(
     *     response=404,
     *     description="Article not found"
     * )
     *
     * @OA\Tag(name="Articles")
     *
     * @Security(name="Bearer")
     */
    public function show(int $id/*, CentrifugoService $centrifugoService*/): Response
    {
//        $centrifugoService->publish('news');

        return $this->makeResponse(
            fn (): object => $this->articleService->getById($id),
            static fn (Article $article): array => [ViewArticle::createFrom($article)],
            static fn (): array => [[], Response::HTTP_NOT_FOUND]
        );
    }

    /**
     * @Route("/articles/{id}", methods={"DELETE"}, name="article_delete")
     *
     * @OA\Response(
     *     response=204,
     *     description="Delete article",
     * )
     *
     * @OA\Response(
     *     response=404,
     *     description="Article not found"
     * )
     *
     * @OA\Tag(name="Articles")
     *
     * @Security(name="Bearer")
     */
    public function delete(int $id): Response
    {
        return $this->makeResponse(
            fn (): object => $this->articleService->delete($id),
            static fn (): array => [[], Response::HTTP_NO_CONTENT],
            static fn (): array => [[], Response::HTTP_NOT_FOUND]
        );
    }

    /**
     * @Route("/articles", methods={"GET"}, name="article_list")
     *
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
     *
     * @OA\Tag(name="Articles")
     *
     * @Security(name="Bearer")
     */
    public function list(): Response
    {
        $articles = $this->articleService->getAll();
        return $this
            ->json(\array_map(static fn ($article): ViewArticle => ViewArticle::createFrom($article), $articles))
            ->setSharedMaxAge(3600);
    }

    /**
     * @Route("/articles", methods={"POST"}, name="article_create")
     *
     * @OA\RequestBody(
     *     @OA\JsonContent(
     *         type="object",
     *         required={"name", "userId", "content"},
     *         @OA\Property(property="name", type="string"),
     *         @OA\Property(property="userId", type="integer"),
     *         @OA\Property(property="content", type="string"),
     *     )
     * )
     *
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
     * @OA\Tag(name="Articles")
     *
     * @Security(name="Bearer")
     */
    public function create(Request $request): Response
    {
        /** @var CreateArticle $command */
        $command = $this->deserialize($request, CreateArticle::class);

        return $this->isValid($command) ?? $this->makeResponse(
            fn (): object => $this->articleService->create($command),
            static fn (Article $article): array => [ViewArticle::createFrom($article), Response::HTTP_CREATED],
            static fn (Exception $exception): array => [$exception->getMessage(), Response::HTTP_BAD_REQUEST]
        );
    }
    /**
     * @Route("/articles/{articleId}/comments", methods={"POST"}, name="article_create_comment")
     *
     * @OA\RequestBody(
     *     @OA\JsonContent(
     *         type="object",
     *         required={"userId", "content"},
     *         @OA\Property(property="userId", type="integer"),
     *         @OA\Property(property="content", type="string"),
     *     )
     * )
     *
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
     * @OA\Tag(name="Articles")
     *
     * @Security(name="Bearer")
     */
    public function createComment(int $articleId, Request $request): Response
    {
        /** @var CreateComment $command */
        $command = $this->deserialize($request, CreateComment::class);

        return $this->isValid($command) ?? $this->makeResponse(
            fn (): object => $this->articleService->createComment($articleId, $command),
            static fn (Comment $comment): array => [ViewComment::createFrom($comment), Response::HTTP_CREATED],
            static fn (Exception $exception): array => [$exception->getMessage(), Response::HTTP_BAD_REQUEST]
        );
    }
}
