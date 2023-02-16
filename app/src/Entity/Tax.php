<?php

namespace App\Entity;

use App\Repository\TaxRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TaxRepository::class)]
class Tax
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $label = null;

    #[ORM\Column]
    private ?int $child_rate = null;

    #[ORM\Column]
    private ?int $adult_rate = null;

    #[ORM\ManyToMany(targetEntity: Booking::class, inversedBy: 'taxes')]
    private Collection $bookings;

    public function __construct()
    {
        $this->bookings = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(string $label): self
    {
        $this->label = $label;

        return $this;
    }

    public function getChildRate(): ?int
    {
        return $this->child_rate;
    }

    public function setChildRate(int $child_rate): self
    {
        $this->child_rate = $child_rate;

        return $this;
    }

    public function getAdultRate(): ?int
    {
        return $this->adult_rate;
    }

    public function setAdultRate(int $adult_rate): self
    {
        $this->adult_rate = $adult_rate;

        return $this;
    }

    /**
     * @return Collection<int, Booking>
     */
    public function getBookings(): Collection
    {
        return $this->bookings;
    }

    public function addBookings(Booking $bookings): self
    {
        if (!$this->bookings->contains($bookings)) {
            $this->bookings->add($bookings);
        }

        return $this;
    }

    public function removeBookings(Booking $bookings): self
    {
        $this->bookings->removeElement($bookings);

        return $this;
    }
}
