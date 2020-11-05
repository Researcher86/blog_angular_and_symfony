<?php

declare(strict_types=1);

namespace App\Tests\Controller\User;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerTest extends WebTestCase
{
    public function testShow()
    {
        $client = static::createClient();

        $client->request('GET', '/api/users/5');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertSame('{"id":5,"name":"User 5"}', $client->getResponse()->getContent());
    }
}
