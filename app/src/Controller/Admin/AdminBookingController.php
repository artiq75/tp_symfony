<?php

namespace App\Controller\Admin;

use App\Entity\Booking;
use App\Repository\BookingRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('admin')]
class AdminBookingController extends AbstractController
{
  public function __construct(
    private BookingRepository $repository
  )
  {
  }

  #[Route('/reservations', name: 'admin.booking.index', methods: ['GET'])]
  public function index(): Response
  {
    return $this->render('pages/admin/booking/index.html.twig', [
      'bookings' => $this->repository->findAll()
    ]);
  }

  #[Route('/reservation/{id}', name: 'admin.booking.delete', methods: ['POST'])]
  public function delete(Booking $booking, Request $request): Response
  {
    if ($this->isCsrfTokenValid('delete' . $booking->getId(), $request->request->get('_token'))) {
      $this->repository->remove($booking, true);
      $this->addFlash('success', 'Suppression de la réservation avec succès');
    }

    return $this->redirectToRoute('admin.booking.index');
  }
}