<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $name = null;

    #[ORM\Column(nullable: true)]
    private ?int $price = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $picture = null;

    #[ORM\OneToMany(mappedBy: 'productLink', targetEntity: ItemOrder::class)]
    private Collection $itemOrders;

    public function __construct()
    {
        $this->itemOrders = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(?int $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPicture(): ?string
    {
        return $this->picture;
    }

    public function setPicture(?string $picture): self
    {
        $this->picture = $picture;

        return $this;
    }

    /**
     * @return Collection<int, ItemOrder>
     */
    public function getItemOrders(): Collection
    {
        return $this->itemOrders;
    }

    public function addItemOrder(ItemOrder $itemOrder): self
    {
        if (!$this->itemOrders->contains($itemOrder)) {
            $this->itemOrders->add($itemOrder);
            $itemOrder->setProductLink($this);
        }

        return $this;
    }

    public function removeItemOrder(ItemOrder $itemOrder): self
    {
        if ($this->itemOrders->removeElement($itemOrder)) {
            // set the owning side to null (unless already changed)
            if ($itemOrder->getProductLink() === $this) {
                $itemOrder->setProductLink(null);
            }
        }

        return $this;
    }
}
