<?php

namespace App\DataFixtures;

use App\Entity\Task;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserTestFixtures extends Fixture
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
     *
     * @param ObjectManager $manager
     *
     * @return void
     */
    public function load(ObjectManager $manager)
    {
        for ($i = 1; $i <= 2; ++$i) {
            $user = new User();
            $user->setEmail('user' . $i . '@email.com');
            $user->setUsername('user' . $i);
            $user->setPassword($this->encoder->hashPassword($user, 'password'));
            $manager->persist($user);
            $this->addReference('user' . $i, $user);
        }

        $admin = new User();
        $admin->setEmail('admin1@email.com');
        $admin->setUsername('admin1');
        $admin->setPassword($this->encoder->hashPassword($admin, 'password'));
        $admin->setRoles(['ROLE_ADMIN']);

        $manager->persist($admin);
        $this->addReference('admin' . $i, $admin);

        $manager->flush();
    }
}
