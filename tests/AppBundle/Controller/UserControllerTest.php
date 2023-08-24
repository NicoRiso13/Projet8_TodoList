<?php

namespace Tests\AppBundle\Controller;

use App\AppBundle\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class UserControllerTest extends WebTestCase
{

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
        $form ['_username'] = 'admin';
        $form ['_password'] = '123456';
        $client->submit($form);

        // On renseigne la page à atteindre
        $crawlerPage = $client->request('GET', '/tasks');
        self::assertEquals(200, $client->getResponse()->getStatusCode());
        self::assertSame('http://localhost/tasks', $crawlerPage->getUri());

        // On selectionne le lien (Boutton) qu'on veut tester
        $link = $crawlerPage->selectLink('Créer un utilisateur')->link();
        $crawlerCreateUser = $client->click($link);

        // On renseigne le boutton qui permet de soumettre le formulaire
        $formCreateUser = $crawlerCreateUser->selectButton('Ajouter')->form();

        // On remplit le formulaire avec les champs requis
        $formCreateUser['user[username]'] = 'test1';
        $formCreateUser['user[password][first]'] = '123456';
        $formCreateUser['user[password][second]'] = '123456';
        $formCreateUser['user[email]'] = 'test1@gmail.com';
        $formCreateUser['user[roles]'] = "ROLE_USER";

        // On soumet le formulaire
        $crawlerSubmit = $client->submit($formCreateUser);

        // On suit la redirection
        $client->followRedirects();

        // Vérifier la redirection après la création de l'utilisateur
        $crawlerSubmit = $client->request('GET', '/users');
        self::assertSame('http://localhost/users', $crawlerSubmit->getUri());

        // Vérifier la presence d'un nouvel utilisateur dans la liste
        $value = "test1@gmail.com";
        $testValue = ["test1@gmail.com"];
        self::assertContains($value, $testValue, $client->getResponse()->getContent());
    }

    public function testEditAction()
    {
        self::bootKernel();

        $userId = 6;

        // On se log
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');
        $loginForm = $crawler->selectButton('Se connecter')->form([
            '_username' => 'admin',
            '_password' => '123456'
        ]);
        $client->submit($loginForm);

        // On renseigne la page à atteindre
        $client->request('GET', '/');
        $client->followRedirects();
        $value = "Bienvenue";
        $testValue = ["Bienvenue"];
        self::assertContains($value, $testValue, $client->getResponse()->getContent());

        $crawlerPageUserId = $client->request('GET', '/users/' . $userId . '/edit');
        self::assertEquals(200, $client->getResponse()->getStatusCode());

        // On renseigne le boutton qui permet de soumettre le formulaire
        $formEditUser = $crawlerPageUserId->selectButton('Modifier')->form();

        // On remplit le formulaire avec les champs requis
        $formEditUser['user[username]'] = 'TestController';
        $formEditUser['user[password][first]'] = '123456';
        $formEditUser['user[password][second]'] = '123456';
        $formEditUser['user[email]'] = 'Test@gmail.com';
        $formEditUser['user[roles]'] = "ROLE_USER";

        // On soumet le formulaire
        $crawlerSubmitForm = $client->submit($formEditUser);

        // On suit la redirection
        $client->followRedirects();

        // Vérifier la redirection après la creation de l'utilisateur
        self::assertResponseStatusCodeSame(Response::HTTP_OK);
        self::assertSame('http://localhost/users', $crawlerSubmitForm->getUri());


        // Vérifier la presence d'un nouvel utilisateur dans la liste
        self::assertSelectorTextContains('table',"Test@gmail.com");
    }
}
