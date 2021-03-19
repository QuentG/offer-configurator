<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private const USER_PASSWORD = 'password';

    private Generator $faker;

    public function __construct(
        private UserPasswordEncoderInterface $userPasswordEncoder
    )
    {
        $this->faker = Factory::create('fr_FR');
    }

    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $user->setEmail('admin@gmail.com')
            ->setPassword($this->userPasswordEncoder->encodePassword($user, self::USER_PASSWORD));

        $manager->persist($user);

        $manager->flush();
    }
}
