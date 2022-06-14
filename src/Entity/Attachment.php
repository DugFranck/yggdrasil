<?php

namespace App\Entity;

use App\Repository\AttachmentRepository;
use Doctrine\ORM\Mapping as ORM;


use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;

use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass=AttachmentRepository::class)
 * @Vich\Uploadable()
 */
class Attachment
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     *
     */
    private $image;

    /**
     *
     * @Vich\UploadableField(mapping="attachments", fileNameProperty="image")
     * @var File
     * @Assert\Image(mimeTypes={"image/png", "image/jpeg", "image/jpg", "image/gif"})
     */
    private $imageFile;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createAt;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updateAt;



    /**
     * @ORM\ManyToOne(targetEntity=Product::class, inversedBy="attachments")
     */
    private $product;



    public function __construct()
    {
        $this->createAt = new \DateTime();
        $this->updateAt = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }



    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    /**
     * @param File|null $image
     * @return void
     */
    public function setImageFile( File $image = null): void
    {
        $this->imageFile = $image;

        if($image){
            $this->updateAt = new \DateTime('now');
        }
    }

    public function getCreateAt(): ?\DateTimeInterface
    {
        return $this->createAt;
    }

    public function setCreateAt(\DateTimeInterface $createAt): self
    {
        $this->createAt = $createAt;

        return $this;
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

    public function getUpdateAt(): ?\DateTimeInterface
    {
        return $this->updateAt;
    }

    public function setUdateAt(\DateTimeInterface $updateAt): self
    {
        $this->updateAt = $updateAt;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }
}
