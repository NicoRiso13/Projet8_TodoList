<?php

namespace App\Manager;

use App\Entity\User;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;



class UserManager
{
    protected EntityManagerInterface $entityManager;
    protected UserPasswordHasherInterface $passwordEncoder;

    public function __construct(EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordEncoder)
    {

        $this->entityManager = $entityManager;
        $this->passwordEncoder = $passwordEncoder;
    }

    public function createUser(User $user)
    {
        $password = $this->passwordEncoder->hashPassword($user, $user->getPassword());
        $user->setPassword($password);
        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }

    public function updateUser(User $user)
    {
        $password = $this->passwordEncoder->hashPassword($user, $user->getPassword());
        $user->setPassword($password);
        $this->entityManager->flush();
    }

}
