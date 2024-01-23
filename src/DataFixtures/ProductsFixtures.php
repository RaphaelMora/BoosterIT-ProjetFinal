<?php

namespace App\DataFixtures;

use App\Entity\Products;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\String\Slugger\SluggerInterface;
use Faker;;

class ProductsFixtures extends Fixture
{
    public function __construct(private SluggerInterface $slugger)
    {
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create('fr_FR');

        // Boucle pour créer un produit factice (Banane)
        for ($product = 1; $product <= 5; $product++) {
            // Crée une nouvelle instance de Products
            $products = new Products();

            // Définit le nom du produit comme "Banane"
            $products->setName('Banane');

            // Définit une description du produit (Origine Guadeloupe)
            $products->setDescription('Origine Guadeloupe');

            // Génère un slug à partir du nom du produit et le met en minuscules
            $products->setSlug($this->slugger->slug($products->getName())->lower());

            // Génère un prix aléatoire entre 0 et 10
            $products->setPrice($faker->numberBetween(0, 10));

            // Génère une quantité en stock aléatoire entre 0 et 20
            $products->setStock($faker->numberBetween(0, 20));

            // Obtient une référence à une catégorie spécifique (catégorie 2)
            $category = $this->getReference('cat-' . (2));

            // Associe la catégorie au produit
            $products->setCategories($category);

            // Persiste l'objet produit pour qu'il soit enregistré dans la base de données
//            $manager->persist($products);

            // Ajoute une référence au produit créé
            $this->addReference('prod-' . $product, $products);
        }

        // Enregistre les modifications dans la base de données
//        $manager->flush();
    }

}
