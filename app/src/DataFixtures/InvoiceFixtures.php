<?php

namespace App\DataFixtures;

use App\Controller\BookingController;
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
        for ($i=0; $i < 100; $i++) { 
            $invoice = new Invoice();

            /**
             * @var Booking
             */
            $booking = $this->faker->randomElement($this->bookingRepository->findAll());

            $persons = $booking->getAdults() + $booking->getChildren();
            $priceTTC = $booking->getProperty()->getType()->getPrice() * $persons;
            $priceHT = $priceTTC * 100 / (100 + BookingController::TVA);
            
            $invoice
              ->setCompanyName("Espadrille Volante")
              ->setCompanyAddress("5 rue de l'espadrille, Perpignan, 66000")
              ->setCompanySiret('83410482000016')
              ->setCompanyTva('FR40834104820')
              ->setCustomerFirstname($booking->getCustomerFirstname())
              ->setCustomerLastname($booking->getCustomerLastname())
              ->setCustomerAddress($booking->getCustomerAddress())
              ->setPriceTtc($priceTTC)
              ->setPriceHt($priceHT)
              ->setTotal($priceHT);

              $manager->persist($invoice);
        }

        $manager->flush();
    }
}
