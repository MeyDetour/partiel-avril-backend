<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use App\Service\ImageService;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
#[Vich\Uploadable]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['products','order'])]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Groups(["products",'order'])]
    private ?string $name = null;

    #[ORM\Column]
    #[Groups(["products",'order'])]
    private ?float $price = null;

    #[Groups(["products"])]
    #[ORM\Column(nullable: true)]
    private ?string $imageUrl = null;
    #[Groups(["products"])]
    #[ORM\Column(nullable: true)]
    private ?string $qrCodeUrl = null;


    #[ORM\OneToOne(inversedBy: 'product', cascade: ['persist', 'remove'])]
    private ?Image $image = null;


    #[ORM\OneToOne(inversedBy: 'product', cascade: ['persist', 'remove'])]
    private ?QrCodeImage $qrCodeImage = null;

    /**
     * @var Collection<int, Order>
     */
    #[ORM\ManyToMany(targetEntity: Order::class, mappedBy: 'products')]
    private Collection $orders;


    public function __construct()
    {

        $this->orders = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getImage(): ?Image
    {
        return $this->image;
    }


    public function setImage(?Image $image): static
    {
        $this->image = $image;

        return $this;
    }

    public function getQrCodeImage(): ?QrCodeImage
    {
        return $this->qrCodeImage;
    }


    public function setQrCodeImage(?QrCodeImage $qrCodeImage): static
    {
        $this->qrCodeImage = $qrCodeImage;

        return $this;
    }

    public function getQrCodeUrl(): string|null
    {
        return $this->qrCodeUrl;
    }


    public function setQrCodeUrl(string|null $qrCodeUrl): static
    {
        $this->qrCodeUrl = $qrCodeUrl;

        return $this;
    }    public function getImageUrl(): string|null
    {
        return $this->imageUrl;
    }


    public function setImageUrl(string|null $imageUrl): static
    {
        $this->imageUrl = $imageUrl;

        return $this;
    }


    /**
     * @return Collection<int, Order>
     */
    public function getOrders(): Collection
    {
        return $this->orders;
    }

    public function addOrder(Order $order): static
    {
        if (!$this->orders->contains($order)) {
            $this->orders->add($order);
            $order->addProduct($this);
        }

        return $this;
    }

    public function removeOrder(Order $order): static
    {
        if ($this->orders->removeElement($order)) {
            $order->removeProduct($this);
        }

        return $this;
    }


}
