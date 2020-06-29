<?php

namespace App\Tests\Repository;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class UserRepositoryTest extends KernelTestCase {

    /**
     * Test qui vérifie le nombre d'utilisateurs créé via les fixtures.
     *
     * @return void
     */
    public function testCount() {
        self::bootKernel();
        $users = self::$container->get(UserRepository::class)->count([]);
        $this->assertEquals(10, $users);
    }
}