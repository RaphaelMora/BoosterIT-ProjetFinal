<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/profil', name: 'app_profile_')]
class ProfileController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(Security $security): Response
    {
        // Récupère l'utilisateur actuellement connecté
        $user = $security->getUser();
        // Vérifie si un utilisateur est connecté
        if ($user) {
            return $this->render('profile/profile.html.twig', [
                'user' => $user,
            ]);
        }
        // Si aucun utilisateur n'est connecté, redirige vers la page de connexion
        return $this->redirectToRoute('app_login');
    }
    #[Route('/commandes', name: 'orders')]
    public function orders(): Response
    {
        return $this->render('profile/list.html.twig', [
            'controller_name' => 'orders',
        ]);
    }
}
