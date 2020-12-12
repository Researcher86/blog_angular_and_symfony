<?php

declare(strict_types=1);

namespace App\Service;

use phpcent\Client;

class CentrifugoService
{
    private Client $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * @param array<string, string> $data
     */
    public function publish(string $channel, array $data = ['message' => 'Hello from PHP!']): void
    {
        $this->client->publish($channel, $data);
    }

    public function generateToken(int $userId): string
    {
        return $this->client->generateConnectionToken((string) $userId);
    }
}
