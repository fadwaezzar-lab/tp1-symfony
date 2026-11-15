<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class MailController extends AbstractController
{
    #[Route('/test-email', name: 'test_email')]
    public function sendEmail(MailerInterface $mailer): Response
    {
        $email = (new Email())
            ->from('test@example.com')
            ->to('recipient@example.com')
            ->subject('Test Symfony Mailer')
            ->text('Symfony Mailer is working!');

        $mailer->send($email);

        return new Response('Email test executed successfully!');
    }
}
