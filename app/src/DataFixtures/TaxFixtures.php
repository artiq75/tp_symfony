<?php

namespace App\DataFixtures;

use App\Entity\Tax;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class TaxFixtures extends Fixture
{
    private const TAXES = [
        [
            'label' => 'Taxe de séjour',
            'slug' => 'taxe-sejour',
            'childRate' => 35,
            'adultRate' => 60
        ],
        [
            'label' => 'Taxe piscine',
            'slug' => 'taxe-piscine',
            'childRate' => 1,
            'adultRate' => 150
        ]
    ];

    public function load(ObjectManager $manager): void
    {
        foreach (self::TAXES as $data) {
            $tax = new Tax();
            $tax
                ->setLabel($data['label'])
                ->setSlug($data['slug'])
                ->setChildRate($data['childRate'])
                ->setAdultRate($data['adultRate']);

            $manager->persist($tax);
        }

        $manager->flush();
    }
}