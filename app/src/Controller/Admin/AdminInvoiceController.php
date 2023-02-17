<?php

namespace App\Controller\Admin;

use App\Entity\Invoice;
use App\Repository\InvoiceRepository;
use Knp\Bundle\SnappyBundle\Snappy\Response\PdfResponse;
use Knp\Snappy\Pdf;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('admin')]
class AdminInvoiceController extends AbstractController
{
  public function __construct(
    private InvoiceRepository $repository
  )
  {
  }

  #[Route('/facturations', name: 'admin.invoice.index', methods: ['GET'])]
  public function index(): Response
  {
    return $this->render('pages/admin/invoice/index.html.twig', [
      'invoices' => $this->repository->findAll()
    ]);
  }

  #[Route('/facturations/{id}', name: 'admin.invoice.cancel', methods: ['POST'])]
  public function cancel(Invoice $invoice, Request $request): Response
  {
    if ($this->isCsrfTokenValid('delete' . $invoice->getId(), $request->request->get('_token'))) {
      $invoice->setIsCancel(true);
      $this->repository->save($invoice, true);
      $this->addFlash('success', 'Annulation de la facture avec succès');
    }

    return $this->redirectToRoute('admin.invoice.index');
  }

  #[Route('/facturations/{id}/pdf', name: 'admin.invoice.pdf', methods: ['GET'])]
  public function generatePdf(Invoice $invoice, Pdf $pdf): PdfResponse
  {
    $html = $this->renderView('pages/admin/invoice/pdf.html.twig', [
      'invoice' => $invoice
    ]);

    return new PdfResponse(
      $pdf->getOutputFromHtml($html),
      'facture-' . $invoice->getUuid() . '.pdf'
    );
  }
}