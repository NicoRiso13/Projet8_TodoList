<?php

namespace Tests\AppBundle\Manager;

use AppBundle\Entity\User;
use PHPUnit\Framework\TestCase;

class UserManagerTest extends TestCase
{

    public function testCreateUser()
    {
        $user = new User();
        $this->assertSame(User::class,$user);
    }

}
