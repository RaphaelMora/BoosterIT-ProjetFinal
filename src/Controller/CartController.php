<?php

namespace App\Controller;

use App\Entity\Products;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\ProductsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/cart', name: 'app_cart_')]
class CartController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(SessionInterface $session, ProductsRepository $repository)
    {
        // Récupère le panier de la session, ou initialise un nouveau si inexistant
        $panier = $session->get('panier', []);
        $data = [];
        $total = 0;
        // Parcourt chaque élément du panier pour récupérer les détails du produit et calculer le total
        foreach ($panier as $id => $quantity) {
            $product = $repository->find($id);
            $data[] = [
                'product' => $product,
                'quantity' => $quantity
            ];
            $total += $product->getPrice() * $quantity;
        }
        return $this->render('cart/index.html.twig', [
            'data' => $data,
            'total' => $total,
        ]);
    }

    #[Route('/add/{id}', name: 'add')]
    public function add(Products $products, SessionInterface $session)
    {
        // Obtient l'ID du produit
        $id = $products->getId();
        // Récupère le panier depuis la session
        $panier = $session->get('panier', []);
        // Ajoute ou incrémente le produit dans le panier
        if (empty($panier[$id])) {
            $panier[$id] = 1;
        } else {
            $panier[$id]++;
        }
        // Enregistre le panier mis à jour dans la session
        $session->set('panier', $panier);

        return $this->redirectToRoute('app_cart_index');
    }

    #[Route('/supprimer/{id}', name: 'remove')]
    public function remove(Products $products, SessionInterface $session)
    {
        // Obtient l'ID du produit
        $id = $products->getId();
        // Récupère le panier depuis la session
        $panier = $session->get('panier', []);
        // Diminue la quantité du produit dans le panier ou le supprime si sa quantité est 1
        if (!empty($panier[$id])) {
            if ($panier[$id] > 1) {
                $panier[$id]--;
            } else {
                unset($panier[$id]);
            }
        }
        // Enregistre le panier mis à jour dans la session
        $session->set('panier', $panier);

        return $this->redirectToRoute('app_cart_index');
    }

    #[Route('/enlever/{id}', name: 'delete')]
    public function delete(Products $products, SessionInterface $session)
    {
        // Obtient l'ID du produit
        $id = $products->getId();
        // Récupère le panier depuis la session
        $panier = $session->get('panier', []);
        // Supprime complètement le produit du panier
        if (!empty($panier[$id])) {
            unset($panier[$id]);
        }
        // Enregistre le panier mis à jour dans la session
        $session->set('panier', $panier);

        return $this->redirectToRoute('app_cart_index');
    }

    #[Route('/vider', name: 'empty')]
    public function empty(SessionInterface $session)
    {
        // Supprime le panier de la session
        $session->remove('panier');

        return $this->redirectToRoute('app_cart_index');
    }
}
