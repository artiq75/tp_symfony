<?php

namespace App\Entity;

use App\Repository\BookingRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

#[ORM\Entity(repositoryClass: BookingRepository::class)]
class Booking
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Assert\NotBlank]
    private ?\DateTimeInterface $start_date = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Assert\NotBlank]
    private ?\DateTimeInterface $end_date = null;

    #[ORM\Column]
    #[Assert\NotBlank]
    #[Assert\PositiveOrZero]
    private ?int $children = null;

    #[ORM\Column]
    #[Assert\NotBlank]
    #[Assert\PositiveOrZero]
    private ?int $adults = null;

    #[ORM\Column]
    #[Assert\NotBlank]
    #[Assert\Type('boolean')]
    private ?bool $pool_acess = null;

    #[ORM\Column]
    #[Assert\NotBlank]
    #[Assert\Type('boolean')]
    private ?bool $grant_access = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $created_at = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    private ?string $customer_firstname = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    private ?string $customer_lastname = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    private ?string $customer_address = null;

    #[ORM\ManyToOne(inversedBy: 'bookings', cascade: ['remove'])]
    private ?Property $property = null;

    public function __construct()
    {
        $this->created_at = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->start_date;
    }

    public function setStartDate(\DateTimeInterface $start_date): self
    {
        $this->start_date = $start_date;

        return $this;
    }

    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->end_date;
    }

    public function setEndDate(\DateTimeInterface $end_date): self
    {
        $this->end_date = $end_date;

        return $this;
    }

    public function getChildren(): ?int
    {
        return $this->children;
    }

    public function setChildren(int $children): self
    {
        $this->children = $children;

        return $this;
    }

    public function getAdults(): ?int
    {
        return $this->adults;
    }

    public function setAdults(int $adults): self
    {
        $this->adults = $adults;

        return $this;
    }

    public function isPoolAcess(): ?bool
    {
        return $this->pool_acess;
    }

    public function setPoolAcess(bool $pool_acess): self
    {
        $this->pool_acess = $pool_acess;

        return $this;
    }

    public function isGrantAccess(): ?bool
    {
        return $this->grant_access;
    }

    public function setGrantAccess(bool $grant_access): self
    {
        $this->grant_access = $grant_access;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

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

    public function setCustomerLastname(string $customer_lastname): self
    {
        $this->customer_lastname = $customer_lastname;

        return $this;
    }

    public function getCustomerFullName(): string
    {
        return $this->getCustomerFirstname() . ' ' . $this->getCustomerLastname();
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

    public function getProperty(): ?Property
    {
        return $this->property;
    }

    public function setProperty(?Property $property): self
    {
        $this->property = $property;

        return $this;
    }

    #[Assert\Callback]
    public function validateDate(ExecutionContextInterface $context, $payload)
    {
        $property = $this->getProperty();

        if ($this->start_date >= $this->end_date) {
            $context->buildViolation("La date d'arriver doit être inférieur à la date de départ!")
                ->atPath('start_date')
                ->addViolation();
        }

        if (
            $this->start_date < $property->getAvailabilityStart() ||
            $this->end_date > $property->getAvailabilityEnd()
        ) {
            $context->buildViolation("Les dates de réservation doivent corréspondre aux dates de disponibilté!")
                ->atPath('start_date')
                ->addViolation();
        }
    }
}