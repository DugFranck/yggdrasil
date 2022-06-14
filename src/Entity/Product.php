<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;


/**
 * @ORM\Entity(repositoryClass=ProductRepository::class)
 */
class Product
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
     * @ORM\Column(type="string", length=255)
     */
    private $slug;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $illustration;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $subtitle;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\Column(type="float")
     */
    private $price;

    /**
     * @ORM\ManyToOne(targetEntity=Category::class, inversedBy="products")
     * @ORM\JoinColumn(nullable=false)
     */
    private $category;

    /**
     * @ORM\OneToMany(targetEntity=ProductDimensionStock::class, mappedBy="product")
     */
    private $productDimensionStocks;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isBest;

    /**
     *
     * @var string
     * @ORM\OneToMany(targetEntity=Attachment::class, mappedBy="product", cascade={"all","persist", "remove"})
     *
     */
    private $attachments;



    public function __construct()
    {
        $this->productDimensionStocks = new ArrayCollection();
        $this->attachments = new ArrayCollection();

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

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getIllustration(): ?string
    {
        return $this->illustration;
    }

    public function setIllustration(string $illustration): self
    {
        $this->illustration = $illustration;

        return $this;
    }

    public function getSubtitle(): ?string
    {
        return $this->subtitle;
    }

    public function setSubtitle(string $subtitle): self
    {
        $this->subtitle = $subtitle;

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

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

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
            $productDimensionStock->setProduct($this);
        }

        return $this;
    }

    public function removeProductDimensionStock(ProductDimensionStock $productDimensionStock): self
    {
        if ($this->productDimensionStocks->removeElement($productDimensionStock)) {
            // set the owning side to null (unless already changed)
            if ($productDimensionStock->getProduct() === $this) {
                $productDimensionStock->setProduct(null);
            }
        }

        return $this;
    }

    public function getIsBest(): ?bool
    {
        return $this->isBest;
    }

    public function setIsBest(bool $isBest): self
    {
        $this->isBest = $isBest;

        return $this;
    }

    /**
     *
     * @return Collection<int, Attachment>
     */
    public function getAttachments(): Collection
    {
        return $this->attachments;
    }

    public function addAttachment(Attachment $attachment): self
    {
        if (!$this->attachments->contains($attachment)) {
            $this->attachments[] = $attachment;
            $attachment->setProduct($this);
        }

        return $this;
    }

    public function removeAttachment(Attachment $attachment): self
    {
        if ($this->attachments->removeElement($attachment)) {
            // set the owning side to null (unless already changed)
            if ($attachment->getProduct() === $this) {
                $attachment->setProduct(null);
            }
        }

        return $this;
    }








}
