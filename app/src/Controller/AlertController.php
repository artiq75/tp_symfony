<?php

namespace App\Controller;

use App\Entity\Alert;
use App\Repository\AlertRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_ADMIN')]
class AlertController extends AbstractController
{
  public function __construct(
    private AlertRepository $repository
  )
  {
  }

  #[Route('/notifications', name: 'alert.index', methods: ['GET'])]
  public function index(): Response
  {
    return $this->render('pages/alert/index.html.twig', [
      'alerts' => $this->repository->findAll()
    ]);
  }

  #[Route('/notifications/{id}', name: 'alert.seen', methods: ['GET'])]
  public function seen(Alert $alert): Response
  {
    $alert->setIsSeen(true);
    $this->repository->save($alert, true);
    return $this->redirectToRoute('alert.index');
  }

  #[Route('/notifications/{id}', name: 'alert.delete', methods: ['POST'])]
  public function delete(Alert $alert, Request $request) 
  {
    if ($this->isCsrfTokenValid('delete' . $alert->getId(), $request->request->get('_token'))) {
      $this->repository->remove($alert, true);
    }
    
    return $this->redirectToRoute('alert.index');
  }

  public function alert(): Response
  {
    return $this->render('includes/_alert.html.twig', [
      'has_new_alerts' => !empty($this->repository->findAllNotSeen())
    ]);
  }
}