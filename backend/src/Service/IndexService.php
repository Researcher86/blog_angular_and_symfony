<?php

declare(strict_types=1);

namespace App\Service;

use Elasticsearch\Client;

class IndexService
{
    private Client $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function ping(): bool
    {
        return $this->client->ping();
    }
}
