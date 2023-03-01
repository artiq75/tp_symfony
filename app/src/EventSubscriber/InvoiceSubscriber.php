<?php

namespace App\EventSubscriber;

use App\Entity\Alert;
use App\Entity\Invoice;
use App\Repository\AlertRepository;
use App\Repository\InvoiceLineRepository;
use App\Repository\InvoiceRepository;
use Knp\Snappy\Pdf;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\KernelInterface;
use Twig\Environment;

class InvoiceSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private InvoiceRepository $invoiceRepository,
        private InvoiceLineRepository $invoiceLineRepository,
        private AlertRepository $alertRepository,
        private Environment $twig,
        private KernelInterface $app,
        private Pdf $pdf
    )
    {
    }

    public function onKernelTerminate(RequestEvent $event)
    {
        $invoices = $this->invoiceRepository->findAll();
        $now = new \DateTime();
        
        foreach ($invoices as $invoice) {
            // Si 3 ans ou plus se sont écouler depuis la création de la facture
            if ($invoice->getCreatedAt()->diff($now)->y >= 3) {
                $invoiceLines = $this->invoiceLineRepository->findBy([
                    'invoice' => $invoice
                ]);

                $total = 0;

                // Calcule du total
                foreach ($invoiceLines as $invoiceLine) {
                    $total += $invoiceLine->getUnitPrice() / 100;
                }

                // Génération du pdf
                $this->pdf->generateFromHtml(
                    $this->twig->render('pages/admin/invoice/pdf.html.twig', [
                        'invoice' => $invoice,
                        'invoiceLines' => $invoiceLines,
                        'total' => $total
                    ]),
                    // Stockage du pdf dans le dossier 'public/pdf/uuid_de_la_facture'
                    $this->app->getProjectDir() . '/public/pdf/' . date('Y-m-d') . '/' . uniqid() . '.pdf'
                );
                // Passer la facture en état inactif
                $invoice->setIsActive(false);
                $this->invoiceRepository->save($invoice, true);
                // Notifier l'administration
                $this->addAlert($invoice);
            }
        }
    }

    private function addAlert(Invoice $invoice): void
    {
        $now = new \DateTime();

        $alert = new Alert();

        $title = sprintf("La facture numéro: (%s) à été imprimer et sortie de l'espace numérique", $invoice->getId());

        $alert
            ->setTitle($title)
            ->setPublishedAt($now);

        $this->alertRepository->save($alert, true);
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => 'onKernelTerminate'
        ];
    }
}