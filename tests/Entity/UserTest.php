<?php

namespace App\Tests\Entity;

use App\Entity\Task;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class UserTest extends KernelTestCase
{
    public function getEntity(): User
    {
        $user = new User();
        $user->setEmail('valid@email.com');
        $user->setUsername('ValidUsername');
        $user->setPassword('password');

        return $user;
    }

    public function testInvalidEntity()
    {
        $invalidUser = $this->getEntity();
        $invalidUser->setEmail('invalidUser.com');
        $invalidUser->setUsername('');
        self::assertCount(1, [$invalidUser]);
    }

    public function testAddAndRemoveTask()
    {
        $user = new User();
        for ($i = 1; $i <= 10; ++$i) {
            $task = new Task();
            $task->setTitle('task'.$i);
            $task->setContent('content'.$i);
            $task->setAuthor($user);
            $user->addTask($task);
        }
        $tasks = $user->getTasks();
        $this->assertSame(10, \count($tasks));

        $user->removeTask($tasks[0]);
        $user->removeTask($tasks[1]);
        $this->assertSame(8, \count($tasks));
    }
}
