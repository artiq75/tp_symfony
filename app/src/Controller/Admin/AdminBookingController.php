<?php

namespace App\Controller\Admin;

use App\Entity\Booking;
use App\Repository\BookingRepository;
use Knp\Component\Pager\PaginatorInterface;
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
  public function index(PaginatorInterface $paginator, Request $request): Response
  {
    $pagination = $paginator->paginate(
      $this->repository->findAllQuery(),
      $request->query->get('page', 1),
      4
    );

    return $this->render('pages/admin/booking/index.html.twig', [
      'pagination' => $pagination
    ]);
  }

  #[Route('/reservation/{id}', name: 'admin.booking.delete', methods: ['POST'])]
  public function delete(Booking $booking, Request $request): Response
  {
    if ($this->isCsrfTokenValid('delete' . $booking->getId(), $request->request->get('_token'))) {
      $this->repository->remove($booking, true);
      $this->addFlash('success', 'Annulation de la réservation avec succès');
    }

    return $this->redirectToRoute('admin.booking.index');
  }
}