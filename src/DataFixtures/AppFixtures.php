<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;
use Faker\Generator;
use App\Entity\Option;
use App\Entity\Product;
use App\Entity\Attribute;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private const USER_PASSWORD = 'password';
    private const SIZE_ATTRIBUTES = [
        'S', 'M', 'L', 'XL'
    ];
    private const COLOR_ATTRIBUTES = [
        'red', 'white', 'black', 'grey'
    ];

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

        $product = (new Product())
            ->setName('BESTÅ')
            ->setDescription('
                Banc TV avec portes, brun noir/Lappviken/Stubbarp brun noir120x42x74 cm
            ')
            ->setStock(23675)
            ->setPrice(79.99)
            ->setBrand('IKEA')
            ->setType(Product::PARENT_TYPE)
            ->setEntityId(random_int(100, 999))
        ;

        $option = (new Option())
            ->setName('Size');

        foreach (self::SIZE_ATTRIBUTES as $size) {
            $attribute = (new Attribute())
                ->setLabel($size)
                ->setPrice($this->faker->randomFloat(2, 5, 15));
            $option->addAttribute($attribute);
            $manager->persist($attribute);
        }

        $manager->persist($option);
        $product->addOption($option);

        $option = (new Option())
            ->setName('Color');

        foreach (self::COLOR_ATTRIBUTES as $color) {
            $attribute = (new Attribute())
                ->setLabel($color)
                ->setPrice($this->faker->randomFloat(2, 5, 15));
            $option->addAttribute($attribute);
            $manager->persist($attribute);
        }

        $manager->persist($option);
        $product->addOption($option);

        $manager->persist($product);

        foreach ($product->getOptions() as $option) {
            foreach ($option->getAttributes() as $attribute) {
                $variant = (new Product())
                    ->setName($product->getName() . '-' . $option->getName() . '-' . $attribute->getLabel())
                    ->setDescription($product->getDescription())
                    ->setStock($product->getStock())
                    ->setPrice($product->getPrice() + $attribute->getPrice())
                    ->setBrand($product->getBrand())
                    ->setType(Product::CHILD_TYPE)
                    ->setEntityId(random_int(100, 999))
                    ->setParentId($product->getEntityId())
                ;

                $manager->persist($variant);
            }
        }

        $manager->flush();
    }
}
