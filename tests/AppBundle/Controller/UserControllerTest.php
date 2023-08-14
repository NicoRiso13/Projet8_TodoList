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
        self::assertEquals(200, $client->getResponse()->getStatusCode());
        self::assertSame('http://localhost/login', $crawler->getUri());
        $form = $crawler->selectButton('Se connecter')->form();
        $form ['_username'] = 'admin';
        $form ['_password'] = '123456';
        $crawlerForm = $client->submit($form);
        self::assertEquals(200, $client->getResponse()->getStatusCode());
        self::assertSame('http://localhost/', $crawlerForm->getUri());
    }

    public function testListAction()
    {
        // On se log
        $client = static::createClient();
        $client->followRedirects();
        $crawler = $client->request('GET', '/login');
        $form = $crawler->selectButton('Se connecter')->form();
        $form ['_username'] = 'admin';
        $form ['_password'] = '123456';
        $client->submit($form);

        // On renseigne la page à atteindre
        $crawlerPage = $client->request('GET', '/tasks');
        self::assertEquals(200, $client->getResponse()->getStatusCode());
        self::assertSame('http://localhost/tasks', $crawlerPage->getUri());

    }

    public function testCreateAction()
    {
        // On se log
        $client = static::createClient();
        $client->followRedirects();
        $crawler = $client->request('GET', '/login');
        $form = $crawler->selectButton('Se connecter')->form();
        $form ['_username'] = 'admin';
        $form ['_password'] = '123456';
        $client->submit($form);

        // On renseigne la page à atteindre
        $crawlerPage = $client->request('GET', '/users');
        self::assertEquals(200, $client->getResponse()->getStatusCode());
        self::assertSame('http://localhost/users', $crawlerPage->getUri());

        // On selectionne le lien (Boutton) qu'on veut tester
        $link = $crawlerPage->selectLink('Créer un utilisateur')->link();
        $crawlerCreateUser = $client->click($link);

        // On renseigne le boutton qui permet de soumettre le formulaire
        $formCreateUser = $crawlerCreateUser->selectButton('Ajouter')->form();

        // On remplit le formulaire avec les champs requis
        $formCreateUser['user[username]'] = 'toto';
        $formCreateUser['user[password][first]'] = '123456';
        $formCreateUser['user[password][second]'] = '123456';
        $formCreateUser['user[email]'] = 'toto1@gmail.com';
        $formCreateUser['user[roles]'] = "ROLE_ADMIN";

        // On soumet le formulaire
        $crawlerSubmit = $client->submit($formCreateUser);

        // On suit la redirection
        $client->followRedirects();

        // Vérifier la redirection après la création de l'utilisateur
//        self::assertEquals(201, $client->getResponse()->getStatusCode());
        self::assertSame('http://localhost/users', $crawlerSubmit->getUri());

        // Vérifier la présence d'un nouvel utilisateur dans la liste
        self::assertStringContainsString('toto1@gmail.com', $client->getResponse()->getContent());
    }

    public function testEditAction()
    {
        // On se log
        $client = static::createClient();
        $client->followRedirects();
        $crawler = $client->request('GET', '/login');
        $form = $crawler->selectButton('Se connecter')->form();
        $form ['_username'] = 'admin';
        $form ['_password'] = '123456';
        $client->submit($form);
        $crawlerPage = $client->request('GET', '/users');
        self::assertEquals(200, $client->getResponse()->getStatusCode());
        self::assertSame('http://localhost/users', $crawlerPage->getUri());

        // On selectionne le lien (Boutton) qu'on veut tester
        $link = $crawlerPage->selectLink('Liste des utilisateurs')->link();
        $client->click($link);

        // On suit la redirection
        $client->followRedirects();

        // On selectionne le lien (Boutton) qu'on veut tester
        $link = $crawlerPage->selectLink('Edit')->link();
        $crawlerEditUser = $client->click($link);

        $crawlerPage = $client->request('GET', '/users/1/edit');
        self::assertEquals(200, $client->getResponse()->getStatusCode());
        self::assertSame('http://localhost/users/1/edit', $crawlerPage->getUri());

        // On renseigne le boutton qui permet de soumettre le formulaire
        $formEditUser = $crawlerEditUser->selectButton('Modifier')->form();

        // On remplit le formulaire avec les champs requis
        $formEditUser['user[username]'] = 'toto12';
        $formEditUser['user[password][first]'] = '123456';
        $formEditUser['user[password][second]'] = '123456';
        $formEditUser['user[email]'] = 'toto6@gmail.com';
        $formEditUser['user[roles]'] = "ROLE_ADMIN";

        // On soumet le formulaire
        $crawlerSubmit = $client->submit($formEditUser);

        // On suit la redirection
        $client->followRedirects();

        // Vérifier la redirection après la creation de l'utilisateur
        self::assertEquals(200, $client->getResponse()->getStatusCode());
        self::assertSame('http://localhost/users', $crawlerSubmit->getUri());

        // Vérifier la presence d'un nouvel utilisateur dans la liste
        self::assertStringContainsString('toto6@gmail.com', $client->getResponse()->getContent());
    }
}
