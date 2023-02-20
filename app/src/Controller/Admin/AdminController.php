<?php

namespace App\Controller\Admin;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
  #[Route('/admin', name: 'admin.index', methods: ['GET'])]
  public function index(): Response
  {
    return $this->render('pages/admin/index.html.twig');
  }
}