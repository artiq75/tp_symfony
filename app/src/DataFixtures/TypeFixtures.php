<?php

namespace App\DataFixtures;

use App\Entity\PropertyType;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class TypeFixtures extends Fixture
{
    private const TYPES = [
        [
            'label' => 'M-H 3 personnes',
            'adultRate' => 20 * 100,
            'childRate' => 0,
        ],
        [
            'label' => 'M-H 4 personnes',
            'adultRate' => 24 * 100,
            'childRate' => 0,
        ],
        [
            'label' => 'M-H 5 personnes',
            'adultRate' => 27 * 100,
            'childRate' => 0,
        ],
        [
            'label' => 'M-H 6-8 personnes',
            'adultRate' => 34 * 100,
            'childRate' => 0,
        ],
        [
            'label' => 'Caravane 2 places',
            'adultRate' => 15 * 100,
            'childRate' => 0,
        ],
        [
            'label' => 'Caravane 4 places',
            'adultRate' => 18 * 100,
            'childRate' => 0,
        ],
        [
            'label' => 'Caravane 6 places',
            'adultRate' => 24 * 100,
            'childRate' => 0,
        ],
        [
            'label' => 'Emplacement 8m²',
            'adultRate' => 12 * 100,
            'childRate' => 0,
        ],
        [
            'label' => 'Emplacement 12m²',
            'adultRate' => 14 * 100,
            'childRate' => 0,
        ],
        [
            'label' => 'Taxe de séjour',
            'childRate' => 1 * 35,
            'adultRate' => 1 * 60
        ],
        [
            'label' => 'Taxe piscine',
            'childRate' => 1 * 100,
            'adultRate' => 1 * 150
        ]
    ];

    public function load(ObjectManager $manager): void
    {
        foreach (self::TYPES as $type) {
            $propertyType = new PropertyType();
            $propertyType
                ->setLabel($type['label'])
                ->setAdultRate($type['adultRate'])
                ->setChildRate($type['childRate']);

            $manager->persist($propertyType);
        }

        $manager->flush();
    }
}