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

    public function load(ObjectManager $manager): void
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