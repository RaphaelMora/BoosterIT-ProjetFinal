<?php

namespace App\DataFixtures;

use App\Entity\Users;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\String\Slugger\SluggerInterface;
use Faker;;

class UsersFixtures extends Fixture
{
    public function __construct(
        private UserPasswordHasherInterface $passwordHasher,
        private SluggerInterface $slugger
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        // Crée un nouvel utilisateur administrateur
        $admin = new Users();
        $admin->setEmail('admin@gmail.com');
        $admin->setLastname('Mora-Bouty');
        $admin->setFirstname('Aaron');
        $admin->setAdress('140 rue Emile Combes');
        $admin->setZipcode('33000');
        $admin->setCity('BORDEAUX');

        // Hache le mot de passe 'admin' et le stocke dans l'objet utilisateur
        $admin->setPassword(
            $this->passwordHasher->hashPassword($admin, 'admin')
        );

        // Définit le rôle de l'administrateur comme 'ROLE_ADMIN'
        $admin->setRoles(['ROLE_ADMIN']);

        // Persiste l'objet utilisateur administrateur pour qu'il soit enregistré dans la base de données
        $manager->persist($admin);

        // Crée des utilisateurs factices (5 au total)
        $faker = Faker\Factory::create('fr_FR');
        for ($user = 1; $user <= 5; $user++) {
            $users = new Users();

            // Définit des données aléatoires pour chaque utilisateur factice
            $users->setEmail($faker->email);
            $users->setLastname($faker->lastName);
            $users->setFirstname($faker->firstName);
            $users->setAdress($faker->streetAddress);
            $users->setZipcode(str_replace(' ', '', $faker->postcode));
            $users->setCity($faker->city);

            // Hache le mot de passe 'faker' et le stocke dans l'objet utilisateur factice
            $users->setPassword(
                $this->passwordHasher->hashPassword($users, 'faker')
            );

            // Persiste chaque utilisateur factice pour qu'il soit enregistré dans la base de données
//            $manager->persist($users);
        }

//        // Enregistre les modifications dans la base de données
//        $manager->flush();
    }
}
