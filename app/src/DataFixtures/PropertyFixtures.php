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
    private const TYPES = [
        [
            'label' => 'M-H 3 personnes',
            'price' => 20 * 100
        ],
        [
            'label' => 'M-H 4 personnes',
            'price' => 24 * 100
        ],
        [
            'label' => 'M-H 5 personnes',
            'price' => 27 * 100
        ],
        [
            'label' => 'M-H 6-8 personnes',
            'price' => 34 * 100
        ],
        [
            'label' => 'Caravane 2 places',
            'price' => 15 * 100
        ],
        [
            'label' => 'Caravane 4 places',
            'price' => 18 * 100
        ],
        [
            'label' => 'Caravane 6 places',
            'price' => 24 * 100
        ],
        [
            'label' => 'Emplacement 8m²',
            'price' => 12 * 100
        ],
        [
            'label' => 'Emplacement 12m²',
            'price' => 14 * 100
        ],
    ];

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
            UserFixtures::class
        ];
    }

    public function load(ObjectManager $manager): void
    {
        $this->generateType($manager);

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

    private function generateType(ObjectManager $manager)
    {
        foreach (self::TYPES as $type) {
            $propertyType = new PropertyType();
            $propertyType
                ->setLabel($type['label'])
                ->setPrice($type['price']);
            $manager->persist($propertyType);
        }

        $manager->flush();
    }
}