<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Form\ContactType;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    #[Route('/contact', name: 'app_contact')]
    public function contact(Request $request, MailerInterface $mailer): Response
    {
        // Crée un formulaire de contact
        $form = $this->createForm(ContactType::class);
        // Traite la requête HTTP et l'associe au formulaire
        $form->handleRequest($request);
        // Vérifie si le formulaire est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {
            // Récupère les données du formulaire
            $contactData = $form->getData();
            // Prépare un nouvel e-mail avec les données du formulaire
            $email = (new Email())
                ->from($contactData['email']) // Définit l'expéditeur avec l'email fourni dans le formulaire
                ->to('admin@epicerie.com') // Définit le destinataire de l'email
                ->subject($contactData['objet']) // Définit le sujet de l'email
                ->text($contactData['message']); // Ajoute le message du formulaire dans le corps de l'e-mail
            // Envoie l'e-mail
            $mailer->send($email);
            // Affiche un message de succès
            $this->addFlash('success', 'Votre message a été envoyé.');
            return $this->redirectToRoute('app_main');
        }
        return $this->render('contact/formContact.html.twig', [
            'contactForm' => $form->createView(),
        ]);
    }
}
