<?php

namespace App\Controller;

use App\Entity\Etablishment;
use App\Form\EtablishmentType;
use App\Repository\EtablishmentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class EtablishmentController extends AbstractController
{
    #[Route('/etablishment', name: 'etablishment')]
    public function index(EtablishmentRepository $etablishmentRepository): Response
    {
        $etablishments = $etablishmentRepository->findAll();

        return $this->render('etablishment/index.html.twig', [
            'etablishments' => $etablishments,
        ]);
    }

    #[Route('/etablishment/create', name: 'etablishment_create')]
    public function createEtablishment(Request $request, EntityManagerInterface $entityManager): Response
    {
        $etablishment = new Etablishment();
        $form = $this->createForm(EtablishmentType::class, $etablishment);
        $form_view = $form->createView();

        $form->handleRequest($request);

        if($form->isSubmitted() ){
            $entityManager->persist($etablishment);
            $entityManager->flush();
            return($this->redirectToRoute('etablishment'));
        }

        return $this->render('etablishment/create.html.twig', ['form_view'=>$form_view]);
    }
}
