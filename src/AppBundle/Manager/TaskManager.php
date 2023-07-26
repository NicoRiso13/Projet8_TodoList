<?php

namespace AppBundle\Manager;

use AppBundle\Entity\Task;
use Doctrine\ORM\EntityManagerInterface;

class TaskManager
{
    protected EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;

    }

    public function createTask(Task $task)
    {
        $this->entityManager->persist($task);
        $this->entityManager->flush();
    }

    public function updateTask()
    {
        $this->entityManager->flush();
    }

}
