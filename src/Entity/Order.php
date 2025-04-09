<?php

namespace App\Entity;

use App\Repository\OrderRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: OrderRepository::class)]
#[ORM\Table(name: '`order`')]
class Order
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(["order"])]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'orders')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(["order"])]
    private ?User $owner = null;

    /**
     * @var Collection<int, ProductInCart>
     */
    #[Groups(["order"])]
    #[ORM\OneToMany(targetEntity: ProductInCart::class, mappedBy: 'orderOfProducItemCart', orphanRemoval: true,cascade: ["persist"])]
    private Collection $productsItems;

    #[ORM\Column]
    #[Groups(["order"])]
    private ?\DateTimeImmutable $createdAt = null;

    public function __construct()
    {
        $this->productsItems = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }


    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(?User $owner): static
    {
        $this->owner = $owner;

        return $this;
    }

    /**
     * @return Collection<int, ProductInCart>
     */
    public function getProductsItems(): Collection
    {
        return $this->productsItems;
    }

    public function addProductsItem(ProductInCart $productsItem): static
    {
        if (!$this->productsItems->contains($productsItem)) {
            $this->productsItems->add($productsItem);
            $productsItem->setOrderOfProducItemCart($this);
        }

        return $this;
    }

    public function removeProductsItem(ProductInCart $productsItem): static
    {
        if ($this->productsItems->removeElement($productsItem)) {
            // set the owning side to null (unless already changed)
            if ($productsItem->getOrderOfProducItemCart() === $this) {
                $productsItem->setOrderOfProducItemCart(null);
            }
        }

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }
}
