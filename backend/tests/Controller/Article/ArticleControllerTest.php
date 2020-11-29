<?php

declare(strict_types=1);

namespace App\Tests\Controller\Article;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Messenger\Transport\InMemoryTransport;

class ArticleControllerTest extends WebTestCase
{
    public function testShow()
    {
        $client = static::createClient();

        $client->request('GET', '/api/articles/5');

        $this->assertResponseIsSuccessful();
        $this->assertNotEmpty($client->getResponse()->getContent());
    }

    public function testGetAll()
    {
        $client = static::createClient();

        $client->request('GET', '/api/articles');

        $this->assertResponseIsSuccessful();
        $this->assertNotEmpty($client->getResponse()->getContent());
    }

    public function testCreateArticleSuccess()
    {
        $client = static::createClient();

        $client->request('POST', '/api/articles', [], [], [], \json_encode(['name' => 'Test', 'userId' => 5, 'content' => 'Text']));

        $this->assertResponseIsSuccessful();
        $this->assertNotEmpty($client->getResponse()->getContent());

        /* @var InMemoryTransport $transport */
        $transport = self::$container->get('messenger.transport.async_es');
        $this->assertCount(1, $transport->getSent());

        /* @var InMemoryTransport $transport */
        $transport = self::$container->get('messenger.transport.async_email');
        $this->assertCount(1, $transport->getSent());

        return \json_decode($client->getResponse()->getContent());
    }

    /**
     * @depends testCreateArticleSuccess
     */
    public function testDelete()
    {
        $client = static::createClient();
        $args = \func_get_args();

        $client->request('DELETE', '/api/articles/' . $args[0]->id);

        $this->assertResponseStatusCodeSame(204);
        $this->assertEmpty($client->getResponse()->getContent());
    }
}
