<?php

namespace App\Fixtures;

use App\AppBundle\Entity\Task;
use App\AppBundle\Entity\User;
use Doctrine\Bundle\DoctrineBundle\DoctrineBundle;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class DataFixtures extends Fixture
{

    private UserPasswordEncoderInterface $passwordEncoder;

    private EntityManagerInterface $em;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder, EntityManagerInterface $em)
    {
        $this->passwordEncoder = $passwordEncoder;
        $this->em = $em;
    }

    /**
     * @inheritDoc
     * @throws Exception
     */
    public function load(ObjectManager $manager)
    {
        // Creation d'un utilisateur "ADMIN" et Bam!.
        $userFixtures = new User();
        $userFixtures->setUsername('admin');
        $userFixtures->setEmail('admin@gmail.com');
        $userFixtures->setPassword($this->passwordEncoder->encodePassword($userFixtures, '123456'));
        $userFixtures->setRoles(["ROLE_ADMIN"]);
        $manager->persist($userFixtures);

        // Création de 50 utilisateurs! et Bim!
        for ($i = 0; $i < 50; $i++) {
            $userFixtures = new User();
            $userFixtures->setUsername('user ' . $i);
            $userFixtures->setEmail('user' . $i . '@gmail.com');
            $userFixtures->setPassword($this->passwordEncoder->encodePassword($userFixtures, '123456'));
            $userFixtures->setRoles(["ROLE_USER"]);
            $manager->persist($userFixtures);
        }


        $manager->flush();

        // Création de 30 tâches
        $userId = $this->em->getReference(User::class, 1);
        for ($i = 0; $i < 30; $i++) {
            $userFixtures = new Task();
            $userFixtures->setTitle('tache' . $i);
            $userFixtures->setContent('je suis le contenu de la tache' . $i);
            $userFixtures->setUser($userId);
            $userFixtures->setCreatedAt(new \DateTime('now'));
            $manager->persist($userFixtures);

            $manager->flush();

        }

    }
}
