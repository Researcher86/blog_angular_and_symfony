<?php

declare(strict_types=1);

namespace App\Messenger;

use Symfony\Component\Messenger\Stamp\StampInterface;

class UniqueIdStamp implements StampInterface
{
    private string $uniqueId;

    public function __construct()
    {
        $this->uniqueId = \uniqid('t', true);
    }

    public function getUniqueId(): string
    {
        return $this->uniqueId;
    }
}
