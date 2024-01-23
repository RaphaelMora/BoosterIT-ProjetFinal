<?php

namespace App\Controller;

use App\Entity\Orders;
use App\Entity\OrdersDetails;
use App\Entity\Users;
use App\Repository\OrdersRepository;
use App\Repository\ProductsRepository;
use App\Repository\UsersRepository;
use App\Service\PdfService;
use App\Service\sendMailService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/commandes', name: 'app_orders_')]
class OrdersController extends AbstractController
{
    #[Route('/ajout', name: 'add')]
    public function add(
        SessionInterface $session,
        ProductsRepository $repository,
        EntityManagerInterface $manager,
        UsersRepository $user,
        sendMailService $mailService
    ): Response {
        // Vérifie si l'utilisateur a le rôle 'ROLE_USER'
        $this->denyAccessUnlessGranted('ROLE_USER');
        // Récupère le panier depuis la session
        $panier = $session->get('panier', []);
        // Vérifie si le panier est vide
        if ($panier === []) {
            $this->addFlash('danger', 'Votre panier est vide');
            return $this->redirectToRoute('app_admin_products_all');
        }
        // Prépare une liste pour les détails de la commande et crée une nouvelle commande
        $orderDetailsList = [];
        $order = new Orders();
        $order->setUsers($this->getUser());
        // Récupère les commandes de l'utilisateur connecté
        $ordersRepository = $manager->getRepository(Orders::class);
        $userOrders = $ordersRepository->findBy(['users' => $this->getUser()]);
        // Crée une référence unique pour la commande
        $orderCount = $manager->getRepository(Orders::class)->count([]);
        $reference = date('Ym') . '-' . str_pad($orderCount + 1, 2, '0', STR_PAD_LEFT);
        $order->setReference($reference);
        // Traite chaque article du panier
        foreach ($panier as $item => $quantity) {
            $orderDetails = new OrdersDetails();
            $product = $repository->find($item);
            // Vérifie la disponibilité du stock
            if ($product->getStock() < $quantity) {
                continue; // Ignore l'article si le stock est insuffisant
            }
            // Configure les détails de l'article de la commande
            $price = $product->getPrice();
            $orderDetails->setProducts($product);
            $orderDetails->setPrice($price);
            $orderDetails->setQuantity($quantity);
            $order->addOrdersDetail($orderDetails);
            // Met à jour le stock du produit
            $newStock = $product->getStock() - $quantity;
            $product->setStock($newStock);
            $manager->persist($product);
            $orderDetailsList[] = $orderDetails;
        }
        // Enregistre la commande dans la base de données
        $manager->persist($order);
        $manager->flush();
        // Vide le panier
        $session->remove('panier');
        // Prépare et envoie un e-mail de confirmation
        $email = $this->getUser()->getEmail();
        $orderDetails = $order->getOrdersDetails();
        $mailService->send(
            'no-reply@epicerie.com',
            $email,
            'Confirmation de votre commande',
            'orderConfirmation',
            [
                'user' => $this->getUser(),
                'orderDetails' => $orderDetails,
                'order' => $order
            ]
        );
        // Affiche un message de succès
        $this->addFlash('success', 'Merci pour votre commande');
        return $this->render('orders/main.html.twig', [
            'orderDetailsLists' => $orderDetailsList,
            'userOrders' => $userOrders,
            'user' => $this->getUser(),
        ]);
    }
    #[Route('/historique', name: 'list')]
    public function list(EntityManagerInterface $entityManager, Request $request): Response
    {
        // Vérifie si l'utilisateur a le rôle 'ROLE_USER'
        $this->denyAccessUnlessGranted('ROLE_USER');
        // Récupère l'utilisateur connecté
        $user = $this->getUser();
        // Obtient le référentiel pour l'entité 'Orders'
        $ordersRepository = $entityManager->getRepository(Orders::class);
        // Récupère les commandes de l'utilisateur
        $userOrders = $ordersRepository->findBy(['users' => $user]);

        return $this->render('orders/main.html.twig', [
            'userOrders' => $userOrders,
        ]);
    }
    #[Route('/changement-status/{id}', name: 'update_status')]
    public function updateOrderStatus(Request $request, Orders $order, EntityManagerInterface $manager, sendMailService $mailService): Response
    {
        // Vérifie si l'utilisateur actuel a le rôle 'ROLE_ADMIN', et restreint l'accès s'il ne l'a pas
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $status = $request->request->get('status');
        $order->setStatus($status);
        $manager->flush();
        // Récupère l'utilisateur connecté
        $user = $this->getUser();
        // Prépare et envoie un e-mail de confirmation de changement de statut
        $email = $user->getEmail();
        $mailService->send(
            'no-reply@epicerie.com',
            $email,
            'Mise à jour de votre commande',
            'orderStatus',
            [
                'user' => $user,
                'order' => $order,
                'status' => $status
            ]
        );
        $this->addFlash('success', 'Statut de la commande mis à jour.');
        return $this->redirectToRoute('app_orders_list');
    }
}
