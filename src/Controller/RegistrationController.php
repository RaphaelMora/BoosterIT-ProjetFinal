<?php

namespace App\Controller;

use App\Entity\Users;
use App\Form\RegistrationFormType;
use App\Repository\UsersRepository;
use App\Security\UsersAuthenticator;
use App\Service\JWTService;
use App\Service\sendMailService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class RegistrationController extends AbstractController
{
    #[Route('/inscription', name: 'app_register')]
    public function register(
        Request $request,
        UserPasswordHasherInterface $userPasswordHasher,
        UserAuthenticatorInterface $userAuthenticator,
        UsersAuthenticator $authenticator,
        EntityManagerInterface $entityManager,
        sendMailService $mailService,
        JWTService $jwtService
    ): Response {
        // Crée une nouvelle instance de l'entité Users
        $user = new Users();
        // Crée un formulaire d'inscription
        $form = $this->createForm(RegistrationFormType::class, $user);
        // Traite la requête HTTP et l'associe au formulaire
        $form->handleRequest($request);
        // Vérifie si le formulaire est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {
            // Hash le mot de passe de l'utilisateur
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );
            // Enregistre le nouvel utilisateur dans la base de données
            $entityManager->persist($user);
            $entityManager->flush();
            // Prépare les données du token JWT
            $header = [
                'type' => 'JWT',
                'alg' => 'HS256'
            ];
            $payload = [
                'user_id' => $user->getId()
            ];
            // Génère un token JWT
            $token = $jwtService->generate($header, $payload, $this->getParameter('app.jwtsecret'));
            // Envoie un e-mail d'activation avec le token JWT
            $mailService->send(
                'no-reply@epicerie.com',
                $user->getEmail(),
                'Activation du compte',
                'register',
                [
                    'user' => $user,
                    'token' => $token
                ]
            );
            // Authentifie l'utilisateur et le redirige
            return $userAuthenticator->authenticateUser(
                $user,
                $authenticator,
                $request
            );
        }
        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),

        ]);
    }
    #[Route('/verification/{token}', name: 'app_verifyUser')]
    public function verifyUser(
        $token,
        JWTService $jwtService,
        UsersRepository $repository,
        EntityManagerInterface $manager
    ): Response {
        // Vérifie si le token JWT est valide, non expiré, et correspond à la clé secrète
        if ($jwtService->isValid($token) && !$jwtService->isExpired($token) && $jwtService->check($token, $this->getParameter('app.jwtsecret'))) {
            // Récupère les informations (payload) du token
            $payload = $jwtService->getPayload($token);
            // Trouve l'utilisateur correspondant à l'ID dans le payload
            $user = $repository->find($payload['user_id']);
            // Vérifie si l'utilisateur existe et n'est pas déjà vérifié
            if ($user && !$user->getIsVerified()) {
                // Active le compte de l'utilisateur
                $user->setIsVerified(true);
                // Enregistre les modifications dans la base de données
                $manager->flush($user);
                $this->addFlash('success', 'Votre compte est bien activé');
                return $this->redirectToRoute('app_profile_index');
            }
        }
        // Affiche un message d'erreur si le token n'est pas valide ou expiré
        $this->addFlash('danger', 'le jeton de vérification est invalide ou expiré');
        return $this->redirectToRoute('app_login');
    }
    #[Route('/nouvelleverification/{token}', name: 'app_resend_verifyUser')]
    public function resendVerify(
        JWTService $jwtService,
        sendMailService $mailService,
        UsersRepository $repository
    ): Response {
        // Obtient l'utilisateur connecté
        $user = $this->getUser();
        // Vérifie si un utilisateur est connecté
        if (!$user) {
            $this->addFlash('danger', 'Vous devez être connecté pour accéder à cette page');
            return $this->redirectToRoute('app_login');
        }
        // Vérifie si le compte de l'utilisateur est déjà vérifié
        if ($user->getIsVerified()) {
            $this->addFlash('warning', 'Votre compte est déjà activé');
            return $this->redirectToRoute('app_profile_index');
        }
        // Prépare les données du token JWT
        $header = [
            'type' => 'JWT',
            'alg' => 'HS256'
        ];
        $payload = [
            'user_id' => $user->getId()
        ];
        // Génère un nouveau token JWT
        $token = $jwtService->generate($header, $payload, $this->getParameter('app.jwtsecret'));
        // Envoie un e-mail d'activation avec le nouveau token JWT
        $mailService->send(
            'no-reply@epicerie.com',
            $user->getEmail(),
            'Activation du compte',
            'register',
            [
                'user' => $user,
                'token' => $token
            ]
        );
        $this->addFlash('success', 'Nouveau lien de vérification envoyé');
        return $this->redirectToRoute('app_profile_index');
    }
}
