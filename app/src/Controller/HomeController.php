<?php

namespace App\Controller;

use App\Entity\Booking;
use App\Entity\Period;
use App\Entity\Property;
use App\Event\BookingBookEvent;
use App\Form\BookingType;
use App\Repository\PropertyRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
  public function __construct(
    private PropertyRepository $propertyRepository,
    private ManagerRegistry $doctrine,
    private EventDispatcherInterface $dispatcher
  )
  {
  }

  #[Route('/', name: 'home')]
  public function index(): Response
  {
    return $this->render('pages/home.html.twig', [
      'properties' => $this->propertyRepository->findAll()
    ]);
  }

  #[Route('biens/{id}', name: 'property.show', methods: ['GET', 'POST'])]
  public function show(Property $property, Request $request): Response
  {
    $booking = new Booking();
    $booking->setProperty($property);
    $form = $this->createForm(BookingType::class, $booking);
    $form->handleRequest($request);

    $isCampingOpen = Period::isCampingOpen();

    if ($form->isSubmitted() && $form->isValid() && $isCampingOpen) {
      $manager = $this->doctrine->getManager();

      $manager->persist($booking);

      $this->dispatcher->dispatch(new BookingBookEvent($booking), BookingBookEvent::NAME);

      // $stayTax = $this->taxRepository->findOneBy(['slug' => Tax::TAX_STAY_SLUG]);

      // $price = $booking->getProperty()->getType()->getPrice();

      // $now = new \DateTime();

      // if (
      //   $now->format('d') >= Period::CAMPING_HIGHT_SEASON_DATE['start']['days'] &&
      //   $now->format('m') >= Period::CAMPING_HIGHT_SEASON_DATE['start']['month'] &&
      //   $now->format('d') <= Period::CAMPING_HIGHT_SEASON_DATE['end']['days'] &&
      //   $now->format('m') <= Period::CAMPING_HIGHT_SEASON_DATE['end']['month']
      // ) {
      //   $price += ($price * 15 / 100);

      //   $difference = $booking->getEndDate()->getTimestamp() - $booking->getStartDate()->getTimestamp();

      //   // Calcul nombre de tranche de 7 jours
      //   $number7DayBlock = floor($difference / 7);

      //   // Réducation 5% par tranche de 7 jours
      //   for ($i = 0; $i < $number7DayBlock; $i++) {
      //     $price -= (5 / 100);
      //   }
      // }

      // $stayChildTax = $stayTax->getChildRate() * $booking->getChildren();
      // $stayAdultTax = $stayTax->getAdultRate() * $booking->getAdults();

      // $price = $booking->getProperty()->getType()->getPrice() + $stayChildTax + $stayAdultTax;

      // if ($booking->isPoolAcess()) {
      //   $poolTax = $this->taxRepository->findOneBy([
      //     'slug' => Tax::TAX_POOL_SLUG
      //   ]);

      //   $poolChildTax = $poolTax->getChildRate() * $booking->getChildren();
      //   $poolAdultTax = $poolTax->getAdultRate() * $booking->getAdults();

      //   $price += $poolChildTax + $poolAdultTax;
      // }

      // $invoice = new Invoice();
      // $invoice
      //   ->setCompanyName("Espadrille Volante")
      //   ->setCompanyAddress("5 rue de l'espadrille, Perpignan, 66000")
      //   ->setCustomerName($booking->getCustomerFullName())
      //   ->setCustomerAddress($booking->getCustomerAddress())
      //   ->setTva(20)
      //   ->setUuid(uniqid());

      // $invoiceLine = new InvoiceLine();
      // $invoiceLine
      //   ->setAdults($booking->getAdults())
      //   ->setChildren($booking->getChildren())
      //   ->setDesignation($booking->getProperty()->getType()->getLabel())
      //   ->setUnitPrice($price)
      //   ->setInvoice($invoice);

      // $manager->persist($invoice);
      // $manager->flush();

      $this->addFlash('success', 'Réservation prise en compte!');
    }

    return $this->render('pages/property/show.html.twig', [
      'property' => $property,
      'form' => $form,
      'is_open' => $isCampingOpen
    ]);
  }
}