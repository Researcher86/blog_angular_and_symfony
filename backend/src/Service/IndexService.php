<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Article\Article;
use Elasticsearch\Client;
use Elasticsearch\Common\Exceptions\Missing404Exception;
use Psr\Log\LoggerInterface;

class IndexService
{
    private const INDEX_ARTICLES = 'articles';

    private Client $client;
    private LoggerInterface $logger;

    public function __construct(Client $client, LoggerInterface $logger)
    {
        $this->client = $client;
        $this->logger = $logger;
    }

    public function ping(): bool
    {
        return $this->client->ping();
    }

    public function add(Article $article): void
    {
        $params = [
            'index' => self::INDEX_ARTICLES,
            'id' => $article->getId(),
            'body' => [
                'name' => $article->getName(),
                'content' => $article->getContent(),
                'user_id' => $article->getUserId(),
            ],
        ];

        $this->client->index($params);
    }

    /**
     * @return array<int, string>
     */
    public function get(int $articleId): array
    {
        try {
            return $this->client->getSource([
                'index' => self::INDEX_ARTICLES,
                'id' => $articleId,
            ]);
        } catch (Missing404Exception $exception) {
            $this->logger->debug($exception->getMessage());
            return [];
        }
    }

    public function delete(int $articleId): void
    {
        try {
            $this->client->delete([
                'index' => self::INDEX_ARTICLES,
                'id' => $articleId,
            ]);
        } catch (Missing404Exception $exception) {
            $this->logger->debug($exception->getMessage());
        }
    }

    /**
     * @return array<int, array<string, array<string>>>
     */
    public function search(string $text, int $page = 1, int $limit = 10): array
    {
        $params = [
            'index' => self::INDEX_ARTICLES,
            'body' => [
                '_source' => [''],
                'from' => ($page - 1) * $limit,
                'size' => $limit,
                'query' => [
                    'multi_match' => [
                        'query' => $text,
                        'fields' => ['name^4', 'content^3'],
                    ],
                ],
                'highlight' => [
                    'fields' => [
                        '*' => [
                            'fragment_size' => 150,
                            'number_of_fragments' => 4,
                            'pre_tags' => ['<b>'],
                            'post_tags' => ['</b>'],
                            'require_field_match' => true,
                        ],
                    ],
                ],
            ],
        ];

        return \array_column($this->client->search($params)['hits']['hits'], 'highlight');
    }
}
