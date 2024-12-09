<?php

namespace App\Controller;

use App\Entity\Etablishment;
use App\Entity\Image;
use App\Entity\Room;
use App\Form\RoomType;
use App\Repository\RoomRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
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
            'rooms' => $rooms,
        ]);
    }

    #[Route('/room/show/{id}', name: 'room_show')]
    public function showRoom(int $id, RoomRepository $roomRepository): Response
    {
        $room = $roomRepository->find($id);

        $establishment = $room->getEtablishment();

        return $this->render('room/show.html.twig', ['room' => $room, 'etablishment' => $establishment]);
    }

    #[Route(path: '/room/create', name: 'room_create')]
    public function createRoom(Request $request, EntityManagerInterface $entityManager): Response
    {
        $room = new Room();

        $form = $this->createForm(RoomType::class, $room);
        $form_view = $form->createView();

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            //ajout d'images
            //1- je les récupère depuis mon formulaire
            $imagesImported = $form->get('images')->getData();

            //je boucle sur les images importées (car multiple = true dans le form)
            foreach ($imagesImported as $imageImported) {
                //je donne un nom à monfichier en le hachant avec md5 + uniqid
                //je concatène avec un . et l'extension détectée de mon image
                $fileName = md5(uniqid()) . '.' . $imageImported->getClientOriginalExtension();
                // Je déplace l'image importée dans le répertoire configuré pour les téléchargements.
                // getParameter('uploads_directory') : Récupère le chemin du dossier où stocker les fichiers,
                // défini dans les paramètres de l'application Symfony.
                // move() : Déplace le fichier téléchargé dans ce répertoire en utilisant le nom généré.
                $imageImported->move(
                    $this->getParameter('uploads_directory'),
                    $fileName);

                // Création d'une nouvelle instance de l'entité Image
                $imageImported = new Image();

                // Définit le chemin public de l'image pour pouvoir l'afficher plus tard.
                // 'uploads_base_url' contient l'URL de base (par exemple, '/uploads').
                $imageImported->setPath($this->getParameter('uploads_base_url') . '/' . $fileName);
                //dd($imageImported);
                // Relie l'image à la Room créée
                $imageImported->setRoom($room);

                // Persist l'image ==> elle est ''''commit'''' mais pas encore 'push'
                $entityManager->persist($imageImported);
            }

            $entityManager->persist($room);
            $entityManager->flush();
            return $this->redirectToRoute('room');
        }

        return $this->render('room/create.html.twig', ['form_view' => $form_view]);
    }

    #[
        Route(path: '/room/update/{id}', name: 'room_update', requirements: ['id' => '\d+'])]
    public function updateRoom(int                    $id,
                               RoomRepository         $roomRepository,
                               Request                $request,
                               EntityManagerInterface $entityManager): Response
    {
        $roomToUpdate = $roomRepository->find($id);

        if (!$roomToUpdate) {
            return $this->redirectToRoute('not_found');
        }

        $form = $this->createForm(RoomType::class, $roomToUpdate);
        $formView = $form->createView();
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $imagesImported = $form->get('images')->getData();

            foreach ($imagesImported as $imageImported) {

                $fileName = md5(uniqid()) . '.' . $imageImported->getClientOriginalExtension();
                $imageImported->move(
                    $this->getParameter('uploads_directory'),
                    $fileName);
                // Création d'une nouvelle instance de l'entité Image
                $imageImported = new Image();
                $imageImported->setPath($this->getParameter('uploads_base_url') . '/' . $fileName);
                $imageImported->setRoom($roomToUpdate);
                $entityManager->persist($imageImported);
            }

            $entityManager->persist($roomToUpdate);
            $entityManager->flush();
            return $this->redirectToRoute('room');
        }
        return $this->render('room/update.html.twig', ['form_view' => $formView, 'room' => $roomToUpdate]);
    }

}
