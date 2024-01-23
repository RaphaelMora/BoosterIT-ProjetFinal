<?php

namespace App\Controller;

use App\Entity\Products;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/produits', name: 'app_product_')]
class ProductController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(): Response
    {
        return $this->render('product/list.html.twig', []);
    }
    #[Route('/{slug}', name: 'details')]
    public function details(Products $products): Response
    {
        return $this->render('product/details.html.twig', [
            'products' => $products,
        ]);
    }
}
