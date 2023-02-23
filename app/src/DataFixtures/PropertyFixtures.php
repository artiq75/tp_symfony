<?php

namespace App\DataFixtures;

use App\Entity\Property;
use App\Entity\PropertyType;
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

        $alredyExists = [];

        for ($i = 0; $i < 10; $i++) {
            foreach (TypeFixtures::TYPES as $type) {
                $days = $this->faker->numberBetween(7, 20);
                $availabilityEnd = $availabilityStart->modify('+' . $days . ' days');

                $property = new Property();

                $property
                    ->setAvailabilityStart($availabilityStart)
                    ->setAvailabilityEnd($availabilityEnd)
                    ->setImageName("https://picsum.photos/300/315")
                    ->setAdultRate($type['adultRate'])
                    ->setChildRate($type['childRate'])
                    ->setType($this->getReference($type['label']))
                    ->setOwner($this->faker->randomElement($owners));

                    $isExist = array_key_exists($type['label'], $alredyExists);

                    if (!$isExist) {
                        $manager->persist($property);
                    }
                    
                    if ($property->getChildRate() && !$isExist) {
                        $alredyExists[$type['label']] = $property;
                    }
            }
        }

        $manager->flush();
    }
}