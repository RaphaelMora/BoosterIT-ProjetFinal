<?php

namespace App\Security\Voter;

use App\Entity\Products;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class ProductsVoter extends Voter
{
    const EDIT = 'PRODUCT_EDIT';
    const DELETE = 'PRODUCT_DELETE';
    public function __construct(private Security $security)
    {
    }
    protected function supports(string $attribute, $product): bool
    {
        // Vérifie si l'attribut est l'un des attributs pris en charge (self::EDIT ou self::DELETE)
        if (!in_array($attribute, [self::EDIT, self::DELETE])) {
            return false; // Si ce n'est pas le cas, la méthode retourne false
        }
        // Vérifie si l'objet passé en paramètre est une instance de la classe Products
        if (!$product instanceof Products) {
            return false; // Si ce n'est pas le cas, la méthode retourne false
        }
        // Si toutes les conditions sont remplies, la méthode retourne true
        return true;
    }
    protected function voteOnAttribute($attribute, $product, TokenInterface $token): bool
    {
        // Récupère l'utilisateur à partir du jeton d'accès
        $user = $token->getUser();
        // Vérifie si l'utilisateur n'est pas une instance de UserInterface
        if (!$user instanceof UserInterface) {
            return false; // Si ce n'est pas le cas, la méthode retourne false
        }
        // Vérifie si l'utilisateur a le rôle "ROLE_ADMIN"
        if ($this->security->isGranted('ROLE_ADMIN')) {
            return true; // Si l'utilisateur est un administrateur, la méthode retourne true
        }
        // Utilise une structure de commutation pour gérer différents attributs (self::EDIT et self::DELETE)
        switch ($attribute) {
            case self::EDIT:
                return $this->canEdit(); // Appelle une méthode pour vérifier si l'utilisateur peut éditer
                break;
            case self::DELETE:
                return $this->canDelete(); // Appelle une méthode pour vérifier si l'utilisateur peut supprimer
                break;
        }
    }
    private function canEdit()
    {
        return $this->security->isGranted('ROLE_ADMIN');
    }
    private function canDelete()
    {
        return $this->security->isGranted('ROLE_SUPER_ADMIN');
    }
}
