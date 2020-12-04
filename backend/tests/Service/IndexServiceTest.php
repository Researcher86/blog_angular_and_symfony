<?php

declare(strict_types=1);

namespace App\Tests\Service;

use App\Service\IndexService;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class IndexServiceTest extends KernelTestCase
{
    private ?IndexService $indexService;

    protected function setUp(): void
    {
        self::bootKernel();
        $container = self::$container;

        $this->indexService = $container->get(IndexService::class);
    }

    public function testPing()
    {
        self::assertTrue($this->indexService->ping());
    }
}
