<?php

namespace App\Entity;

use App\Entity\Trait\SlugTrait;
use App\Repository\CategoriesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use phpDocumentor\Reflection\Types\Integer;

#[ORM\Entity(repositoryClass: CategoriesRepository::class)]
class Categories
{
    use SlugTrait; // Utilisation d'un trait pour gérer les slugs

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $name = null;

    #[ORM\Column]
    private ?int $categoryOrder;

    #[ORM\ManyToOne(targetEntity: self::class, inversedBy: 'categories')] // Relation ManyToOne avec elle-même pour gérer les catégories parentes
    #[ORM\JoinColumn(onDelete: 'CASCADE')] // Contrainte de suppression en cascade
    private ?self $parent = null;

    #[ORM\OneToMany(mappedBy: 'parent', targetEntity: self::class)] // Relation OneToMany avec les catégories enfants
    private Collection $categories;

    #[ORM\OneToMany(mappedBy: 'categories', targetEntity: Products::class)] // Relation OneToMany avec les produits associés à cette catégorie
    private Collection $products;

    public function __construct()
    {
        $this->categories = new ArrayCollection(); // Initialisation de la collection de catégories enfants
        $this->products = new ArrayCollection(); // Initialisation de la collection de produits
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

    public function getCategoryOrder(): ?int
    {
        return $this->categoryOrder;
    }

    public function setCategoryOrder(int $categoryOrder): self
    {
        $this->categoryOrder = $categoryOrder;

        return $this;
    }

    public function getParent(): ?self
    {
        return $this->parent;
    }

    public function setParent(?self $parent): static
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getCategories(): Collection
    {
        return $this->categories; // Récupération de la collection de catégories enfants
    }

    public function addCategory(self $category): static
    {
        if (!$this->categories->contains($category)) {
            $this->categories->add($category); // Ajout d'une catégorie enfant
            $category->setParent($this); // Définition de cette catégorie comme parente
        }

        return $this;
    }

    public function removeCategory(self $category): static
    {
        if ($this->categories->removeElement($category)) {
            // Suppression d'une catégorie enfant
            // Définition de la relation parente à null si nécessaire
            if ($category->getParent() === $this) {
                $category->setParent(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Products>
     */
    public function getProducts(): Collection
    {
        return $this->products; // Récupération de la collection de produits associés à cette catégorie
    }

    public function addProduct(Products $product): static
    {
        if (!$this->products->contains($product)) {
            $this->products->add($product); // Ajout d'un produit associé
            $product->setCategories($this); // Définition de cette catégorie comme catégorie associée au produit
        }

        return $this;
    }

    public function removeProduct(Products $product): static
    {
        if ($this->products->removeElement($product)) {
            // Suppression d'un produit associé
            // Définition de la relation à null si nécessaire
            if ($product->getCategories() === $this) {
                $product->setCategories(null);
            }
        }

        return $this;
    }
}
