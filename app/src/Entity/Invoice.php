<?php

namespace App\Entity;

use App\Repository\InvoiceRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: InvoiceRepository::class)]
class Invoice
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $company_name = null;

    #[ORM\Column(length: 255)]
    private ?string $company_address = null;

    #[ORM\Column(length: 255)]
    private ?string $company_siret = null;

    #[ORM\Column(length: 255)]
    private ?string $company_tva = null;

    #[ORM\Column(length: 255)]
    private ?string $customer_firstname = null;

    #[ORM\Column(length: 255)]
    private ?string $customer_lastname = null;

    #[ORM\Column(length: 255)]
    private ?string $customer_address = null;

    #[ORM\Column]
    private ?int $price_ttc = null;

    #[ORM\Column]
    private ?int $price_ht = null;

    #[ORM\Column]
    private ?int $total = null;

    #[ORM\Column(options: [
        'default' => false
    ])]
    private ?bool $is_cancel = false;

    #[ORM\Column]
    private ?\DateTimeImmutable $created_at = null;

    public function __construct()
    {
        $this->setCreatedAt(new \DateTimeImmutable());
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCompanyName(): ?string
    {
        return $this->company_name;
    }

    public function setCompanyName(string $company_name): self
    {
        $this->company_name = $company_name;

        return $this;
    }

    public function getCompanyAddress(): ?string
    {
        return $this->company_address;
    }

    public function setCompanyAddress(string $company_address): self
    {
        $this->company_address = $company_address;

        return $this;
    }

    public function getCompanySiret(): ?string
    {
        return $this->company_siret;
    }

    public function setCompanySiret(string $company_siret): self
    {
        $this->company_siret = $company_siret;

        return $this;
    }

    public function getCompanyTva(): ?string
    {
        return $this->company_tva;
    }

    public function setCompanyTva(string $company_tva): self
    {
        $this->company_tva = $company_tva;

        return $this;
    }

    public function getCustomerFirstname(): ?string
    {
        return $this->customer_firstname;
    }

    public function setCustomerFirstname(string $customer_firstname): self
    {
        $this->customer_firstname = $customer_firstname;

        return $this;
    }

    public function getCustomerLastname(): ?string
    {
        return $this->customer_lastname;
    }

    public function getCustomerFullName(): string
    {
        return $this->getCustomerFirstname() . ' ' . $this->getCustomerLastname();
    }

    public function setCustomerLastname(string $customer_lastname): self
    {
        $this->customer_lastname = $customer_lastname;

        return $this;
    }

    public function getCustomerAddress(): ?string
    {
        return $this->customer_address;
    }

    public function setCustomerAddress(string $customer_address): self
    {
        $this->customer_address = $customer_address;

        return $this;
    }

    public function getPriceTtc(): ?int
    {
        return $this->price_ttc;
    }

    public function setPriceTtc(int $price_ttc): self
    {
        $this->price_ttc = $price_ttc;

        return $this;
    }

    public function getPriceHt(): ?int
    {
        return $this->price_ht;
    }

    public function setPriceHt(int $price_ht): self
    {
        $this->price_ht = $price_ht;

        return $this;
    }

    public function getTotal(): ?int
    {
        return $this->total;
    }

    public function setTotal(int $total): self
    {
        $this->total = $total;

        return $this;
    }

    public function getFormatedTotal(): string
    {
        return number_format($this->total / 100, 0, '', ' ');
    }

    public function isIsCancel(): ?bool
    {
        return $this->is_cancel;
    }

    public function setIsCancel(bool $is_cancel): self
    {
        $this->is_cancel = $is_cancel;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }
}
