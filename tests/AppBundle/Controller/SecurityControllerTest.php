<?php

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class SecurityControllerTest extends WebTestCase
{
    public function testLoginAction()
    {
        $client = static::createClient();
        $client->request('GET', '/login');
        self::assertResponseStatusCodeSame(Response::HTTP_OK);
        self::assertSelectorTextContains('h1','Se Connecter');
        self::assertSelectorNotExists('.alert.alert-danger');
    }

    public function testLoginWithBadCredentials()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');
        $loginForm= $crawler->selectButton('Se connecter')->form([
            '_username' => 'admin',
            '_password' => '1111111'
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
        $loginForm= $crawler->selectButton('Se connecter')->form([
            '_username' => 'admin',
            '_password' => '123456'
        ]);
        $client->submit($loginForm);
        self::assertResponseRedirects('http://localhost/');
        $client->followRedirect();
        self::assertSelectorTextSame('h6',"L'utilisateur est connecté avec le compte: admin@admin");
    }



}
