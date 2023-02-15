<?php

namespace App\Controller;

use App\Entity\Booking;
use App\Entity\Property;
use App\Form\BookingType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BookingController extends AbstractController
{
  #[Route('/bien/{id}/reservation', name: 'booking.book')]
  public function book(Property $property, Request $request): Response
  {
    $booking  = new Booking();
    $form = $this->createForm(BookingType::class, $booking);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {

    }

    return $this->render('booking/_form.html.twig', [
      'form' => $form
    ]);
  }
}