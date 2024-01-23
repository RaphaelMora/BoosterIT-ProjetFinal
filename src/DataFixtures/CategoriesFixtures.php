<?php

namespace App\DataFixtures;

use App\Entity\Categories;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\String\Slugger\SluggerInterface;;

class CategoriesFixtures extends Fixture
{
    private $counter = 1;
    public function __construct(private SluggerInterface $slugger)
    {
    }

    public function load(ObjectManager $manager): void
    {
        // Crée une catégorie parente "Produit frais"
        $parent = $this->createCategory('Produit frais', null, $manager);

        // Crée les catégories "Fruits" et "Legumes" en tant que sous-catégories de "Produit frais"
        $this->createCategory('Fruits', $parent, $manager);
        $this->createCategory('Legumes', $parent, $manager);

        // Enregistre les modifications dans la base de données
//        $manager->flush();
    }

    public function createCategory(string $name, Categories $parent = null, ObjectManager $manager)
    {
        // Crée une nouvelle catégorie
        $category = new Categories();

        // Définit le nom de la catégorie
        $category->setName($name);

        // Génère un slug à partir du nom de la catégorie et le met en minuscules
        $category->setSlug($this->slugger->slug($category->getName())->lower());

        // Définit la catégorie parente si elle existe
        $category->setParent($parent);

        // Persiste la catégorie pour qu'elle soit enregistrée dans la base de données
        $manager->persist($category);

        // Ajoute une référence à la catégorie avec un nom unique
        $this->addReference('cat-' . $this->counter, $category);
        $this->counter++;

        // Retourne la catégorie créée
        return $category;
    }

}
