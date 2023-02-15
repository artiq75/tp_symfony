<?php

namespace App\Controller\Admin;

use App\Entity\Property;
use App\Form\PropertyType;
use App\Repository\PropertyRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('admin')]
class AdminPropertyController extends AbstractController
{
  public function __construct(
    private PropertyRepository $propertyRepository
  )
  {
  }

  #[Route('/biens', name: 'admin.property.index', methods: ['GET'])]
  public function index(): Response
  {
    return $this->render('pages/admin/property/index.html.twig', [
      'properties' => $this->propertyRepository->findAll()
    ]);
  }

  #[Route('/biens/creation', name: 'admin.property.new', methods: ['GET', 'POST'])]
  public function new(Request $request): Response
  {
    $property = new Property();
    $form = $this->createForm(PropertyType::class, $property);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $this->propertyRepository->save($property, true);

      return $this->redirectToRoute('home');
    }

    return $this->render('pages/admin/property/new.html.twig', [
      'form' => $form
    ]);
  }

  #[Route('/biens/{id}/edit', name: 'admin.property.edit', methods: ['GET', 'POST'])]
  public function edit(Property $property, Request $request): Response
  {
    $form = $this->createForm(PropertyType::class, $property);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $this->propertyRepository->save($property, true);

      return $this->redirectToRoute('home');
    }

    return $this->render('pages/admin/property/edit.html.twig', [
      'property' => $property,
      'form' => $form
    ]);
  }

  #[Route('/biens/{id}', name: 'admin.property.delete', methods: ['POST'])]
  public function delete(Property $property, Request $request): Response
  {
    if ($this->isCsrfTokenValid('delete' . $property->getId(), $request->request->get('_token'))) {
      $this->propertyRepository->remove($property, true);
      $this->addFlash('success', 'Bien supprimer avec succès');
    }
    
    return $this->redirectToRoute('admin.property.index');
  }
}