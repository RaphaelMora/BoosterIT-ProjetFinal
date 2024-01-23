<?php

namespace App\Entity;

use App\Entity\Trait\CreatedAtTrait;
use App\Repository\OrdersRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrdersRepository::class)]
class Orders
{
    use CreatedAtTrait; // Utilisation d'un trait pour la gestion de la date de création
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 20, unique: true)]
    private ?string $reference = null;

    #[ORM\Column(length: 255)]
    private ?string $status;
    #[ORM\ManyToOne(inversedBy: 'orders')] // Relation ManyToOne avec la classe Users, en sens inverse de la propriété 'orders'
    #[ORM\JoinColumn(nullable: false)] // Contrainte pour indiquer que la relation ne peut pas être nulle
    private ?Users $users = null; // Utilisateur associé à la commande

    #[ORM\OneToMany(mappedBy: 'orders', targetEntity: OrdersDetails::class, orphanRemoval: true, cascade: ['persist'])]
    // Relation OneToMany avec la classe OrdersDetails, gestion des détails de la commande
    private Collection $ordersDetails;

    public function __construct()
    {
        $this->ordersDetails = new ArrayCollection(); // Initialisation de la collection des détails de la commande
        $this->created_at = new \DateTimeImmutable(); // Initialisation de la date de création
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getReference(): ?string
    {
        return $this->reference;
    }

    public function setReference(string $reference): static
    {
        $this->reference = $reference;

        return $this;
    }

    public function setStatus(?string $status): void
    {
        $this->status = $status;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }


    public function getUsers(): ?Users
    {
        return $this->users;
    }

    public function setUsers(?Users $users): static
    {
        $this->users = $users;

        return $this;
    }

    /**
     * @return Collection<int, OrdersDetails>
     */
    public function getOrdersDetails(): Collection
    {
        return $this->ordersDetails; // Récupération de la collection des détails de la commande
    }

    public function addOrdersDetail(OrdersDetails $ordersDetail): static
    {
        if (!$this->ordersDetails->contains($ordersDetail)) {
            $this->ordersDetails->add($ordersDetail);
            $ordersDetail->setOrders($this);
        }

        return $this;
    }

    public function removeOrdersDetail(OrdersDetails $ordersDetail): static
    {
        if ($this->ordersDetails->removeElement($ordersDetail)) {
            if ($ordersDetail->getOrders() === $this) {
                $ordersDetail->setOrders(null);
            }
        }

        return $this;
    }
}
