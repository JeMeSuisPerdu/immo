<?php 

// src/Controller/CategoryController.php
namespace App\Controller;
use App\Repository\ArticleRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\CategoryService;
use Doctrine\ORM\EntityManagerInterface;


class CategoryController extends AbstractController
{
    private $categoryService;
    private $entityManager; 
    public function __construct(CategoryService $categoryService,EntityManagerInterface $entityManager)
    {
        $this->categoryService = $categoryService;
        $this->entityManager = $entityManager;
    }

    #[Route('category/', name: 'app_category_catalog')]
    public function showAllCategory(): Response
    {
        return $this->render('category/all_category.html.twig');
    }
    
    #[Route('category/{category}', name: 'app_category')]
    public function showCategory(string $category): Response
    {
        
        // Récupérer les catégories et sous-catégories via le service
        $categories = $this->categoryService->getCategoriesWithSubcategoriesAndAttributes();

        return $this->render('category/category.html.twig', [
            'category' => $category,
            'categories' => $categories,
        ]);
    }
    
    #[Route('category/{category}/{subcategory}', name: 'app_subcategory')]
    public function showSubcategory(string $category, string $subcategory): Response
    {
        return $this->render('category/subcategory.html.twig', [
            'category' => $category,
            'subcategory' => $subcategory,

            ]);
    }


}


