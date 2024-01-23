<?php

namespace App\Controller\Admin;

use App\Entity\Categories;
use App\Form\CategoriesFormType;
use App\Repository\CategoriesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/admin/categories', name: 'app_admin_categories_')]
class CategoriesController extends AbstractController
{
    public function __construct(private  EntityManagerInterface $manager)
    {
    }
    #[Route('/ajouter', name: 'add')]
    public function add(
        Request $request,
        EntityManagerInterface $manager,
        SluggerInterface $slugger,
    ): Response {
        // Seuls les utilisateurs avec le rôle 'ROLE_ADMIN' peuvent accéder à cette fonction
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        // Création d'une nouvelle instance de Categories
        $categories = new Categories();
        // Création d'un formulaire pour Categories
        $categoriesForm = $this->createForm(CategoriesFormType::class, $categories);
        // Associe les données de la requête au formulaire
        $categoriesForm->handleRequest($request);
        // Vérifie si le formulaire est soumis et valide
        if ($categoriesForm->isSubmitted() && $categoriesForm->isValid()) {
            // Génération d'un slug à partir du nom de la catégorie
            $slug = strtolower($slugger->slug($categories->getName()));
            // Récupération de l'ordre maximal des catégories existantes
            $lastOrder = $this->manager->getRepository(Categories::class)->findMaxCategoryOrder();
            // Attribution du nouvel ordre de catégorie
            $categories->setCategoryOrder($lastOrder + 1);
            // Définition du slug pour la catégorie
            $categories->setSlug($slug);
            // Enregistre l'objet categories dans la base de données
            $manager->persist($categories);
            // Applique les changements dans la base de données
            $manager->flush();
            // Affiche un message de succès
            $this->addFlash('success', "Cette catégorie a été ajouté avec succès");
            // Redirige vers la route 'app_admin_categories_add'
            return $this->redirectToRoute('app_admin_categories_add');
        }
        return $this->render('admin/categories/add.html.twig', [
            'categoriesForm' => $categoriesForm
        ]);
    }

    #[Route('/', name: 'index')]
    public function index(CategoriesRepository $repository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        // Récupère toutes les catégories triées par 'categoryOrder' ascendant
        $categories = $repository->findBy([], ['categoryOrder' => 'asc']);
        return $this->render('admin/categories/index.html.twig', [
            'categories' => $categories
        ]);
    }
}
