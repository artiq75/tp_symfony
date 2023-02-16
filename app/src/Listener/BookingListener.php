<?php

namespace App\Listener;

use App\Entity\Alert;
use App\Entity\Invoice;
use App\Entity\InvoiceLine;
use App\Entity\Tax;
use App\Event\BookingBookEvent;
use App\Repository\TaxRepository;
use Symfony\Bridge\Doctrine\ManagerRegistry;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

#[AsEventListener(event: BookingBookEvent::NAME)]
final class BookingListener
{
    public function __construct(
        private TaxRepository $taxRepository,
        private ManagerRegistry $doctrine
    )
    {
    }

    public function __invoke(BookingBookEvent $event)
    {
        $manager = $this->doctrine->getManager();

        $booking = $event->getBooking();
        
        $invoice = new Invoice();
        $invoice
          ->setCompanyName("Espadrille Volante")
          ->setCompanyAddress("5 rue de l'espadrille, Perpignan, 66000")
          ->setCustomerName($booking->getCustomerFullName())
          ->setCustomerAddress($booking->getCustomerAddress())
          ->setTva(20)
          ->setUuid(uniqid());
        
        $stayTax = $this->taxRepository->findOneBy([
          'slug' => Tax::TAX_STAY_SLUG
        ]);
        
        $stayChildTax = $stayTax->getChildRate() * $booking->getChildren();
        $stayAdultTax = $stayTax->getAdultRate() * $booking->getAdults();
        $price = $booking->getProperty()->getType()->getPrice();
        $total = $price + $stayAdultTax + $stayChildTax;
  
        $invoiceLine = new InvoiceLine();
        $invoiceLine
          ->setAdults($booking->getAdults())
          ->setChildren($booking->getChildren())
          ->setDesignation($booking->getProperty()->getType()->getLabel())
          ->setUnitPrice($total)
          ->setInvoice($invoice);
  
        $now = new \DateTime();
  
        $alert = new Alert();
        $alert
          ->setTitle('Vous devez imprimer la facture numéro: ' . $invoice->getUuid())
          ->setPublishedAt($now->add(new \DateInterval('P3Y')));
        $manager->persist($alert);
  
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
  
        $manager->persist($invoice);
        $manager->flush();
    }
}