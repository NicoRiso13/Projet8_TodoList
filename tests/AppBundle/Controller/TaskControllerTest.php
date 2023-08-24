<?php

namespace Tests\AppBundle\Controller;

use App\AppBundle\Entity\Task;
use App\AppBundle\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\ORMException;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class TaskControllerTest extends WebTestCase
{

    public function testListAction()
    {
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
        $link = $crawlerPage->selectLink('Créer une tâche')->link();
        $crawlerCreateTask = $client->click($link);

        // On renseigne le boutton qui permet de soumettre le formulaire
        $formCreateTask = $crawlerCreateTask->selectButton('Ajouter')->form();

        // On remplit le formulaire avec les champs requis
        $formCreateTask['task[title]'] = 'testTask2';
        $formCreateTask['task[content]'] = 'Je suis le test fonctionnel task';

        // On soumet le formulaire
        $crawlerSubmit = $client->submit($formCreateTask);

        // On suit la redirection
        $client->followRedirects();

        // Vérifier la redirection après la creation de l'utilisateur
        self::assertEquals(200, $client->getResponse()->getStatusCode());
        self::assertSame('http://localhost/tasks', $crawlerSubmit->getUri());

        // Vérifier la presence d'un nouvel utilisateur dans la liste
        $value = "testTask1";
        $testValue = ["testTask1"];
        self::assertContains($value, $testValue, $client->getResponse()->getContent());
    }


    public function testEditAction()
    {
        self::bootKernel();

        //setup fixtures
        /** @var EntityManagerInterface $em */
        $em = self::$kernel->getContainer()->get('doctrine.orm.entity_manager');
        $taskId = 4;
        $taskToEdit = $em->getReference(Task::class, $taskId);
        $taskToEdit->setTitle("Original Task");
        $taskToEdit->setContent("Original Content Task");
        $em->flush();


        // On se log
        $client = static::createClient();
        $client->followRedirects();
        $crawler = $client->request('GET', '/login');
        $form = $crawler->selectButton('Se connecter')->form([
            '_username' => 'admin',
            '_password' => '123456'
        ]);
        $client->submit($form);

        // On renseigne la page à atteindre
        $client->request('GET', '/');
        $client->followRedirects();
        $value = "Bienvenue";
        $testValue = ["Bienvenue"];
        self::assertContains($value, $testValue, $client->getResponse()->getContent());


        $crawlerPageTaskId = $client->request('GET', '/tasks/'.$taskId.'/edit');
        self::assertEquals(200, $client->getResponse()->getStatusCode());


        // On renseigne le boutton qui permet de soumettre le formulaire
        $formModifyTask = $crawlerPageTaskId->selectButton('Modifier')->form();

        // On remplit le formulaire avec les champs requis
        $formModifyTask['task[title]'] = 'Task Modify';
        $formModifyTask['task[content]'] = 'Je suis le test fonctionnel task modifié avec fixtures';

        // On soumet le formulaire
        $crawlerSubmit = $client->submit($formModifyTask);

        // On suit la redirection
        $client->followRedirects();

        // Vérifier la redirection après la creation de l'utilisateur
        self::assertResponseStatusCodeSame(Response::HTTP_OK);
        self::assertSame('http://localhost/tasks', $crawlerSubmit->getUri());

        // Vérifier la presence d'un nouvel utilisateur dans la liste
        $value = "Task Modify";
        $testValue = ["Task Modify"];
        self::assertContains($value, $testValue, $client->getResponse()->getContent());
    }


    /**
     * @throws ORMException
     */
    public function testDeleteTaskAction()
    {
        self::bootKernel();

        //setup fixtures
        /** @var EntityManagerInterface $em */
        $em = self::$kernel->getContainer()->get('doctrine.orm.entity_manager');
        $user = $em->getReference(User::class, 1);
        $taskToDelete = new Task();
        $taskToDelete->setTitle("TEST TITLE");
        $taskToDelete->setContent("content test");
        $taskToDelete->setUser($user);
        $em->persist($taskToDelete);
        $em->flush();


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
        $link = $crawlerPage->filter('#delete_button_'.$taskToDelete->getId())->link();
        $crawlerTasksList = $client->click($link);

        self::assertSame('http://localhost/tasks', $crawlerTasksList->getUri());
        self::assertStringContainsString('La tâche a bien été supprimée.', $client->getResponse()->getContent());
    }


}
