<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Attribute\Route;

class ContactController extends AbstractController
{
    #[Route('/contact', name: 'contact')]
    public function index(EntityManagerInterface $entityManager,
                          Request $request, MailerInterface $mailer): Response
    {
        $contact = new Contact();

        $form = $this->createForm(ContactType::class, $contact);
        $formView = $form->createView();

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            //je crée un email
            $email = new Email();

            //je fais un template avec mon mail
            //je lui fais passer ma vue et je lui donne les valeur de contact récup dans le form
            $emailTemplate = $this->renderView('contact/template.html.twig', ['contact' => $contact]);

            //j'envoie avec mailer
            $mailer->send(
                $email->from('noreply@tatiana.com')
                ->to('tatiana@tatiana.com')
                -> subject('demande de contact')
                -> html($emailTemplate)
            );

            $this->addFlash('success', 'Message envoyé');
            $this->redirectToRoute('home');
        }
        return $this->render('contact/index.html.twig', ['formView'=>$formView]);
    }

}
