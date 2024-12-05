<?php

namespace App\Controller;

use App\Entity\Room;
use App\Form\RoomType;
use App\Repository\RoomRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class RoomController extends AbstractController
{
    #[Route('/room', name: 'room')]
    public function index(RoomRepository $roomRepository): Response
    {
        $rooms = $roomRepository->findAll();

        return $this->render('room/index.html.twig', [
            'rooms'=>$rooms,
        ]);
    }

    #[Route(path:'/room/create', name: 'room_create')]
    public function createRoom(Request $request, EntityManagerInterface $entityManager): Response
    {
        $room = new Room();

        $form = $this->createForm(RoomType::class, $room);
        $form_view = $form->createView();

        $form->handleRequest($request);

        if($form->isSubmitted())
        {
            $entityManager->persist($room);
            $entityManager->flush();
            return $this->redirectToRoute('room');
        }
        return $this->render('room/create.html.twig', ['form_view'=>$form_view]);
    }
}
