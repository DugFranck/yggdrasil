<?php

namespace App\Entity;

use App\Repository\OrderItemRepository;
use Doctrine\ORM\Mapping as ORM;
use phpDocumentor\Reflection\Types\Integer;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=OrderItemRepository::class)
 */
class OrderItem
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=ProductDimensionStock::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $productDimensionStock;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank()
     * @Assert\GreaterThanOrEqual(1)
     */
    private $quantity;

    /**
     * @ORM\ManyToOne(targetEntity=Order::class, inversedBy="items")
     * @ORM\JoinColumn(nullable=false)
     */
    private $orderRef;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProductDimensionStock(): ?ProductDimensionStock
    {
        return $this->productDimensionStock;
    }

    public function setProductDimensionStock(?ProductDimensionStock $productDimensionStock): self
    {
        $this->productDimensionStock = $productDimensionStock;

        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getOrderRef(): ?Order
    {
        return $this->orderRef;
    }

    public function setOrderRef(?Order $orderRef): self
    {
        $this->orderRef = $orderRef;

        return $this;
    }
    /**
     * Tests if the given item given corresponds to the same order item.
     *
     * @param OrderItem $item
     *
     * @return bool
     */
    public function equals(OrderItem $item): bool
    {
        return $this->getProductDimensionStock()->getId() === $item->getProductDimensionStock()->getId();
    }
  /*  public function add($id, OrderItem $item)
    {
        if(!empty($item[$id])){
            $item[$id]++;
        }else{
            $item[$id]=1;
        }
    }*/

    /**
     * Calculates the item total.
     *
     * @return float|int
     */
    public function getTotal(): float
    {
        return $this->getProductDimensionStock()->getProduct()->getPrice() * $this->getQuantity();
    }

    /**
     * Calculates the item total.
     *
     * @return int
     */
    public function getTotalPoids(): int
    {
        return $this->getProductDimensionStock()->getPoids() * $this->getQuantity();
    }



}
