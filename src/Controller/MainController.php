<?php

namespace App\Controller;

use App\Entity\Products;
use App\Form\SearchType;
use App\Model\SearchData;
use App\Repository\CategoriesRepository;
use App\Repository\ProductsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    #[Route('/', name: 'app_main')]
    public function index(Request $request, CategoriesRepository $categoriesRepository, ProductsRepository $productsRepository): Response
    {
        // Crée une nouvelle instance de SearchData pour stocker les données de recherche
        $searchData = new SearchData();
        // Crée un formulaire de recherche en utilisant SearchType et SearchData
        $form = $this->createForm(SearchType::class, $searchData);
        // Traite la requête HTTP et l'associe au formulaire
        $form->handleRequest($request);
        // Vérifie si le formulaire est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {
            // Définit la page pour la pagination en récupérant le paramètre 'page' de la requête
            $searchData->page = $request->query->getInt('page', 1);
            // Trouve les produits correspondant aux termes de recherche
            $product = $productsRepository->findBySearchTerm($searchData);
            return $this->render(
                '_fix/_results.html.twig',
                [
                    'form' => $form,
                    'products' => $product
                ]
            );
        }
        return $this->render('main/main.html.twig', [
            'form' => $form->createView(),
            'categories' => $categoriesRepository->findBy([], ['categoryOrder' => 'asc']),
        ]);
    }
}
