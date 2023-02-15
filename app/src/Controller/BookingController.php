<?php

namespace App\Controller;

use App\Entity\Booking;
use App\Entity\Invoice;
use App\Entity\Property;
use App\Form\BookingType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BookingController extends AbstractController
{
  private const TVA = 20;

  #[Route('/bien/{id}/reservation', name: 'booking.book')]
  public function book(Property $property, Request $request, ManagerRegistry $doctrine): Response
  {
    $em = $doctrine->getManager();

    $booking = new Booking();
    $form = $this->createForm(BookingType::class, $booking, [
      'action' => $this->generateUrl('booking.book', [
        'id' => $property->getId()
      ])
    ]);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $invoice = new Invoice();

      $persons = $booking->getAdults() + $booking->getChildren();
      $priceTTC = $property->getType()->getPrice() * $persons;
      $priceHT = $priceTTC * 100 / (100 + self::TVA);
      
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

      $booking->setProperty($property);

      $em->persist($booking);
      $em->persist($invoice);
      $em->flush();

      $this->addFlash('success', 'Réservation prise en compte!');

      return $this->redirectToRoute('property.show', [
        'id' => $property->getId()
      ]);
    }

    return $this->render('booking/_form.html.twig', [
      'form' => $form
    ]);
  }
}