<?php

namespace App\Controller;

use App\Entity\Booking;
use App\Entity\Invoice;
use App\Entity\InvoiceLine;
use App\Entity\Period;
use App\Entity\Property;
use App\Entity\PropertyType;
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

      $days = $booking->getEndDate()->diff($booking->getStartDate())->d;

      $invoice = new Invoice();
      $invoice
        ->setCompanyName("Espadrille Volante")
        ->setCompanyAddress("5 rue de l'espadrille, Perpignan, 66000")
        ->setCustomerName($booking->getCustomerFullName())
        ->setCustomerAddress($booking->getCustomerAddress());
      $manager->persist($invoice);

      /**
       * @var Property
       */
      $stayProperty = $this->propertyRepository->findOneBy([
        'type_id' => PropertyType::STAY_TYPE
      ]);

      $price = $property->getAdultRate();

      if (Period::isHightSeason()) {
        $price += (15 / 100);
      }

      $propertyInvoiceLine = new InvoiceLine();
      $propertyInvoiceLine
        ->setUnitPrice($price)
        ->setType($property->getId())
        ->setFillOrder(1)
        ->setDays($days)
        ->setInvoice($invoice);
      $manager->persist($invoice);

      $price = $stayProperty->getAdultRate() + $stayProperty->getChildRate();

      $adultRate = $stayProperty->getAdultRate() * $invoice->getAdults();
      $chidlrenRate = $stayProperty->getChildRate() * $invoice->getChildren();
      $price = $adultRate + $chidlrenRate;

      $stayInvoiceLine = new InvoiceLine();
      $stayInvoiceLine
        ->setUnitPrice($price)
        ->setType($stayProperty->getId())
        ->setFillOrder(2)
        ->setDays($days)
        ->setInvoice($invoice);
      $manager->persist($invoice);

      if ($booking->isPoolAcess()) {
        /**
         * @var Property
         */
        $poolProperty = $this->propertyRepository->findOneBy([
          'type_id' => PropertyType::POOL_TYPE
        ]);

        $price = $poolProperty->getAdultRate() + $poolProperty->getChildRate();

        $adultRate = $poolProperty->getAdultRate() * $invoice->getAdults();
        $chidlrenRate = $poolProperty->getChildRate() * $invoice->getChildren();
        $price = $adultRate + $chidlrenRate;
        
        $poolInvoiceLine = new InvoiceLine();
        $poolInvoiceLine
          ->setUnitPrice($price)
          ->setType($poolProperty->getId())
          ->setFillOrder(3)
          ->setDays($days)
          ->setInvoice($invoice);
        $manager->persist($invoice);
      }

      $manager->flush();

      $this->addFlash('success', 'Réservation prise en compte!');
    }

    return $this->render('pages/property/show.html.twig', [
      'property' => $property,
      'form' => $form,
      'is_open' => $isCampingOpen
    ]);
  }
}