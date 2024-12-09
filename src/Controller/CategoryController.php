<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CategoryController extends AbstractController
{
    #[Route('/category', name: 'category')]
    public function index(CategoryRepository $categoryRepository): Response
    {
        $categories = $categoryRepository->findAll();

        return $this->render('category/index.html.twig', [
            'categories' => $categories,
        ]);
    }

    #[Route(path:'/category/update/{id}', name: 'category_update', requirements: ['id'=>'\d+'])]
    public function updateCategory(int $id, CategoryRepository $categoryRepository, EntityManagerInterface $entityManager, Request $request)
    {
        $categoryToUpdate = $categoryRepository->find($id);

        if (!$categoryToUpdate) {
            return $this->redirectToRoute('not_found');
        }

        $form = $this->createForm(categoryType::class, $categoryToUpdate);
        $form_view = $form->createView();

        $form->handleRequest($request);

        if($form->isSubmitted() ){
            $entityManager->persist($categoryToUpdate);
            $entityManager->flush();
            return($this->redirectToRoute('category'));
        }

        return $this->render('category/update.html.twig', ['form_view'=>$form_view, 'category'=>$categoryToUpdate]);
    }

    #[Route(path: '/category/create', name: 'category_create')]
    public function createCategory(Request $request, EntityManagerInterface $entityManager): Response
    {
        $category = new Category();

        $form = $this->createForm(CategoryType::class, $category);
        $form_view = $form->createView();

        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $entityManager->persist($category);
            $entityManager->flush();

            return $this->redirectToRoute('category');
        }
        return $this->render('category/create.html.twig', ['form_view'=>$form_view]);
    }


    #[Route(path:'/category/delete/{id}', name: 'category_delete', requirements: ['id'=>'\d+'])]
    public function deleteCategory(int $id, CategoryRepository $categoryRepository,EntityManagerInterface $entityManager )
    {
        $categoryToDelete = $categoryRepository->find($id);

        $entityManager->remove($categoryToDelete);
        $entityManager->flush();
        $this->addFlash('success', 'catégorie supprimée avec succès');
        return $this->redirectToRoute('category');
    }
}
