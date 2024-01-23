<?php

namespace App\Controller\Admin;

use App\Entity\Images;
use App\Entity\Products;
use App\Form\ProductsFormType;
use App\Repository\CategoriesRepository;
use App\Repository\ProductsRepository;
use App\Service\PictureService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/admin/produits', name: 'app_admin_products_')]
class ProductsController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(ProductsRepository $repository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $products = $repository->findAll();
        return $this->render('admin/products/index.html.twig', [
            'products' => $products,
        ]);
    }
    #[Route('/tous', name: 'all')]
    public function allProducts(
        Request $request,
        ProductsRepository $productsRepository,
        CategoriesRepository $categoriesRepository
    ): Response {
        // Récupère tous les noms de produits uniques
        $productNames = $productsRepository->findAllProductNames();
        // Récupère l'ID de la catégorie et les noms de produits sélectionnés
        $selectedCategoryId = $request->query->get('id');
        // Récupère l'ID de la catégorie et les noms de produits sélectionnés
        $selectedCategoryId = $request->query->get('id');
        $queryData = $request->query->all();
        $selectedProductNames = $queryData['productNames'] ?? [];
        // On vérifie que $selectedProductNames est un tableau
        if (!is_array($selectedProductNames)) {
            $selectedProductNames = [];
        }
        // Vérifie si des noms de produits ont été sélectionnés
        if (!empty($selectedProductNames)) {
            // Filtre les produits par les noms sélectionnés
            $products = $productsRepository->findByProductNames($selectedProductNames);
        } elseif ($selectedCategoryId) {
            // Recherche la catégorie spécifique par son ID
            $category = $categoriesRepository->find($selectedCategoryId);
            if ($category) {
                // Filtre les produits par catégorie
                $products = $productsRepository->findBy(['categories' => $category]);
            }
        } else {
            // Si aucun filtre n'est sélectionné, récupère tous les produits
            $products = $productsRepository->findAll();
        }
        // Récupère toutes les catégories pour les options de filtrage
        $categories = $categoriesRepository->findAll();
        // Retourne la réponse en rendant le template avec les produits, les catégories et les noms de produits sélectionnés
        return $this->render('admin/products/allProducts.html.twig', [
            'products' => $products,
            'categories' => $categories,
            'selectedCategoryId' => $selectedCategoryId,
            'selectedProductNames' => $selectedProductNames,
            'productNames' => $productNames
        ]);
    }
    #[Route('/ajouter', name: 'add')]
    public function add(
        Request $request,
        EntityManagerInterface $manager,
        SluggerInterface $slugger,
        PictureService $pictureService
    ): Response {
        // Vérifie si l'utilisateur actuel a le rôle 'ROLE_ADMIN', et restreint l'accès s'il ne l'a pas
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        // Crée une nouvelle instance de l'entité Produit
        $product = new Products();
        // Crée un formulaire pour l'entité Produit
        $productForm = $this->createForm(ProductsFormType::class, $product);
        // Gère la requête pour le formulaire
        $productForm->handleRequest($request);
        // Vérifie si le formulaire est soumis et valide
        if ($productForm->isSubmitted() && $productForm->isValid()) {
            // Traite les images envoyées avec le formulaire, s'il y en a
            $this->handleImages($productForm, $product, $pictureService);
            // Génère un slug à partir du nom du produit
            $slug = $slugger->slug($product->getName());
            // Définit le slug pour le produit
            $product->setSlug($slug);
            // Enregistre le produit dans la base de données
            $manager->persist($product);
            // Applique les changements dans la base de données
            $manager->flush();
            // Ajoute un message flash de succès
            $this->addFlash('success', "Ce produit a été ajouté avec succès");
            // Redirige vers l'index des produits dans l'administration
            return $this->redirectToRoute('app_admin_products_index');
        }
        // Renvoie la vue pour ajouter un produit avec le formulaire
        return $this->render('admin/products/add.html.twig', [
            'productForm' => $productForm->createView()
        ]);
    }
    #[Route('/modifier/{id}', name: 'edit')]
    public function edit(
        Products $product,
        Request $request,
        EntityManagerInterface $manager,
        SluggerInterface $slugger,
        PictureService $pictureService
    ): Response {
        // Vérifie si l'utilisateur actuel a le rôle 'ROLE_ADMIN', et restreint l'accès s'il ne l'a pas
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        // Crée un formulaire pour l'entité Produit, pré-rempli avec les données du produit existant
        $productForm = $this->createForm(ProductsFormType::class, $product);
        // Gère la requête pour le formulaire
        $productForm->handleRequest($request);
        // Vérifie si le formulaire est soumis et valide
        if ($productForm->isSubmitted() && $productForm->isValid()) {
            // Traite les images envoyées avec le formulaire, s'il y en a
            $this->handleImages($productForm, $product, $pictureService);
            // Génère un slug à partir du nom du produit
            $slug = $slugger->slug($product->getName());
            // Définit le slug pour le produit
            $product->setSlug($slug);
            // Enregistre les modifications du produit dans la base de données
            $manager->persist($product);
            // Applique les changements dans la base de données
            $manager->flush();
            // Ajoute un message flash de succès pour informer que la modification a été réussie
            $this->addFlash('success', "Ce produit a été modifié avec succès");
            // Redirige vers l'index des produits dans l'administration
            return $this->redirectToRoute('app_admin_products_index');
        }
        // Renvoie la vue pour éditer un produit avec le formulaire et les données du produit
        return $this->render('admin/products/edit.html.twig', [
            'productForm' => $productForm->createView(),
            'product' =>  $product
        ]);
    }
    // Déclare une route pour la suppression d'un produit avec un paramètre 'id' dans l'URL
    #[Route('/supprimer/{id}', name: 'delete')]
    public function delete(Products $products): Response
    {
        // Vérifie si l'utilisateur actuel a le rôle 'ROLE_ADMIN', et restreint l'accès s'il ne l'a pas
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        // Renvoie la vue de la liste des produits
        return $this->render('admin/products/productsList.html.twig', [
            'controller_name' => 'UsersController',
        ]);
    }
    #[Route('/supprimer/image/{id}', name: 'delete_image', methods: ['DELETE'])]
    public function deleteImage(
        Images $images,
        Request $request,
        EntityManagerInterface $manager,
        PictureService $pictureService
    ): JsonResponse {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        // Décode le contenu de la requête en JSON
        $data = json_decode($request->getContent(), true);
        // Vérifie la validité du token CSRF
        if ($this->isCsrfTokenValid('delete' . $images->getId(), $data['_token'])) {
            // Récupère le nom de l'image
            $nom = $images->getName();
            // Tente de supprimer l'image en utilisant PictureService
            if ($pictureService->delete($nom, 'products', 300, 300)) {
                // Supprime l'image de la base de données
                $manager->remove($images);
                $manager->flush();
                // Retourne une réponse JSON indiquant le succès
                return new JsonResponse(['success' => true], 200);
                return $this->render('admin/products/productsList.html.twig');
            }
            // Retourne une erreur si la suppression échoue
            return new JsonResponse(['error' => 'Erreur lors de la suppression'], 400);
        }
        // Retourne une erreur si le token CSRF est invalide
        return new JsonResponse(['error' => 'Token invalide'], 400);
    }
    // Méthode privée pour gérer les images
    // Méthode privée pour gérer les images associées à un produit
    private function handleImages($productForm, $product, $pictureService)
    {
        // Récupère les images depuis le formulaire
        $images = $productForm->get('images')->getData();
        // Vérifie s'il y a des images à traiter
        if ($images) {
            // Parcourt chaque image
            foreach ($images as $image) {
                try {
                    // Définit le dossier de destination pour les images
                    $folder = 'products';
                    // Utilise le service PictureService pour ajouter l'image
                    // et redimensionner à 300x300 pixels
                    $fichier = $pictureService->add($image, $folder, 300, 300);
                    // Crée une nouvelle instance de l'entité Images
                    $img = new Images();
                    // Définit le nom de fichier pour l'image
                    $img->setName($fichier);
                    // Ajoute l'image à l'entité Produit
                    $product->addImage($img);
                } catch (\Exception $e) {
                    // Si une erreur survient, vous pouvez gérer l'erreur ici
                    // Par exemple, enregistrer un message d'erreur dans un log ou afficher un message à l'utilisateur
                }
            }
        }
    }
}
