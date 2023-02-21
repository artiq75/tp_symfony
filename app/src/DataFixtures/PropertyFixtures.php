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
        $types = $this->typeRepository->findAll();
        $owners = $this->userRepository->findAll();
        $availabilityStart = new \DateTimeImmutable();

        for ($i = 0; $i < 100; $i++) {
            $availabilityEnd = $availabilityStart->modify('+' . $this->faker->numberBetween(7, 20) . ' days');

            $property = new Property();
            $property
                ->setAvailabilityStart($availabilityStart)
                ->setAvailabilityEnd($availabilityEnd)
                ->setImageName("https://picsum.photos/300/315")
                ->setType($this->faker->randomElement($types))
                ->setOwner($this->faker->randomElement($owners));

            $manager->persist($property);
        }

        $manager->flush();
    }
}