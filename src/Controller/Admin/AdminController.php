<?php

namespace App\Controller\Admin;

use App\Repository\OrdersDetailsRepository;
use App\Repository\OrdersRepository;
use App\Repository\ProductsRepository;
use App\Repository\UsersRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin', name: 'app_admin_')]
class AdminController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(
        UsersRepository $usersRepository,
        ProductsRepository $productsRepository,
        OrdersDetailsRepository $ordersDetailsRepository
    ): Response {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        // Compte le nombre total d'utilisateurs
        $userCount = $usersRepository->count([]);
        $verifiedUserCount = $usersRepository->countVerifiedUsers();
        // Compte le nombre total de produits différents
        $productCount = $productsRepository->count([]);
        // Récupère tous les produits de la base de données
        $products = $productsRepository->findAll();
        // Récupère tous les détails des commandes de la base de données
        $ordered = $ordersDetailsRepository->findAll();
        // Initialisation du total de la quantité de produits en stock
        $totalProductQuantity = 0;
        // Initialisation du total de la quantité de produits commandés
        $totalOrderedQuantity = 0;
        // Tableau pour stocker la quantité vendue de chaque produit
        $productSoldQuantities = [];
        // Récupère encore une fois tous les détails des commandes (ligne potentiellement redondante)
        $ordered = $ordersDetailsRepository->findAll();
        // Calcule la quantité totale de produits en stock
        foreach ($products as $product) {
            $totalProductQuantity += $product->getStock();
        }
        // Calcule la quantité totale de produits vendus
        foreach ($ordered as $order) {
            $totalOrderedQuantity += $order->getQuantity();
        }
        // Calcule la quantité vendue pour chaque produit de façon simplifiée
        foreach ($ordered as $orderDetail) {
            $productId = $orderDetail->getProducts()->getId();
            $productSoldQuantities[$productId] = ($productSoldQuantities[$productId] ?? 0) + $orderDetail->getQuantity();
        }
        // Trouve l'ID du produit le plus vendu
        $maxSoldProductId = array_keys($productSoldQuantities, max($productSoldQuantities))[0];
        // Récupère les détails du produit le plus vendu
        $mostSoldProduct = $productsRepository->find($maxSoldProductId);
        return $this->render('admin/index.html.twig', [
            'userCount' => $userCount,
            'verifiedUserCount' => $verifiedUserCount,
            'productCount' => $productCount,
            'productQuantity' => $totalProductQuantity,
            'totalOrderedQuantity' => $totalOrderedQuantity,
            'mostSoldProduct' => $mostSoldProduct,
        ]);
    }
    #[Route('/toutes-les-commandes', name: 'all_orders')]
    public function allOrders(OrdersRepository $ordersRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        // Récupère toutes les commandes
        $allOrders = $ordersRepository->findAll();

        return $this->render('admin/orders/allOrders.html.twig', [
            'allOrders' => $allOrders,
        ]);
    }
}
