<?php

namespace App\Controller;

use App\Entity\Article;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
class NavbarController extends AbstractController
{
    /**
     * Action pour le formulaire de recherche.
     */
    #[Route('/article/search/ajax', name: 'ajax_search_article')]
    public function ajaxSearchManga(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $searchTerm = strtolower($request->query->get('query'));
    
        // Rechercher dans Article
        $articles = $entityManager->getRepository(className: Article::class)
            ->createQueryBuilder('a')
            ->where('LOWER(a.title) LIKE :term')
            ->setParameter('term', '%' . $searchTerm . '%')
            ->setMaxResults(5)
            ->getQuery()
            ->getResult();
    
        // Préparer les résultats pour le format JSON
        $results = [];
        foreach ($articles as $article) {
            $results[] = [
                'id' => $article->getId(),
                // 'owner_id' => $article->getOwner() ? $article->getOwner()->getId() : null,
                // 'category_id' => $article->getCategory() ? $article->getCategory()->getId() : null,
                // 'subcategory_id' => $article->getSubcategory() ? $article->getSubcategory()->getId() : null,
                'title' => $article->getTitle(),
                'description' => $article->getDescription(),
                'price' => $article->getPrice(),
            ];
        }        
        return $this->json($results);
    }

}