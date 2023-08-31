<?php

namespace App\DataFixtures;

use App\Entity\Task;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class TaskTestFixtures extends Fixture implements DependentFixtureInterface
{
    /**
     * charger les fixtures des tâches pour les tests.
     *
     * @param ObjectManager $manager
     *
     * @return void
     */
    public function load(ObjectManager $manager): void
    {
        for ($i = 1; $i <= 5; ++$i) {
            $task = new Task();
            $task->setTitle('title'.$i);
            $task->setContent('content'.$i);
            if ($i == 3) {
                $task->setIsDone(true);
            }
            if ($i == 4) {
                $task->setAuthor($this->getReference('user1'));
            }
            if ($i == 5) {
                $task->setAuthor($this->getReference('user2'));
            }
            $manager->persist($task);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            UserTestFixtures::class,
        ];
    }
}
