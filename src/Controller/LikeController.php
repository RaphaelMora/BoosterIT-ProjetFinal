<?php

namespace App\Controller;

use App\Entity\Products;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LikeController extends AbstractController
{
    #[Route('/like/produit/{id}', name: 'app_like')]
    public function like(Products $products, EntityManagerInterface $manager): Response
    {
        $user = $this->getUser();
        if (!$user) {
            // Redirection ou gestion des utilisateurs non connectés
            return $this->redirectToRoute('app_login');
        }
        if ($products->isLiked($user)) {
            $products->removeLike($user);
            $manager->flush();
            //            $this->addFlash('message', 'Votre dislike a été pris en compte');
            return  $this->json([
                'message' => 'Json test dislike',
                'nbLike' => $products->howManyLikes()
            ]);
        }
        $products->addLikes($user);
        $manager->flush();
        //            $this->addFlash('success', 'Votre like a été pris en compte');
        return $this->json([
            'message' => 'Json test like',
            'nbLike' => $products->howManyLikes()
        ]);
    }
}
