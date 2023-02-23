<?php

namespace App\Controller\Admin;

use App\Entity\Invoice;
use App\Repository\InvoiceLineRepository;
use App\Repository\InvoiceRepository;
use Knp\Bundle\SnappyBundle\Snappy\Response\PdfResponse;
use Knp\Component\Pager\PaginatorInterface;
use Knp\Snappy\Pdf;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('admin')]
class AdminInvoiceController extends AbstractController
{
  public function __construct(
    private InvoiceRepository $repository,
    private InvoiceLineRepository $invoiceLineRepository
  )
  {
  }

  #[Route('/facturations', name: 'admin.invoice.index', methods: ['GET'])]
  public function index(PaginatorInterface $paginator, Request $request): Response
  {
    $pagination = $paginator->paginate(
      $this->repository->findAllQuery(),
      $request->query->get('page', 1),
      8
    );

    return $this->render('pages/admin/invoice/index.html.twig', [
      'pagination' => $pagination
    ]);
  }

  #[Route('/facturations/{id}', name: 'admin.invoice.cancel', methods: ['POST'])]
  public function cancel(Invoice $invoice, Request $request): Response
  {
    if ($this->isCsrfTokenValid('delete' . $invoice->getId(), $request->request->get('_token'))) {
      $invoice->setIsActive(false);
      $this->repository->save($invoice, true);
      $this->addFlash('success', 'Annulation de la facture avec succès');
    }

    return $this->redirectToRoute('admin.invoice.index');
  }

  #[Route('/facturations/{id}/pdf', name: 'admin.invoice.pdf', methods: ['GET'])]
  public function generatePdf(Invoice $invoice, Pdf $pdf): PdfResponse
  {
    $invoiceLines = $this->invoiceLineRepository->findBy([
      'invoice' => $invoice
    ]);
    
    $html = $this->renderView('pages/admin/invoice/pdf.html.twig', [
      'invoice' => $invoice,
      'invoiceLines' => $invoiceLines
    ]);

    return new PdfResponse(
      $pdf->getOutputFromHtml($html),
      date('Y-m-d')
    );
  }
}