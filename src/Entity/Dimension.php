<?php

namespace App\Entity;

use App\Repository\DimensionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=DimensionRepository::class)
 */
class Dimension
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
     * @ORM\OneToMany(targetEntity=ProductDimensionStock::class, mappedBy="dimension")
     */
    private $productDimensionStocks;

    public function __construct()
    {
        $this->productDimensionStocks = new ArrayCollection();
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




    public function __toString(){
        return $this->getName();
    }

    /**
     * @return Collection|ProductDimensionStock[]
     */
    public function getProductDimensionStocks(): Collection
    {
        return $this->productDimensionStocks;
    }

    public function addProductDimensionStock(ProductDimensionStock $productDimensionStock): self
    {
        if (!$this->productDimensionStocks->contains($productDimensionStock)) {
            $this->productDimensionStocks[] = $productDimensionStock;
            $productDimensionStock->setDimension($this);
        }

        return $this;
    }

    public function removeProductDimensionStock(ProductDimensionStock $productDimensionStock): self
    {
        if ($this->productDimensionStocks->removeElement($productDimensionStock)) {
            // set the owning side to null (unless already changed)
            if ($productDimensionStock->getDimension() === $this) {
                $productDimensionStock->setDimension(null);
            }
        }

        return $this;
    }





}
