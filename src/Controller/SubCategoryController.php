<?php

namespace App\Controller;

use App\Entity\Subcategory;
use App\Repository\ArticleRepository;
use App\Repository\SubcategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SubCategoryController extends AbstractController
{
    #[Route('/subcategory/{subcategoryId}', name: 'subcategory_articles', methods: ['GET'])]
    public function articlesBySubcategory(
        int $subcategoryId,
        SubcategoryRepository $subcategoryRepository,
        ArticleRepository $articleRepository
    ): Response {
        // Récupérer la sous-catégorie par son ID
        $subcategory = $subcategoryRepository->find($subcategoryId);

        if (!$subcategory) {
            throw $this->createNotFoundException('Sous-catégorie introuvable.');
        }

        // Récupérer les articles liés à cette sous-catégorie
        $articles = $articleRepository->findBy(['subcategory' => $subcategory]);

        return $this->render('category/subcategory.html.twig', [
            'subcategory' => $subcategory->getName(),
            'category' => $subcategory->getCategory()->getName(),
            'articles' => $articles, // Vérifiez que cette ligne existe
        ]);
        
    }
}
