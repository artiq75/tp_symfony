<?php

namespace App\EventSubscriber;

use App\Entity\Alert;
use App\Event\BookingBookEvent;
use App\Repository\AlertRepository;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class BookingSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private AlertRepository $alertRepository
    )
    {
    }

    public function onBookingBook(BookingBookEvent $event): void
    {
        $booking = $event->getBooking();
    
        $now = new \DateTime();
    
        $alert = new Alert();
        $publishedAt = null;
    
        if (!$booking->isGrantAccess()) {
          $publishedAt = $now->add(new \DateInterval('P7D'));
        } else {
          $publishedAt = $now->add(new \DateInterval('P1Y'));
        }
    
        $alert
          ->setTitle('Vous devez supprimer les informations du locataire: ' . $booking->getCustomerFullName())
          ->setPublishedAt($publishedAt);
    
        $this->alertRepository->save($alert, true);
    }

    public static function getSubscribedEvents(): array
    {
        return [
            BookingBookEvent::NAME => 'onBookingBook',
        ];
    }
}
