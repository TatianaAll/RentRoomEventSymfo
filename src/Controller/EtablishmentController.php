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

    #[Route('/etablishment/show/{id}', name: 'etablishment_show', requirements: ['id' =>'\d+'])]
    public function showEtablishment(int $id, EtablishmentRepository $etablishmentRepository): Response
    {
        $etablishment = $etablishmentRepository->find($id);

        return $this->render('etablishment/show.html.twig', [
            'etablishment' => $etablishment,
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

    #[Route('/etablishment/update/{id}', name: 'etablishment_update', requirements: ['id'=>'\d+'])]
    public function updateEtablishment(int $id,
                                       Request $request,
                                       EntityManagerInterface $entityManager,
                                       EtablishmentRepository $etablishmentRepository): Response
    {
        $etablishmentToUpdate = $etablishmentRepository->find($id);

        if (!$etablishmentToUpdate) {
            return $this->redirectToRoute('not_found');
        }

        $form = $this->createForm(EtablishmentType::class, $etablishmentToUpdate);
        $form_view = $form->createView();

        $form->handleRequest($request);

        if($form->isSubmitted() ){
            $entityManager->persist($etablishmentToUpdate);
            $entityManager->flush();
            return($this->redirectToRoute('etablishment'));
        }

        return $this->render('etablishment/update.html.twig', ['form_view'=>$form_view, 'etablishment'=>$etablishmentToUpdate]);
    }

    #[Route(path: 'etablishment/delete/{id}',name: 'etablishment_delete', requirements: ['id'=>'\d+'])]
    public function deleteEstablishment(int $id,
                                        EtablishmentRepository $etablishmentRepository,
                                        EntityManagerInterface $entityManager): Response
    {
        $etablishmentToDelete = $etablishmentRepository->find($id);
        $roomsToDelete = $etablishmentToDelete->getRooms();
        foreach($roomsToDelete as $room) {
            $imagesToDelete = $room->getImages();
            foreach ($imagesToDelete as $image)
            {
                //dd($this->getParameter('uploads_directory').'/'.$image->getFileName());
                @unlink($this->getParameter('uploads_directory').'/'.$image->getFileName());
            }
        }
        $entityManager->remove($etablishmentToDelete);
        $entityManager->flush();

        return $this->redirectToRoute('etablishment');
    }
}
