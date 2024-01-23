<?php

namespace App\Controller;

use App\Entity\Categories;
use App\Repository\CategoriesRepository;
use App\Repository\ProductsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/categories', name: 'app_categories_')]
class CategoriesController extends AbstractController
{
    #[Route('/{slug}', name: 'list')]
    public function list(
        Categories $categories,
        ProductsRepository $repository,
        Request $request
    ): Response {
        // Récupère la valeur du paramètre 'page' de la requête, avec une valeur par défaut de 1
        $page = $request->query->getInt('page', 1);
        // Récupère les produits associés à la catégorie donnée, paginés selon la page demandée
        $products = $repository->findProductsPaginated($page, $categories->getSlug(), 3);
        return $this->render('categories/categoriesList.html.twig', [
            'categories' => $categories,
            'products' => $products
        ]);
    }
}
