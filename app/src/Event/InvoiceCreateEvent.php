<?php

namespace App\Event;

use App\Entity\Invoice;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Contracts\EventDispatcher\Event;

class InvoiceCreateEvent extends Event
{
    public const NAME = 'invoice.create';

    public function __construct(
        protected Invoice $invoice,
        public ManagerRegistry $doctrine
    )
    {
    }
    
    public function getInvoice(): Invoice
    {
        return $this->invoice;
    }
}