<?php

namespace App\Listener;

use App\Entity\Alert;
use App\Event\InvoiceCreateEvent;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

#[AsEventListener(event: InvoiceCreateEvent::NAME)]
final class InvoiceListener
{
  public function __invoke(InvoiceCreateEvent $event)
  {
    $manager = $event->doctrine->getManager();

    $invoice = $event->getInvoice();
        
    $now = new \DateTime();

    $alert = new Alert();
    $alert
      ->setTitle('Vous devez imprimer la facture numéro: ' . $invoice->getUuid())
      ->setPublishedAt($now->add(new \DateInterval('P3Y')));

    $manager->persist($alert);
    $manager->flush();
  }
}