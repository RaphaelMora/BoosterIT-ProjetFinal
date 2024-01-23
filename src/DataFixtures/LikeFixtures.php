<?php

namespace App\DataFixtures;

use App\Repository\ProductsRepository;
use App\Repository\UsersRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Persistence\ObjectManager;

class LikeFixtures extends Fixture implements FixtureInterface
{
    public function __construct(private ProductsRepository $productsRepository,private UsersRepository $usersRepository)
    {
    }

    public function load(ObjectManager $manager): void
    {
        $users = $this->usersRepository->findAll();
        $products = $this->productsRepository->findAll();

        foreach($products as $product) {
        for($i = 0; $i <= 5; $i++){
            $product->addLikes(
                $users[mt_rand(0, count($users) -1)]
            );
            }
        }
        $manager->flush();
    }
}