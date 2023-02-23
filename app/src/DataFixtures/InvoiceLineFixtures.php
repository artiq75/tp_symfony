<?php

namespace App\DataFixtures;

use App\Entity\InvoiceLine;
use App\Entity\PropertyType;
use App\Repository\InvoiceRepository;
use App\Repository\PropertyRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;

class InvoiceLineFixtures extends Fixture implements DependentFixtureInterface
{
    private Generator $faker;

    public function __construct(
        private InvoiceRepository $invoiceRepository,
        private PropertyRepository $propertyRepository
    )
    {
        $this->faker = Factory::create('fr_FR');
    }

    public function getDependencies(): array
    {
        return [
            InvoiceFixtures::class,
            PropertyFixtures::class
        ];
    }

    public function load(ObjectManager $manager): void
    {
        $properties = $this->propertyRepository->findAll();

        /**
         * @var \App\Entity\Property
         */
        $stayProperty = $this->propertyRepository->findOneBy([
            'type' => PropertyType::STAY_TYPE
        ]);

        /**
         * @var \App\Entity\Property
         */
        $poolProperty = $this->propertyRepository->findOneBy([
            'type' => PropertyType::POOL_TYPE
        ]);

        foreach ($this->invoiceRepository->findAll() as $invoice) {
            $days = $invoice->getEndDate()->diff($invoice->getStartDate())->d;

            /**
             * @var \App\Entity\Property
             */
            $property = $this->faker->randomElement($properties);

            $price = $property->getAdultRate();

            $invoiceLine = new InvoiceLine();
            $invoiceLine
                ->setUnitPrice($price)
                ->setType($property->getId())
                ->setDesignation($property->getType()->getLabel())
                ->setFillOrder(1)
                ->setDays($days)
                ->setInvoice($invoice);
            $manager->persist($invoiceLine);

            $adultRate = $stayProperty->getAdultRate() * $invoice->getAdults();
            $chidlrenRate = $stayProperty->getChildRate() * $invoice->getChildren();
            $price = $adultRate + $chidlrenRate;

            $invoiceLine = new InvoiceLine();
            $invoiceLine
                ->setUnitPrice($price)
                ->setType($stayProperty->getId())
                ->setDesignation($stayProperty->getType()->getLabel())
                ->setFillOrder(2)
                ->setDays($days)
                ->setInvoice($invoice);
            $manager->persist($invoiceLine);

            $adultRate = $poolProperty->getAdultRate() * $invoice->getAdults();
            $chidlrenRate = $poolProperty->getChildRate() * $invoice->getChildren();
            $price = $adultRate + $chidlrenRate;

            $invoiceLine = new InvoiceLine();
            $invoiceLine
                ->setUnitPrice($price)
                ->setType($poolProperty->getId())
                ->setDesignation($poolProperty->getType()->getLabel())
                ->setFillOrder(3)
                ->setDays($days)
                ->setInvoice($invoice);
            $manager->persist($invoiceLine);
        }

        $manager->flush();
    }
}