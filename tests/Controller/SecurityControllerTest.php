<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class SecurityControllerTest extends WebTestCase
{
    public function testLoginAction()
    {
        $client = static::createClient();
        $client->request('GET', '/login');
        self::assertResponseStatusCodeSame(Response::HTTP_OK);
        self::assertSelectorNotExists('.alert.alert-danger');
    }

    public function testLoginWithBadCredentials()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');
        $loginForm = $crawler->selectButton('Se connecter')->form([
            '_username' => 'admin',
            '_password' => '1111111',
        ]);
        $client->submit($loginForm);

        self::assertResponseRedirects('http://localhost/login');
        $client->followRedirect();
        self::assertSelectorExists('.alert.alert-danger');
    }

    public function testLoginWithSuccess()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');
        self::assertResponseStatusCodeSame(Response::HTTP_OK);
        $loginForm = $crawler->selectButton('Se connecter')->form([
            '_username' => 'admin1',
            '_password' => 'password',
        ]);
        $client->submit($loginForm);

        self::assertResponseRedirects('http://localhost/');
        $client->followRedirect();
        self::assertSelectorTextSame('h6', "L'utilisateur est connecté avec le compte:admin1@email.com");
    }
}
