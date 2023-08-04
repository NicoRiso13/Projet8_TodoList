<?php

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerTest extends WebTestCase
{
    public function testLoginAction()
    {
        $client = static::createClient();
        $client->followRedirects();
        $crawler = $client->request('GET', '/');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        self::assertSame('http://localhost/login', $crawler->getUri());
        $form = $crawler->selectButton('Se connecter')->form();
        $form ['_username'] = 'admin';
        $form ['_password'] = '123456';
        $crawlerForm = $client->submit($form);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        self::assertSame('http://localhost/', $crawlerForm->getUri());
    }

    public function testListAction()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');
        $form = $crawler->selectButton('Se connecter')->form();
        $form ['_username'] = 'admin';
        $form ['_password'] = '123456';
        $client->submit($form);
        $crawlerPage = $client->request('GET', '/users');
        self::assertSame('http://localhost/users', $crawlerPage->getUri());
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertContains('Liste des utilisateurs', $client->getResponse()->getContent());



    }

    public function testCreateAction()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');
        $form = $crawler->selectButton('Se connecter')->form();
        $form ['_username'] = 'admin';
        $form ['_password'] = '123456';
        $client->submit($form);
        $crawler = $client->request('GET', '/');
        $formCreateUser = $crawler->selectLink('Créer un utilisateur')->form();
        $formCreateUser['user[username]'] = 'toto';
        $formCreateUser['user[password][first]'] = '123456';
        $formCreateUser['user[password][second]'] = '123456';
        $formCreateUser['user[email]'] = 'toto@gmail.com';
        $formCreateUser['user[roles]'] = '["ROLE_ADMIN"]';
        $client->followRedirects();

    }

    public function testEditAction()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/');
    }
}
