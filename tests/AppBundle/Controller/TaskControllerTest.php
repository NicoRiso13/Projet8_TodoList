<?php

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TaskControllerTest extends WebTestCase
{

    public function testListAction()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/');
    }

    public function testCreateAction()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/');
    }

    public function testEditAction()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/');
    }

    public function testToggleTaskAction()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/');
    }

    public function testDeleteTaskAction()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/');
    }

}
