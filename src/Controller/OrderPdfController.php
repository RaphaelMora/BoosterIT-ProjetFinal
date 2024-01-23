<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\PdfService;
use App\Repository\OrdersRepository;
use Symfony\Component\HttpFoundation\Response;

class OrderPdfController extends AbstractController
{
    #[Route('/pdf/{id}', name: 'app_pdf')]
    public function generatePdf(int $id, PdfService $pdfService, OrdersRepository $ordersRepository): Response
    {
        // Récupération de la commande par son ID
        $order = $ordersRepository->find($id);
        // Vérification si la commande existe
        if (!$order) {
            throw $this->createNotFoundException('Aucune commande correspondante trouvée.');
        }
        // Préparation des données pour le rendu
        $orderDetails = [];
        foreach ($order->getOrdersDetails() as $detail) {
            $orderDetails[] = [
                'product' => $detail->getProducts()->getName(),
                'quantity' => $detail->getQuantity(),
                'price' => $detail->getPrice(),
            ];
        }
        // Création du HTML pour le PDF
        $html = $this->renderView('_fix/_pdf.html.twig', [
            'order' => $order,
            'orderDetails' => $orderDetails,
            'date' => $order->getCreatedAt(),
            'reference' => $order->getReference(),
        ]);
        // Génération du fichier PDF
        return new Response($pdfService->showPdfFile($html), 200, [
            'Content-Type' => 'application/pdf',
        ]);
    }
}
