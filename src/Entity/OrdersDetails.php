<?php

namespace App\Entity;

use App\Repository\OrdersDetailsRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrdersDetailsRepository::class)]
class OrdersDetails
{

    #[ORM\Column]
    private ?int $quantity = null; // Quantité de produits dans le détail de la commande

    #[ORM\Column] // Colonne de la base de données
    private ?int $price = null; // Prix du produit dans le détail de la commande

    #[ORM\Id]
    #[ORM\ManyToOne(inversedBy: 'ordersDetails')] // Relation ManyToOne avec la classe Orders, en sens inverse de la propriété 'ordersDetails'
    #[ORM\JoinColumn(nullable: false)] // Contrainte pour indiquer que la relation ne peut pas être nulle
    private ?Orders $orders = null; // Commande associée au détail

    #[ORM\Id]
    #[ORM\ManyToOne(inversedBy: 'ordersDetails')] // Relation ManyToOne avec la classe Products, en sens inverse de la propriété 'ordersDetails'
    #[ORM\JoinColumn(nullable: false)] // Contrainte pour indiquer que la relation ne peut pas être nulle
    private ?Products $products = null; // Produit associé au détail de la commande

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): static
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getOrders(): ?Orders
    {
        return $this->orders;
    }

    public function setOrders(?Orders $orders): static
    {
        $this->orders = $orders;

        return $this;
    }

    public function getProducts(): ?Products
    {
        return $this->products;
    }

    public function setProducts(?Products $products): static
    {
        $this->products = $products;

        return $this;
    }
}
