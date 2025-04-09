<?php

namespace App\Entity;

use App\Repository\ProductInCartRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: ProductInCartRepository::class)]
class ProductInCart
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(["order"])]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[Groups(["order"])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Product $product = null;

    #[ORM\Column]
    #[Groups(["order"])]
    private ?int $quantity = null;

    #[ORM\ManyToOne(inversedBy: 'productsItems')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Order $orderOfProducItemCart = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): static
    {
        $this->product = $product;

        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): static
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getOrderOfProducItemCart(): ?Order
    {
        return $this->orderOfProducItemCart;
    }

    public function setOrderOfProducItemCart(?Order $orderOfProducItemCart): static
    {
        $this->orderOfProducItemCart = $orderOfProducItemCart;

        return $this;
    }
}
