<?php

namespace App\DataFixtures;

use App\Entity\Images;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker;;

class ImagesFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        // Crée une instance de Faker pour générer des données aléatoires en français
        $faker = Faker\Factory::create('fr_FR');

        // Boucle pour créer 5 images fictives
        for ($img = 1; $img <= 5; $img++) {
            // Crée une nouvelle instance d'Images
            $image = new Images();

            // Génère un nom d'image aléatoire avec des dimensions 640x480
            $image->setName($faker->image(null, 640, 480));

            // Obtient une référence à un produit aléatoire créé précédemment
            $products = $this->getReference('prod-' . rand(1, 5));

            // Associe l'image au produit
            $image->setProducts($products);

            // Persiste l'objet image pour qu'il soit enregistré dans la base de données
//            $manager->persist($image);
        }

        // Enregistre les modifications dans la base de données
//        $manager->flush();
    }

    public function getDependencies(): array
    {
        // Indique que cette classe de fixtures dépend des fixtures de ProductsFixtures
        return [
            ProductsFixtures::class
        ];
    }

}
