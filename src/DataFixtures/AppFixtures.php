<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {

        $user = new User();
        $user->setPassword("$2y$13$4QC1I7Ur6GWjybfMt1HX/uMxUXmaLkYuUz5Ih5yJZZNVJKyjDtJsa");
        $user->setEmail("mey@gmail.com");
        $user->setIsVerified(true);
        $user->setRoles(["ROLE_ADMIN"]);
        $manager->persist($user);
        $manager->flush();
    }
}
