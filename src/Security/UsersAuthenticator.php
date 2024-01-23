<?php

namespace App\Security;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\RememberMeBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\SecurityRequestAttributes;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

class UsersAuthenticator extends AbstractLoginFormAuthenticator
{
    use TargetPathTrait;
    public const LOGIN_ROUTE = 'app_login';
    public function __construct(private UrlGeneratorInterface $urlGenerator)
    {
    }
    public function authenticate(Request $request): Passport
    {
        // Récupère l'e-mail à partir des données du formulaire de la requête
        $email = $request->request->get('email', '');
        // Enregistre l'e-mail dans la session pour le réutiliser ultérieurement
        $request->getSession()->set(SecurityRequestAttributes::LAST_USERNAME, $email);
        // Crée un objet Passport contenant les informations d'authentification
        return new Passport(
            // Utilise un UserBadge pour identifier l'utilisateur par son e-mail
            new UserBadge($email),
            // Utilise PasswordCredentials pour obtenir le mot de passe à partir des données du formulaire
            new PasswordCredentials($request->request->get('password', '')),
            // Ajoute des badges de sécurité supplémentaires
            [
                // Utilise un CsrfTokenBadge pour vérifier le jeton CSRF
                new CsrfTokenBadge('authenticate', $request->request->get('_csrf_token')),
                // Ajoute un RememberMeBadge pour activer la fonction "Se souvenir de moi"
                new RememberMeBadge(),
            ]
        );
    }
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        // Vérifie s'il existe une URL cible dans la session et redirige vers cette URL si elle existe
        if ($targetPath = $this->getTargetPath($request->getSession(), $firewallName)) {
            return new RedirectResponse($targetPath);
        }
        // Si aucune URL cible n'est définie, redirige vers une URL par défaut (par exemple, la page principale)
        return new RedirectResponse($this->urlGenerator->generate('app_main'));
    }
    protected function getLoginUrl(Request $request): string
    {
        // Génère l'URL de la page de connexion (utilisée pour les redirections en cas d'échec de l'authentification)
        return $this->urlGenerator->generate(self::LOGIN_ROUTE);
    }
}
