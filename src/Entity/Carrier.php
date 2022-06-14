<?php

namespace App\Entity;

use App\Repository\CarrierRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CarrierRepository::class)
 */
class Carrier
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
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\OneToMany(targetEntity=PriceSending::class, mappedBy="carrier")
     */
    private $priceSendings;

    public function __construct()
    {
        $this->priceSendings = new ArrayCollection();
    }




    public function __toStringName()
    {
        return $this->getName();
    }
    public function __toString()
    {
        return $this->getName().'[br]'.$this->getDescription();
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

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
            $priceSending->setCarrier($this);
        }

        return $this;
    }

    public function removePriceSending(PriceSending $priceSending): self
    {
        if ($this->priceSendings->removeElement($priceSending)) {
            // set the owning side to null (unless already changed)
            if ($priceSending->getCarrier() === $this) {
                $priceSending->setCarrier(null);
            }
        }

        return $this;
    }


    public function price(){

    }




}
