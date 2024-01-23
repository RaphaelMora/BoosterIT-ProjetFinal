<?php

namespace App\Controller;

use App\Form\ResetPasswordFormType;
use App\Form\ResetPasswordRequestFormType;
use App\Repository\UsersRepository;
use App\Service\sendMailService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route(path: '/connexion', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // Récupère les éventuelles erreurs d'authentification précédentes
        $error = $authenticationUtils->getLastAuthenticationError();
        // Récupère le dernier nom d'utilisateur saisi
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }
    #[Route(path: '/deconnexion', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
    #[Route(path: '/oubliemdp', name: 'app_forgot_password')]
    public function forgotPassword(
        Request $request,
        UsersRepository $repository,
        TokenGeneratorInterface $generator,
        EntityManagerInterface $manager,
        sendMailService $mailService
    ): Response {
        // Crée un formulaire de demande de réinitialisation de mot de passe
        $form = $this->createForm(ResetPasswordRequestFormType::class);
        $form->handleRequest($request);
        // Traite le formulaire soumis
        if ($form->isSubmitted() && $form->isValid()) {
            // Trouve l'utilisateur par l'email fourni
            $user = $repository->findOneByEmail($form->get('email')->getData());
            if ($user) {
                // Génère un token de réinitialisation
                $token = $generator->generateToken();
                $user->setResetToken($token);
                $manager->persist($user);
                $manager->flush();
                // Prépare et envoie l'email de réinitialisation
                $url = $this->generateUrl('app_reset_password', ['token' => $token], UrlGeneratorInterface::ABSOLUTE_URL);
                $context = ['url' => $url, 'user' => $user];
                $mailService->send('no-reply@epicerie.com', $user->getEmail(), 'Réinitialisation du mot de passe', 'passwordReset', $context);

                $this->addFlash('success', "Email envoyé avec succès");
                return $this->redirectToRoute('app_login');
            }
            // Gère le cas où l'utilisateur n'est pas trouvé
            $this->addFlash('danger', "Une erreur est survenue");
            return $this->redirectToRoute('app_login');
        }
        return $this->render('security/reset_password_request.html.twig', ['requestPass' => $form->createView()]);
    }
    #[Route(path: '/oubliepass/{token}', name: 'app_reset_password')]
    public function resetPass(
        string $token,
        Request $request,
        UsersRepository $repository,
        EntityManagerInterface $manager,
        UserPasswordHasherInterface $hasher
    ): Response {
        // Trouve l'utilisateur par le token de réinitialisation
        $user = $repository->findOneByResetToken($token);
        if ($user) {
            // Crée un formulaire de réinitialisation de mot de passe
            $form = $this->createForm(ResetPasswordFormType::class);
            $form->handleRequest($request);
            // Traite le formulaire soumis
            if ($form->isSubmitted() && $form->isValid()) {
                // Réinitialise le mot de passe de l'utilisateur
                $user->setResetToken('');
                $user->setPassword(
                    $hasher->hashPassword(
                        $user,
                        $form->get('password')->getData()
                    )
                );
                $manager->persist($user);
                $manager->flush();
                $this->addFlash('success', 'Mot de passe réinitialisé avec succès');
                return $this->redirectToRoute('app_login');
            }
            return $this->render(
                'security/resetPassword.html.twig',
                ['passForm' => $form->createView()]
            );
        }
        // Gère le cas où le token n'est pas valide
        $this->addFlash('danger', 'Jeton invalide');
        return $this->redirectToRoute('app_login');
    }
}
