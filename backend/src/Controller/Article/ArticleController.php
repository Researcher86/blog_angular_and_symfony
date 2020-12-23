<?php

declare(strict_types=1);

namespace App\Controller\Article;

use App\Controller\Article\Dto\ViewArticle;
use App\Controller\Article\Dto\ViewComment;
use App\Service\Article\ArticleService;
use App\Service\Article\Command\CreateArticle;
use App\Service\Article\Command\CreateComment;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Annotations as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
class ArticleController extends AbstractController
{
    private ArticleService $articleService;

    public function __construct(ArticleService $articleService)
    {
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
    public function show(int $id): Response
    {
        return $this->json(ViewArticle::createFrom($this->articleService->getById($id)));
    }

    /**
     * @Route("/articles/{id}/published", methods={"PUT"}, name="article_published")
     *
     * @OA\Response(
     *     response=200,
     *     description="Article published"
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
    public function published(int $id): Response
    {
        return $this->json(ViewArticle::createFrom($this->articleService->published($id)));
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
        $this->articleService->delete($id);
        return $this->json([], Response::HTTP_NO_CONTENT);
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
    public function create(CreateArticle $command): Response
    {
        return $this->json(
            ViewArticle::createFrom($this->articleService->create($command)),
            Response::HTTP_CREATED
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
    public function createComment(int $articleId, CreateComment $command): Response
    {
        return $this->json(
            ViewComment::createFrom($this->articleService->createComment($articleId, $command)),
            Response::HTTP_CREATED
        );
    }
}
