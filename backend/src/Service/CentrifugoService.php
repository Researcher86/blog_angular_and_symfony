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

    public function publish(string $channel, array $data = ['message' => 'Hello from PHP!'])
    {
        $this->client->publish($channel, $data);
    }

    public function generateToken($userId): string
    {
        return $this->client->generateConnectionToken($userId);
    }
}
