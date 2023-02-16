<?php

namespace App\Controller;

use App\Entity\Alert;
use App\Entity\Booking;
use App\Entity\Invoice;
use App\Entity\InvoiceLine;
use App\Entity\Property;
use App\Entity\Tax;
use App\Event\BookingBookEvent;
use App\Form\BookingType;
use App\Repository\TaxRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class BookingController extends AbstractController
{
  public function __construct(
    private EventDispatcherInterface $dispatcher
  )
  {
  }

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
      $booking->setProperty($property);

      $this->dispatcher->dispatch(new BookingBookEvent($booking), BookingBookEvent::NAME);
      
      $em->persist($booking);
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