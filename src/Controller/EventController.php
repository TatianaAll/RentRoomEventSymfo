<?php

namespace App\Controller;

use App\Entity\Event;
use App\Form\EventType;
use App\Repository\EventRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class EventController extends AbstractController
{
    #[Route('/event', name: 'event')]
    public function index(EventRepository $eventRepository): Response
    {
        $events = $eventRepository->findAll();

        return $this->render('event/index.html.twig', [
            'events' => $events,
        ]);
    }

    #[Route('/event/show/{id}', name: 'event_show')]
    public function showEvent(int $id, EventRepository $eventRepository): Response
    {
        $event = $eventRepository->find($id);

        return $this->render('event/show.html.twig', ['event' => $event]);
    }

    #[Route(path: '/event/create', name: 'event_create')]
    public function createEvent(Request $request, EntityManagerInterface $entityManager): Response
    {
        $event = new Event();

        $form = $this->createForm(EventType::class, $event);
        $form_view = $form->createView();

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $entityManager->persist($event);
            $entityManager->flush();

            $this->addFlash('success', 'Evènement bien créé ;)');
            return $this->redirectToRoute('event');
        }
        return $this->render('event/create.html.twig', ['form_view' => $form_view]);
    }

    #[Route(path: '/event/update/{id}', name: 'event_update', requirements: ['id' => '\d+'])]
    public function updateEvent(int $id,
                               EventRepository $eventRepository,
                               Request $request,
                               EntityManagerInterface $entityManager): Response
    {
        $eventToUpdate = $eventRepository->find($id);

        if (!$eventToUpdate) {
            return $this->redirectToRoute('not_found');
        }

        $form = $this->createForm(EventType::class, $eventToUpdate);
        $formView = $form->createView();
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $entityManager->persist($eventToUpdate);

            $entityManager->flush();

            $this->addFlash('success', 'Evènement mis à jour correctement ;)');

            return $this->redirectToRoute('event');
        }
        return $this->render('event/update.html.twig', ['form_view' => $formView,
            'event' => $eventToUpdate]);
    }

}
