<?php

namespace App\Controller;

use App\Entity\Property;
use App\Form\PropertyType;
use App\Repository\PropertyRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PropertyController extends AbstractController
{
  #[Route('bien/{id}', name: 'property.show', methods: ['GET'])]
  public function show(Property $property): Response
  {
    return $this->render('pages/property/show.html.twig', [
      'property' => $property
    ]);
  }
}