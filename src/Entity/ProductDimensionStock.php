<?php

namespace App\Entity;

use App\Repository\ProductDimensionStockRepository;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ProductDimensionStockRepository::class)
 */
class ProductDimensionStock
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Product::class, inversedBy="productDimensionStocks")
     */
    private $product;

    /**
     * @ORM\ManyToOne(targetEntity=Dimension::class, inversedBy="productDimensionStocks")
     * @Assert\NotBlank()
     *
     */
    private $dimension;

    /**
     * @ORM\Column(type="integer")
     */
    private $stock;

    /**
     * @ORM\Column(type="integer")
     */
    private $poids;



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): self
    {
        $this->product = $product;

        return $this;
    }

    public function getDimension(): ?Dimension
    {
        return $this->dimension;
    }

    public function setDimension(?Dimension $dimension): self
    {
        $this->dimension = $dimension;

        return $this;
    }


    public function __toString(){
        return $this->getDimension();
    }

    public function getStock(): ?int
    {
        return $this->stock;
    }

    public function setStock(int $stock): self
    {
        $this->stock = $stock;

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




}
