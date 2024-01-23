<?php

namespace App\Entity;

use App\Entity\Trait\CreatedAtTrait;
use App\Entity\Trait\SlugTrait;
use App\Repository\ProductsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ProductsRepository::class)]
class Products
{
    use CreatedAtTrait; // Utilisation d'un trait pour ajouter des fonctionnalités de gestion de la date de création
    use SlugTrait; // Utilisation d'un trait pour ajouter des fonctionnalités de gestion de slug
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column]
    private ?int $price = null;

    #[ORM\Column]
    private ?int $stock = null;

    #[ORM\ManyToOne(inversedBy: 'products')] // Relation ManyToOne avec la classe Categories, en sens inverse de la propriété 'products'
    #[ORM\JoinColumn(nullable: false)] // Contrainte pour indiquer que la relation ne peut pas être nulle
    private ?Categories $categories = null; // Catégorie à laquelle le produit appartient

    #[ORM\OneToMany(mappedBy: 'products', targetEntity: Images::class, orphanRemoval: true, cascade: ['persist'])]
    // Relation OneToMany avec la classe Images, gestion des images liées au produit
    private Collection $images;

    #[ORM\OneToMany(mappedBy: 'products', targetEntity: OrdersDetails::class)]
    // Relation OneToMany avec la classe OrdersDetails, gestion des détails de commandes liées au produit
    private Collection $ordersDetails;

    #[ORM\ManyToMany(targetEntity: Users::class)]
    #[ORM\JoinTable('user_product_like')]
    private Collection $likes;

    public function __construct()
    {
        $this->images = new ArrayCollection(); // Initialisation de la collection d'images
        $this->ordersDetails = new ArrayCollection(); // Initialisation de la collection de détails de commandes
        $this->created_at = new \DateTimeImmutable(); // Initialisation de la date de création du produit
        $this->likes = new ArrayCollection();
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

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

    public function getStock(): ?int
    {
        return $this->stock;
    }

    public function setStock(int $stock): static
    {
        $this->stock = $stock;

        return $this;
    }

    public function getCategories(): ?Categories
    {
        return $this->categories;
    }

    public function setCategories(?Categories $categories): static
    {
        $this->categories = $categories;

        return $this;
    }

    /**
     * @return Collection<int, Images>
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    public function addImage(Images $image): static
    {
        if (!$this->images->contains($image)) {
            $this->images->add($image);
            $image->setProducts($this);
        }

        return $this;
    }

    public function removeImage(Images $image): static
    {
        if ($this->images->removeElement($image)) {
            // set the owning side to null (unless already changed)
            if ($image->getProducts() === $this) {
                $image->setProducts(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, OrdersDetails>
     */
    public function getOrdersDetails(): Collection
    {
        return $this->ordersDetails;
    }

    public function addOrdersDetail(OrdersDetails $ordersDetail): static
    {
        if (!$this->ordersDetails->contains($ordersDetail)) {
            $this->ordersDetails->add($ordersDetail);
            $ordersDetail->setProducts($this);
        }

        return $this;
    }

    public function removeOrdersDetail(OrdersDetails $ordersDetail): static
    {
        if ($this->ordersDetails->removeElement($ordersDetail)) {
            // set the owning side to null (unless already changed)
            if ($ordersDetail->getProducts() === $this) {
                $ordersDetail->setProducts(null);
            }
        }

        return $this;
    }
    public function getLikes(): Collection
    {
        return $this->likes;
    }
    public function addLikes(Users $like): self
    {
        if (!$this->likes->contains($like)) {
            $this->likes[] = $like;
        }
        return $this;
    }
    public function removeLike(Users $like): self
    {
        $this->likes->removeElement($like);
        return $this;
    }
    public function isLiked(Users $users): bool
    {
        return $this->likes->contains($users);
    }
    public function howManyLikes(): int
    {
        return count($this->likes);
    }
}
