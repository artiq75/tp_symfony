<?php

namespace App\DataFixtures;

use App\Entity\Property;
use App\Repository\PropertyTypeRepository;
use App\Repository\UserRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;

class PropertyFixtures extends Fixture implements DependentFixtureInterface
{
    private Generator $faker;

    public function __construct(
        private PropertyTypeRepository $typeRepository,
        private UserRepository $userRepository,
    )
    {
        $this->faker = Factory::create('fr_FR');
    }

    public function getDependencies(): array
    {
        return [
            TypeFixtures::class,
            UserFixtures::class
        ];
    }

    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < 100; $i++) {
            $datetime = new \DateTime();
            $dateTimeModify = $datetime->modify('+' . $this->faker->numberBetween(7, 20) . ' day');

            $property = new Property();
            $property
                ->setAvailabilityStart($datetime)
                ->setAvailabilityEnd($dateTimeModify)
                ->setImageName("https://picsum.photos/300/315")
                ->setType($this->faker->randomElement($this->typeRepository->findAll()))
                ->setOwner($this->faker->randomElement($this->userRepository->findAll()));

            $manager->persist($property);
        }

        $manager->flush();
    }
}