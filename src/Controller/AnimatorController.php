<?php

namespace App\Controller;

use App\Entity\Animator;
use App\Form\AnimatorType;
use App\Repository\AnimatorRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AnimatorController extends AbstractController
{
    #[Route(path: '/animator', name: 'animator')]
    public function index(AnimatorRepository $animatorRepository): Response
    {
        $animators = $animatorRepository->findAll();

        return $this->render('animator/index.html.twig', [
            'animators' => $animators,
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

            $this->addFlash('success', 'Animateur créée avec succès');

            return $this->redirectToRoute('animator');
        }
        return $this->render('animator/create.html.twig', ['form_view'=>$form_view]);
    }

    #[Route(path:'/animator/update/{id}', name: 'animator_update', requirements: ['id'=>'\d+'])]
    public function updateAnimator(int $id, AnimatorRepository $animatorRepository, EntityManagerInterface $entityManager, Request $request)
    {
        $animatorToUpdate = $animatorRepository->find($id);

        if (!$animatorToUpdate) {
            return $this->redirectToRoute('not_found');
        }

        $form = $this->createForm(AnimatorType::class, $animatorToUpdate);
        $form_view = $form->createView();

        $form->handleRequest($request);

        if($form->isSubmitted() ){
            $entityManager->persist($animatorToUpdate);
            $entityManager->flush();
            return($this->redirectToRoute('animator'));
        }

        return $this->render('animator/update.html.twig', ['form_view'=>$form_view, 'animator'=>$animatorToUpdate]);
    }

    #[Route(path:'/animator/delete/{id}', name: 'animator_delete', requirements: ['id'=>'\d+'])]
    public function deleteAnimator(int $id, AnimatorRepository $animatorRepository,EntityManagerInterface $entityManager )
    {
        $animatorToDelete = $animatorRepository->find($id);

        $entityManager->remove($animatorToDelete);
        $entityManager->flush();
        $this->addFlash('success', 'animateur supprimé avec succès');
        return $this->redirectToRoute('animator');
    }
}
