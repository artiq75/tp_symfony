<?php

namespace App\Event;

use App\Entity\Booking;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Contracts\EventDispatcher\Event;

class BookingBookEvent extends Event
{
    public const NAME = 'booking.book';

    public function __construct(
        protected Booking $booking
    )
    {
    }

    public function getBooking(): Booking
    {
        return $this->booking;
    }
}