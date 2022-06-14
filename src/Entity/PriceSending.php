<?php

namespace App\Entity;

use App\Repository\PriceSendingRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PriceSendingRepository::class)
 */
class PriceSending
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Carrier::class, inversedBy="priceSendings")
     * @ORM\JoinColumn(nullable=false)
     */
    private $carrier;

    /**
     * @ORM\ManyToOne(targetEntity=Zone::class, inversedBy="priceSendings")
     * @ORM\JoinColumn(nullable=false)
     */
    private $zone;

    /**
     * @ORM\Column(type="integer")
     */
    private $poids;

    /**
     * @ORM\Column(type="float")
     */
    private $price;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCarrier(): ?Carrier
    {
        return $this->carrier;
    }

    public function setCarrier(?Carrier $carrier): self
    {
        $this->carrier = $carrier;

        return $this;
    }

    public function getZone(): ?Zone
    {
        return $this->zone;
    }

    public function setZone(?Zone $zone): self
    {
        $this->zone = $zone;

        return $this;
    }

    public function getPoids(): ?int
    {
        return $this->poids;
    }

    public function setPoids(int $poids): self
    {
        $this->poids = $poids;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

}
