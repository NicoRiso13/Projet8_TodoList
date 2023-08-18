<?php

namespace Tests\AppBundle\Manager;

use App\AppBundle\Entity\Task;
use App\AppBundle\Entity\User;
use App\AppBundle\Manager\TaskManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use PHPUnit\Framework\TestCase;


class TaskManagerTest extends TestCase
{

    public function testCreateTask()
    {
        $user = $this->createMock(User::class);
        $task = $this->createMock(Task::class);
        $task->expects(self::once())->method('setUser')->with($user);
        $entityManager = $this->createMock(EntityManagerInterface::class);
        $entityManager->expects(self::once())->method('persist')->with($task);
        $entityManager->expects(self::once())->method('flush');
        $taskManager = new TaskManager($entityManager);
        $taskManager->createTask($task,$user);
    }
    public function testUpdateTask()
    {
        $entityManager = $this->createMock(EntityManagerInterface::class);
        $entityManager->expects(self::once())->method('flush');
        $taskManager = new TaskManager($entityManager);
        $taskManager->updateTask();
    }

    public function testToggleTask()
    {
        $task = $this->createMock(Task::class);
        $entityManager = $this->createMock(EntityManagerInterface::class);
        $entityManager->expects(self::once())->method('flush');
        $task->expects(self::once())->method('toggle');
        $task->expects(self::once())->method('isDone');
        $taskManager = new TaskManager($entityManager);
        $taskManager->toggleTask($task);
    }

    public function testDeleteTask()
    {
        $task = $this->createMock(Task::class);
        $entityManager = $this->createMock(EntityManagerInterface::class);
        $entityManager->expects(self::once())->method('remove')->with($task);
        $entityManager->expects(self::once())->method('flush');
        $taskManager = new TaskManager($entityManager);
        $taskManager->deleteTask($task);
    }


}
