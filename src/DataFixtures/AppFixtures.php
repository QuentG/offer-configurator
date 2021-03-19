<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Generator;

class AppFixtures extends Fixture
{
    public function __construct(
        private Generator $faker
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        $manager->flush();
    }
}
