<?php

namespace App\Service;

use Dompdf\Dompdf;
use Dompdf\Options;

class PdfService
{
    private $domPdf;

    /**
     * Constructeur de la classe PdfService.
     * Initialise une instance de Dompdf et configure ses options.
     */
    public function __construct()
    {
        // Crée une nouvelle instance de Dompdf
        $this->domPdf = new Dompdf();
        // Définit les options pour Dompdf
        $pdfOptions = new Options();
        // Définit la police par défaut pour les PDF
        $pdfOptions->set('defaultFont', 'Garamond');
        // Applique les options à l'instance Dompdf
        $this->domPdf->setOptions($pdfOptions);
    }

    /**
     * Génère et affiche un fichier PDF à partir du code HTML fourni.
     *
     * @param string $html Le code HTML à transformer en PDF.
     */
    public function showPdfFile($html)
    {
        // Charge le code HTML dans Dompdf
        $this->domPdf->loadHtml($html);
        // Génère le rendu du PDF
        $this->domPdf->render();
        // Envoie le PDF généré au navigateur pour l'affichage
        $this->domPdf->stream("details.pdf", [
            'attachment' => false
        ]);
    }

    /**
     * Génère un fichier PDF en format binaire à partir du code HTML fourni.
     *
     * @param string $html Le code HTML à transformer en PDF.
     * @return string Le contenu du PDF généré en format binaire.
     */
    public function generateBinaryPdf($html)
    {
        // Charge le code HTML dans Dompdf
        $this->domPdf->loadHtml($html);
        // Génère le rendu du PDF
        $this->domPdf->render();
        // Retourne le contenu du PDF en format binaire
        return $this->domPdf->output();
    }
}
