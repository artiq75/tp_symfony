<?php

namespace App\Entity;

use App\Repository\TaxRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TaxRepository::class)]
class Tax
{
    public const TAX_STAY_SLUG = 'taxe-sejour';
    
    public const TAX_POOL_SLUG = 'taxe-piscine';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $label = null;

    #[ORM\Column(length: 255)]
    private ?string $slug = null;

    #[ORM\Column]
    private ?int $childRate = null;

    #[ORM\Column]
    private ?int $adultRate = null;

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

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getChildRate(): ?int
    {
        return $this->childRate;
    }

    public function setChildRate(int $childRate): self
    {
        $this->childRate = $childRate;

        return $this;
    }

    public function getAdultRate(): ?int
    {
        return $this->adultRate;
    }

    public function setAdultRate(int $adultRate): self
    {
        $this->adultRate = $adultRate;

        return $this;
    }
}
