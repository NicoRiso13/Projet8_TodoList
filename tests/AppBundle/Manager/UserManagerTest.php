<?php

namespace Tests\AppBundle\Manager;

use App\AppBundle\Entity\User;
use App\AppBundle\Manager\UserManager;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserManagerTest extends TestCase
{

    public function testCreateUser()
    {
        $user = $this->createMock(User::class);
        $entityManager = $this->createMock(EntityManagerInterface::class);
        $entityManager->expects(self::once())->method('persist')->with($user);
        $entityManager->expects(self::once())->method('flush');
        $passwordEncoder = $this->createMock(UserPasswordEncoderInterface::class);
        $passwordEncoder->expects(self::once())->method('encodePassword')->with($user,'toto')->willReturn('toto_encoded');
        $user->expects(self::once())->method('getPassword')->willReturn('toto');
        $user->expects(self::once())->method('setPassword')->with('toto_encoded');
        $userManager = new UserManager($entityManager, $passwordEncoder);
        $userManager->createUser($user);
    }

    public function testUpdateUser()
    {
        $user = $this->createMock(User::class);
        $entityManager = $this->createMock(EntityManagerInterface::class);
        $entityManager->expects(self::once())->method('flush');
        $passwordEncoder = $this->createMock(UserPasswordEncoderInterface::class);
        $passwordEncoder->expects(self::once())->method('encodePassword')->with($user,'toto')->willReturn('toto_encoded');
        $user->expects(self::once())->method('getPassword')->willReturn('toto');
        $user->expects(self::once())->method('setPassword')->with('toto_encoded');
        $userManager = new UserManager($entityManager, $passwordEncoder);
        $userManager->updateUser($user);
    }

}
