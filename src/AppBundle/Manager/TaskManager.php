<?php

namespace AppBundle\Manager;

use AppBundle\Entity\Task;
use AppBundle\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class TaskManager
{
    private EntityManagerInterface $entityManager;



    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;

    }

    public function createTask(Task $task, User $user)
    {
        $task->setUser($user);
        $this->entityManager->persist($task);
        $this->entityManager->flush();
    }

    public function updateTask()
    {
        $this->entityManager->flush();
    }

    public function toggleTask(Task $task)
    {
        $task->toggle(!$task->isDone());
        $this->entityManager->flush();
    }

    public function deleteTask(Task $task)
    {
        $this->entityManager->remove($task);
        $this->entityManager->flush();

    }



}
