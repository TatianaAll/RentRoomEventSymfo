<?php

namespace App\Controller;

use App\Entity\Animator;
use App\Form\AnimatorType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AnimatorController extends AbstractController
{
    #[Route(path: '/animator', name: 'animator')]
    public function index(): Response
    {
        return $this->render('animator/index.html.twig', [
            'controller_name' => 'AnimatorController',
        ]);
    }

    #[Route(path:"/animator/create", name:'animator_create')]
    public function createAnimator(Request $request, EntityManagerInterface $entityManager): Response
    {
        $animator = new Animator();

        $form = $this->createForm(AnimatorType::class, $animator);
        $form_view = $form->createView();

        $form->handleRequest($request);
        if($form->isSubmitted()){

            $entityManager->persist($animator);
            $entityManager->flush();
            return $this->redirectToRoute('animator');
        }
        return $this->render('animator/create.html.twig', ['form_view'=>$form_view]);
    }
}
