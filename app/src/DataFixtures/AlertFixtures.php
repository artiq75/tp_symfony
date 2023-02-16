<?php

namespace App\DataFixtures;

use App\Entity\Alert;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;

class AlertFixtures extends Fixture
{
    private Generator $faker;

    public function __construct()
    {
        $this->faker = Factory::create('fr_FR');
    }

    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < 100; $i++) {
            $alert = new Alert();

            $alert
            ->setTitle($this->faker->sentence())
            ->setPublishedAt(new \DateTime());

            $manager->persist($alert);
        }

        $manager->flush();
    }
}