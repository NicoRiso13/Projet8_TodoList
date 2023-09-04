<?php

namespace App\Tests\Entity;

use App\Entity\Task;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class TaskTest extends KernelTestCase
{
    use FixturesTrait;

    public function testValidTaskEntity()
    {
        $task = new Task();
        $task->setTitle('valid title');
        $task->setContent('valid content');
        self::assertCount(1, [$task]);
    }

    public function testInvalidBlankTaskEntity()
    {
        $invalidTask = new Task();
        $invalidTask->setTitle('');
        $invalidTask->setContent('');
        self::assertCount(1, [$invalidTask]);
    }

    public function testToggle()
    {
        $task = new Task();
        $task->toggle(!$task->isDone());
        $this->assertTrue($task->isDone());
    }
}
