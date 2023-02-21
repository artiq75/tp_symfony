<?php

namespace App\DataFixtures;

use App\Entity\Booking;
use App\Repository\PropertyRepository;
use App\Repository\UserRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;

class BookingFixtures extends Fixture implements DependentFixtureInterface
{
    private Generator $faker;

    public function __construct(
        private UserRepository $userRepository,
        private PropertyRepository $propertyRepository
    )
    {
        $this->faker = Factory::create('fr_FR');
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
            PropertyFixtures::class
        ];
    }

    public function load(ObjectManager $manager): void
    {
        $properties = $this->propertyRepository->findAll();

        for ($i = 0; $i < 50; $i++) {
            /**
             * @var \App\Entity\Property
             */
            $property = $this->faker->randomElement($properties);
            $availabilityStart = $property->getAvailabilityStart();
            $max = $availabilityStart->diff($property->getAvailabilityEnd())->d;
            $min = $max / 2;
            $days = $this->faker->numberBetween($min, $max);
            $availabilityEnd = clone $availabilityStart;
            $availabilityEnd = $availabilityEnd->modify('+' . $days . ' days');

            $booking = new Booking();
            $booking
                ->setCustomerFirstname($this->faker->firstName())
                ->setCustomerLastname($this->faker->lastName())
                ->setCustomerAddress($this->faker->address())
                ->setAdults($this->faker->numberBetween(2, 8))
                ->setChildren($this->faker->numberBetween(2, 5))
                ->setStartDate($availabilityStart)
                ->setEndDate($availabilityEnd)
                ->setGrantAccess($this->faker->boolean())
                ->setPoolAcess($this->faker->boolean())
                ->setProperty($property);

            $manager->persist($booking);
        }

        $manager->flush();
    }
}