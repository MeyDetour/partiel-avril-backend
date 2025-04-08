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
        $user->setPassword("$2y$13\$B2k6PqnrRZxY1wKj0FWCVOWAKnNEq/LhM8c/DLLrdS3EbdP84OABO");
        $user->setEmail("mey@mey.com");
        $user->setIsVerified(true);
        $user->setRoles(["ROLE_ADMIN"]);
        $manager->persist($user);
        $manager->flush();
    }
}
