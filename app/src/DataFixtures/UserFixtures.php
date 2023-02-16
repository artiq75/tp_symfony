<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    private Generator $faker;

    public function __construct(
        private UserPasswordHasherInterface $hasher
    )
    {
        $this->faker = Factory::create('fr_FR');
    }

    public function load(ObjectManager $manager): void
    {
        for ($i=0; $i < 10; $i++) { 
            $user = new User();
            $user
            ->setFirstname($this->faker->firstName())
            ->setLastname($this->faker->lastName())
            ->setEmail($this->faker->email())
            ->setAddress($this->faker->address())
            ->setRoles($this->faker->boolean(10) ? ['ROLE_ADMIN'] : [])
            ->setPassword($this->hasher->hashPassword(
                $user,
                'password'
            ));

            $manager->persist($user);
        }

        $manager->flush();
    }
}
