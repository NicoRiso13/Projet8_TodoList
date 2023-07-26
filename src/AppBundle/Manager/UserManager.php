<?php

namespace AppBundle\Manager;

use AppBundle\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserManager
{
    protected EntityManagerInterface $entityManager;
    protected UserPasswordEncoderInterface $passwordEncoder;

    public function __construct(EntityManagerInterface $entityManager, UserPasswordEncoderInterface $passwordEncoder)
    {

        $this->entityManager = $entityManager;
        $this->passwordEncoder = $passwordEncoder;
    }

    public function createUser(User $user)
    {
        $password = $this->passwordEncoder->encodePassword($user, $user->getPassword());
        $user->setPassword($password);
        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }

    public function updateUser(User $user)
    {
        $password = $this->passwordEncoder->encodePassword($user, $user->getPassword());
        $user->setPassword($password);
        $this->entityManager->flush();
    }

}
