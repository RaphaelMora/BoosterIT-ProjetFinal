<?php

namespace App\Service;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;

class sendMailService
{
    private $mailer;

    /**
     * Constructeur de la classe sendMailService.
     * 
     * @param MailerInterface $mailer  Le service mailer pour l'envoi d'emails.
     */
    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * Envoie un email basé sur un template Twig.
     * 
     * @param string $from     L'adresse email de l'expéditeur.
     * @param string $to       L'adresse email du destinataire.
     * @param string $subject  Le sujet de l'email.
     * @param string $template Le nom du template Twig à utiliser pour le corps de l'email.
     * @param array $context   Le contexte (variables et données) à passer au template.
     */
    public function send(
        string $from,
        string $to,
        string $subject,
        string $template,
        array $context
    ): void {
        // Crée l'email en utilisant TemplatedEmail
        $email = (new TemplatedEmail())
            ->from($from)      // Définit l'expéditeur de l'email
            ->to($to)          // Définit le destinataire de l'email
            ->subject($subject) // Définit le sujet de l'email
            ->htmlTemplate("email/$template.html.twig") // Définit le template Twig pour le corps de l'email
            ->context($context); // Passe le contexte au template
        // Envoie l'email
        $this->mailer->send($email);
    }
}
