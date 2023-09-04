<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    /**
     * @var UserPasswordHasherInterface
     */
    private UserPasswordHasherInterface $encoder;

    public function __construct(UserPasswordHasherInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    /**
     * @param ObjectManager $manager
     *
     * @return void
     */
    public function load(ObjectManager $manager)
    {
        for ($i = 1; $i <= 10; ++$i) {
            $user = new User();
            $user->setEmail('user'.$i.'@email.com');
            $user->setUsername('user'.$i);
            $user->setPassword($this->encoder->hashPassword($user, 'password'));

            $manager->persist($user);
        }

        $admin = new User();
        $admin->setEmail('admin1@email.com');
        $admin->setUsername('admin1');
        $admin->setPassword($this->encoder->hashPassword($user, 'password'));
        $admin->setRoles(['ROLE_ADMIN']);

        $manager->persist($admin);

        $manager->flush();
    }
}
