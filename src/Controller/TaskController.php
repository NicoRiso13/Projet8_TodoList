<?php

namespace App\Controller;

use App\Entity\Task;
use App\Entity\User;
use App\Form\TaskType;
use App\Manager\TaskManager;
use App\Repository\TaskRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;


class TaskController extends AbstractController
{


    private TaskManager $taskManager;
    private EntityManagerInterface $entityManager;
    private Security $security;


    public function __construct(TaskManager $taskManager, EntityManagerInterface $entityManager, Security $security)
    {

        $this->taskManager = $taskManager;
        $this->entityManager = $entityManager;

        $this->security = $security;
    }

    /**
     * Gérer l'affichage de la liste des tâches.
     *
     * @Route("/tasks", name="task_list")
     */
    public function listAction(TaskRepository $taskRepository): Response
    {
        /** @var User $user */

        return $this->render('task/list.html.twig', [
                'tasks' => $taskRepository->findAll(),


            ]
        );
    }

    /**
     * Gérer l'affichage de la liste des tâches terminées.
     *
     * @Route("/tasks/done", name="task_done_list")
     *
     * @return Response
     */
    public function doneListAction(TaskRepository $taskRepository)
    {
        return $this->render('task/listDone.html.twig', [
                'tasks' => $taskRepository->findAll(),

            ]
        );
    }

    /**
     * Gérer la création de nouvelles tâches.
     *
     * @Route("/tasks/create", name="task_create")
     *
     * @param Request $request
     * @param TaskManager $taskManager
     * @return Response
     */
    public function createAction(Request $request, TaskManager $taskManager): Response
    {
        $task = new Task();
        $form = $this->createForm(TaskType::class, $task);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {


            $task->setAuthor($this->security->getUser());
            $taskManager->createTask($task);
            $this->addFlash('success', 'La tâche a été bien été ajoutée.');

            return $this->redirectToRoute('task_list');
        }

        return $this->render('task/create.html.twig', ['form' => $form->createView()]);
    }

    /**
     * Gérer l'édition de tâches existante.
     *
     * @Route("/tasks/{id}/edit", name="task_edit")
     *
     * @param Task $task
     * @param Request $request
     * @return Response
     */
    public function editAction(Task $task, Request $request): Response
    {
        $form = $this->createForm(TaskType::class, $task);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->taskManager->updateTask();
            $this->addFlash('success', 'La tâche a bien été modifiée.');

            return $this->redirectToRoute('task_list');
        }

        return $this->render('task/edit.html.twig', [
            'form' => $form->createView(),
            'task' => $task,
        ]);
    }

    /**
     * Gérer la modification du statut des tâches.
     *
     * @Route("/tasks/{id}/toggle", name="task_toggle")
     *
     * @param Task $task
     * @return Response
     */
    public function toggleTaskAction(Task $task): Response
    {
        $task = $this->taskManager->handleToggleAction($task);
        $status = $task->isDone() ? 'faite' : 'non terminée';
        $this->addFlash('success', sprintf('La tâche %s a bien été marquée comme ' . $status, $task->getTitle()));

        return $this->redirectToRoute('task_list');
    }

    /**
     * Gérer la suppression de tâches limitée à l'auteur ou à l'administrateur de la tâche pour les tâches anonymes.
     * @param Task $task
     * @param TaskManager $taskManager
     * @return Response
     * @Route("/tasks/{id}/delete", name="task_delete")
     * @IsGranted("TASK_DELETE", subject="task", statusCode=401)
     */
    public function deleteTaskAction(Task $task, TaskManager $taskManager): Response
    {
        $user = $this->getUser();

        if (!$user instanceof User) {
            throw $this->createAccessDeniedException();
        }
        $userId = $user->getId();

        $taskUserId = $task->getAuthor() !== null ? $task->getAuthor()->getId() : null;
        if ($taskUserId === $userId || ($taskUserId === null && $this->isGranted('ROLE_ADMIN'))) {

            $taskManager->deleteTask($task);

            $this->addFlash('success', 'La tâche a bien été supprimée.');
        } else {
            $this->addFlash('error', 'Vous n\'avez pas les droits necessaire pour supprimer cette tache.');
        }

        return $this->redirectToRoute('task_list');
    }
}
