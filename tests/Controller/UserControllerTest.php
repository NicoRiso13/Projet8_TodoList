<?php

namespace App\Tests\Controller;

use App\Tests\Utils\NeedLogin;
use App\DataFixtures\TaskTestFixtures;
use App\DataFixtures\UserTestFixtures;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\HttpFoundation\Response;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerTest extends WebTestCase
{
    public function testListAction()
    {
        // On se log
        $client = static::createClient();
        $client->followRedirects();
        $crawler = $client->request('GET', '/login');
        $form = $crawler->selectButton('Se connecter')->form();
        $form ['_username'] = 'admin1';
        $form ['_password'] = 'password';
        $client->submit($form);
        $crawlerPage = $client->request('GET', '/tasks');
        self::assertEquals(200, $client->getResponse()->getStatusCode());
        self::assertSame('http://localhost/tasks', $crawlerPage->getUri());


        // On renseigne la page à atteindre
        $crawlerPage = $client->request('GET', '/tasks');
        self::assertEquals(200, $client->getResponse()->getStatusCode());
        self::assertSame('http://localhost/tasks', $crawlerPage->getUri());

        // Vérifier la presence d'un nouvel utilisateur dans la liste
        $value = "Liste des utilisateurs";
        $testValue = ["Liste des utilisateurs"];
        self::assertContains($value, $testValue, $client->getResponse()->getContent());

    }

    public function testCreateAction()
    {
        // On se log
        $client = static::createClient();
        $client->followRedirects();
        $crawler = $client->request('GET', '/login');
        $form = $crawler->selectButton('Se connecter')->form();
        $form ['_username'] = 'admin1';
        $form ['_password'] = 'password';
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
        $formCreateUser['user[username]'] = 'admin';
        $formCreateUser['user[password][first]'] = 'password';
        $formCreateUser['user[password][second]'] = 'password';
        $formCreateUser['user[email]'] = 'admin@gmail.com';
        $formCreateUser['user[roles]'] = "ROLE_ADMIN";

        // On soumet le formulaire
        $client->submit($formCreateUser);

        // On suit la redirection
        $client->followRedirects();

        // Vérifier la redirection après la création de l'utilisateur
        $crawlerSubmit = $client->request('GET', '/users');
        self::assertSame('http://localhost/users', $crawlerSubmit->getUri());

        // Vérifier la presence d'un nouvel utilisateur dans la liste
        $value = "admin@gmail.com";
        $testValue = ["admin@gmail.com"];
        self::assertContains($value, $testValue, $client->getResponse()->getContent());
    }

    public function testEditAction()
    {
        self::ensureKernelShutdown();
        $client = static::createClient();

        $userId = 3;

        // On se log
        $crawler = $client->request('GET', '/login');
        $loginForm = $crawler->selectButton('Se connecter')->form([
            '_username' => 'admin1',
            '_password' => 'password'
        ]);
        $client->submit($loginForm);

        // On renseigne la page à atteindre
        $client->request('GET', '/');
        $client->followRedirects();
        $value = "Bienvenue";
        $testValue = ["Bienvenue"];
        self::assertContains($value, $testValue, $client->getResponse()->getContent());

        $crawlerPageUserId = $client->request('GET', '/users/'. $userId .'/edit');
        self::assertEquals(200, $client->getResponse()->getStatusCode());

        // On renseigne le boutton qui permet de soumettre le formulaire
        $formEditUser = $crawlerPageUserId->selectButton('Modifier')->form();

        // On remplit le formulaire avec les champs requis
        $formEditUser['user[username]'] = 'user3';
        $formEditUser['user[password][first]'] = 'password';
        $formEditUser['user[password][second]'] = 'password';
        $formEditUser['user[email]'] = 'user3@gmail.com';
        $formEditUser['user[roles]'] = "ROLE_USER";

        // On soumet le formulaire
        $crawlerSubmit = $client->submit($formEditUser);

        // On suit la redirection

        $client->followRedirects();

        // Vérifier la redirection après la creation de l'utilisateur
        self::assertResponseStatusCodeSame(Response::HTTP_OK);



        // Vérifier la presence d'un nouvel utilisateur dans la liste
        self::assertSelectorTextContains('table',"user3@gmail.com");
    }


}
