<?php

namespace App\Entity;

use App\Repository\InvoiceLineRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: InvoiceLineRepository::class)]
class InvoiceLine
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $unitPrice = null;

    #[ORM\Column]
    private ?int $fillOrder = null;

    #[ORM\Column]
    private ?int $days = null;

    #[ORM\Column]
    private ?int $type = null;

    #[ORM\ManyToOne(inversedBy: 'invoiceLines')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Invoice $invoice = null;

    #[ORM\Column(length: 255)]
    private ?string $designation = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUnitPrice(): ?int
    {
        return $this->unitPrice;
    }

    public function setUnitPrice(int $unitPrice): self
    {
        $this->unitPrice = $unitPrice;

        return $this;
    }

    public function getFormatedPrice(): string
    {
        return number_format($this->unitPrice / 100, 0, '', ' ');
    }

    public function getFillOrder(): ?int
    {
        return $this->fillOrder;
    }

    public function setFillOrder(int $fillOrder): self
    {
        $this->fillOrder = $fillOrder;

        return $this;
    }

    public function getDays(): ?int
    {
        return $this->days;
    }

    public function setDays(int $days): self
    {
        $this->days = $days;

        return $this;
    }

    public function getType(): ?int
    {
        return $this->type;
    }

    public function setType(int $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getInvoice(): ?Invoice
    {
        return $this->invoice;
    }

    public function setInvoice(?Invoice $invoice): self
    {
        $this->invoice = $invoice;

        return $this;
    }

    public function getDesignation(): ?string
    {
        return $this->designation;
    }

    public function setDesignation(string $designation): self
    {
        $this->designation = $designation;

        return $this;
    }
}
