<?php

namespace App\Manager;

use App\Entity\Task;
use App\Repository\TaskRepository;
use Doctrine\ORM\EntityManagerInterface;


class TaskManager
{
    private EntityManagerInterface $entityManager;
    private TaskRepository $taskRepository;


    public function __construct(TaskRepository $taskRepository,EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->taskRepository = $taskRepository;
    }
    /**
     * Gérer la récupération de la liste des tâches à partir de la base de données.
     *
     * @param bool $isDone
     *
     * @return array
     */
    public function handleListAction(bool $isDone = false)
    {
        return $this->taskRepository->findBy(['isDone' => $isDone]);
    }

    /**
     * Gérer la modification du statut des tâches.
     *
     * @param Task $task
     *
     * @return Task $task
     */
    public function handleToggleAction(Task $task): Task
    {
        $task->toggle(!$task->isDone());
        $this->entityManager->flush();

        return $task;
    }

    public function createTask(Task $task): void
    {

        $this->entityManager->persist($task);
        $this->entityManager->flush();
    }

    public function updateTask(): void
    {
        $this->entityManager->flush();
    }

    public function deleteTask(Task $task): void
    {
        $this->entityManager->remove($task);
        $this->entityManager->flush();

    }



}
