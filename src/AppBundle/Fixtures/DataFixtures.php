<?php

namespace App\AppBundle\Fixtures;

use App\AppBundle\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class DataFixtures extends Fixture
{
    private UserPasswordEncoderInterface $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * @inheritDoc
     */
    public function load(ObjectManager $manager)
    {
        // Création de 200 utilisateurs! et Bim!
        for ($i = 0; $i < 200; $i++) {
            $userFixtures = new User();
            $userFixtures->setUsername('user '.$i);
            $userFixtures->setEmail('user'.$i.'@gmail.com');
            $userFixtures->setPassword($this->passwordEncoder->encodePassword($userFixtures, '123456'));
            $userFixtures->setRoles(["ROLE_USER"]);
            $manager->persist($userFixtures);
        }
        // Creation d'un utilisateur "ADMIN" et Bam!.
            $userFixtures = new User();
            $userFixtures->setUsername('admin');
            $userFixtures->setEmail('admin@gmail.com');
            $userFixtures->setPassword($this->passwordEncoder->encodePassword($userFixtures, '123456'));
            $userFixtures->setRoles(["ROLE_ADMIN"]);
            $manager->persist($userFixtures);


        $manager->flush();
    }

}
