<?php

namespace App\Entity;

use App\Repository\ItemOrderRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ItemOrderRepository::class)]
class ItemOrder
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    private ?int $quantity = null;

    #[ORM\ManyToOne(inversedBy: 'itemOrders')]
    private ?Order $orderLink = null;

    #[ORM\ManyToOne(inversedBy: 'itemOrders')]
    private ?Product $productLink = null;

    public function getId(): ?int
    {
        return $this->id;
    }

   public function  __toString() 
   {
        return $this->id;
   }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(?int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getOrderLink(): ?Order
    {
        return $this->orderLink;
    }

    public function setOrderLink(?Order $orderLink): self
    {
        $this->orderLink = $orderLink;

        return $this;
    }

    public function getProductLink(): ?Product
    {
        return $this->productLink;
    }

    public function setProductLink(?Product $productLink): self
    {
        $this->productLink = $productLink;

        return $this;
    }
}
