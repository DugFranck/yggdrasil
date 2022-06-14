<?php

namespace App\Entity;

use App\Repository\ZoneRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ZoneRepository::class)
 */
class Zone
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity=Country::class, mappedBy="zone")
     */
    private $country;

    /**
     * @ORM\OneToMany(targetEntity=PriceSending::class, mappedBy="zone")
     */
    private $priceSendings;

    public function __construct()
    {
        $this->country = new ArrayCollection();
        $this->priceSendings = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, Country>
     */
    public function getCountry(): Collection
    {
        return $this->country;
    }

    public function addCountry(Country $country): self
    {
        if (!$this->country->contains($country)) {
            $this->country[] = $country;
            $country->setZone($this);
        }

        return $this;
    }

    public function removeCountry(Country $country): self
    {
        if ($this->country->removeElement($country)) {
            // set the owning side to null (unless already changed)
            if ($country->getZone() === $this) {
                $country->setZone(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, PriceSending>
     */
    public function getPriceSendings(): Collection
    {
        return $this->priceSendings;
    }

    public function addPriceSending(PriceSending $priceSending): self
    {
        if (!$this->priceSendings->contains($priceSending)) {
            $this->priceSendings[] = $priceSending;
            $priceSending->setZone($this);
        }

        return $this;
    }

    public function removePriceSending(PriceSending $priceSending): self
    {
        if ($this->priceSendings->removeElement($priceSending)) {
            // set the owning side to null (unless already changed)
            if ($priceSending->getZone() === $this) {
                $priceSending->setZone(null);
            }
        }

        return $this;
    }

    public function __toString(){
        return $this->getName();
    }
}
