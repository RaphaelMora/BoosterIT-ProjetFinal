<?php

namespace App\Controller\Admin;

use App\Entity\Users;
use App\Repository\UsersRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/utilisateurs', name: 'app_admin_users_')]
class UsersController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(UsersRepository $repository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $users = $repository->findBy([], ['lastname' => 'asc']);
        return $this->render('admin/users/index.html.twig', [
            'users' => $users,
        ]);
    }
    #[Route('/changement-role/{id}', name: 'update_role')]
    public function updateRole(Request $request, Users $user, EntityManagerInterface $entityManager): Response
    {
        // Vérifie si l'utilisateur actuel a le rôle 'ROLE_SUPER_ADMIN', et restreint l'accès s'il ne l'a pas
        $this->denyAccessUnlessGranted('ROLE_SUPER_ADMIN');
        // Récupère le nouveau rôle depuis la requête
        $newRole = $request->request->get('role');
        // Met à jour le rôle de l'utilisateur
        $user->setRoles([$newRole]);
        // Enregistre les changements dans la base de données
        $entityManager->flush();
        // Ajoute un message flash de succès pour informer que le rôle a été modifié
        $this->addFlash('success', "Le rôle a été modifié avec succès");
        // Redirige vers l'index des utilisateurs dans l'administration
        return $this->redirectToRoute('app_admin_users_index');
    }
    #[Route('/suppression/{id}', name: 'delete')]
    public function deleteUser(Users $user, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        // Suppression de l'utilisateur
        $entityManager->remove($user);
        $entityManager->flush();

        $this->addFlash('success', "L'utilisateur a été supprimé avec succès");

        return $this->redirectToRoute('app_admin_users_index');
    }

}
