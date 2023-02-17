<?php

namespace App\Listener;

use App\Entity\Alert;
use App\Event\BookingBookEvent;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

#[AsEventListener(event: BookingBookEvent::NAME)]
final class BookingListener
{
  public function __invoke(BookingBookEvent $event)
  {
    $manager = $event->doctrine->getManager();
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
    $manager->persist($alert);

    $manager->flush();
  }
}