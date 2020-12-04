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

        $this->assertResponseIsSuccessful();
        $this->assertNotEmpty($client->getResponse()->getContent());
    }

    public function testGetAll()
    {
        $client = static::createClient();

        $client->request('GET', '/api/users');

        $this->assertResponseIsSuccessful();
        $this->assertNotEmpty($client->getResponse()->getContent());
    }

    public function testCreateUserSuccess()
    {
        $client = static::createClient();

        $client->request('POST', '/api/users', [], [], [], \json_encode([
            'name' => 'Test',
            'email' => 'Test@Test.com',
            'plainPassword' => 'Test@Test.com',
        ]));

        $this->assertResponseIsSuccessful();
        $this->assertNotEmpty($client->getResponse()->getContent());

        return \json_decode($client->getResponse()->getContent());
    }

    /**
     * @depends testCreateUserSuccess
     */
    public function testDelete()
    {
        $client = static::createClient();

        $args = \func_get_args();
        $client->request('DELETE', '/api/users/' . $args[0]->id);

        $this->assertResponseStatusCodeSame(204);
        $this->assertEmpty($client->getResponse()->getContent());
    }
}
