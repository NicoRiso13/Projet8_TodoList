<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * @internal
 *
 * @coversNothing
 */
class DefaultControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/');

        self::assertEquals(302, $client->getResponse()->getStatusCode());
        $value = "Welcome to Symfony";
        $testValue = ["Welcome to Symfony"];
        self::assertContains($value,$testValue, $client->getResponse()->getStatusCode());
        self::assertSame('http://localhost/', $crawler->getUri());
    }
}
