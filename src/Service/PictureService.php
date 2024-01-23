<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class PictureService
{
    private $targetDirectory;
    // Constructeur de la classe
    public function __construct(string $targetDirectory)
    {
        // Définit le répertoire cible où les images seront enregistrées
        $this->targetDirectory = $targetDirectory;
    }
    /**
     * Ajoute une image dans un dossier spécifique et redimensionne l'image
     *
     * @param UploadedFile $file   L'image téléchargée
     * @param string $folder       Le dossier où enregistrer l'image
     * @param int $width           La largeur désirée pour l'image (non utilisé actuellement)
     * @param int $height          La hauteur désirée pour l'image (non utilisé actuellement)
     * @return string              Le nom du fichier de l'image
     */
    public function add(UploadedFile $file, $folder, $width, $height)
    {
        // Crée un nom de fichier unique
        $fileName = md5(uniqid()) . '.' . $file->guessExtension();
        // Déplace le fichier dans le répertoire cible
        $file->move($this->getTargetDirectory() . '/' . $folder, $fileName);
        // Retourne le nom du fichier
        return $fileName;
    }
    /**
     * Supprime une image du dossier spécifique
     * 
     * @param string $fileName     Le nom du fichier à supprimer
     * @param string $folder       Le dossier contenant le fichier
     * @param int $width           Non utilisé dans cette méthode
     * @param int $height          Non utilisé dans cette méthode
     * @return bool                Vrai si le fichier a été supprimé, faux sinon
     */
    public function delete($fileName, $folder, $width, $height)
    {
        // Construit le chemin complet du fichier
        $filePath = $this->getTargetDirectory() . '/' . $folder . '/' . $fileName;
        // Vérifie si le fichier existe et le supprime
        if (file_exists($filePath)) {
            unlink($filePath);
            return true;
        }
        // Retourne faux si le fichier n'existe pas
        return false;
    }
    // Méthode pour obtenir le répertoire cible
    private function getTargetDirectory()
    {
        return $this->targetDirectory;
    }
}
