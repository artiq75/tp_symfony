<?php

namespace App\DataFixtures;

use App\Entity\Booking;
use App\Entity\Invoice;
use App\Repository\BookingRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;

class InvoiceFixtures extends Fixture implements DependentFixtureInterface
{
    private Generator $faker;

    public function __construct(
        private BookingRepository $bookingRepository
    )
    {
        $this->faker = Factory::create('fr_FR');
    }

    public function getDependencies(): array
    {
        return [
            BookingFixtures::class
        ];
    }

    public function load(ObjectManager $manager): void
    {
        $bookings = $this->bookingRepository->findAll();
        
        for ($i = 0; $i < 20; $i++) {
            $invoice = new Invoice();

            /**
             * @var Booking
             */
            $booking = $this->faker->randomElement($bookings);

            $invoice
                ->setCompanyName("Espadrille Volante")
                ->setCompanyAddress("5 rue de l'espadrille, Perpignan, 66000")
                ->setCustomerName($booking->getCustomerFullName())
                ->setCustomerAddress($booking->getCustomerAddress())
                ->setIsActive($this->faker->boolean())
                ->setCreatedAt((new \DateTimeImmutable())->modify('-2 years'));

            $manager->persist($invoice);
        }

        $manager->flush();
    }
}