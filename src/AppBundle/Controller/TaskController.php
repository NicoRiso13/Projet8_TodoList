<?php

namespace App\AppBundle\Controller;

use App\AppBundle\Entity\Task;
use App\AppBundle\Entity\User;
use App\AppBundle\Form\TaskType;
use App\AppBundle\Manager\TaskManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class TaskController extends AbstractController
{
    /**
     * @Route("/tasks", name="task_list")
     */
    public function listAction(): Response
    {
        $user = $this->getUser();

        return $this->render('task/list.html.twig', [
            'tasks' => $this->getDoctrine()->getRepository('AppBundle:Task')->findAll(),
            'user' => $user
        ]);
    }

    /**
     * @Route("/tasks/create", name="task_create")
     */
    public function createAction(Request $request)
    {
        $task = new Task();

        $form = $this->createForm(TaskType::class, $task);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $user = $this->getUser();

            if (!$user instanceof User){
                throw $this->createAccessDeniedException();
            }

            $taskManager = $this->get(TaskManager::class);
            $taskManager->createTask($task, $user);

            $this->addFlash('success', 'La tâche a bien été ajoutée.');

            return $this->redirectToRoute('task_list');
        }
        return $this->render('task/create.html.twig', ['form' => $form->createView()]);

    }

    /**
     * @Route("/tasks/{id}/edit", name="task_edit")
     */
    public function editAction(Task $task, Request $request)
    {
        $form = $this->createForm(TaskType::class, $task);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $taskManager = $this->get(TaskManager::class);
            $taskManager->updateTask();

            $this->addFlash('success', 'La tâche a bien été modifiée.');

            return $this->redirectToRoute('task_list');
        }

        return $this->render('task/edit.html.twig', [
            'form' => $form->createView(),
            'task' => $task,
        ]);
    }

    /**
     * @Route("/tasks/{id}/toggle", name="task_toggle")
     */
    public function toggleTaskAction(Task $task)
    {
        $taskManager = $this->get(TaskManager::class);
        $taskManager->toggleTask($task);

        $this->addFlash('success', sprintf('La tâche %s a bien été marquée comme faite.', $task->getTitle()));

        return $this->redirectToRoute('task_list');
    }

    /**
     * @Route("/tasks/{id}/delete", name="task_delete")
     */
    public function deleteTaskAction(Task $task)
    {
        $user = $this->getUser();

        if (!$user instanceof User){
            throw $this->createAccessDeniedException();
        }

        $userId = $user->getId();

        $taskUserId = $task->getUser() !== null ? $task->getUser()->getId(): null ;

        
        if ($taskUserId === $userId || ($taskUserId === null && $this->isGranted('ROLE_ADMIN')))  {

            $taskManager = $this->get(TaskManager::class);
            $taskManager->deleteTask($task);

            $this->addFlash('success', 'La tâche a bien été supprimée.');
        }else{
            $this->addFlash('error', 'Vous n\'avez pas les droits necessaire pour supprimer cette tache.');
        }

        return $this->redirectToRoute('task_list');
    }
}
