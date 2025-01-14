<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Category;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface; // Importer l'EntityManagerInterface

class HomeController extends AbstractController
{
    private $entityManager; 

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        // Récupérer tous les articles depuis la base de données
        $articleRepository = $this->entityManager->getRepository(Article::class);
        $articles = $articleRepository->findAll();

        $categoryRepository = $this->entityManager->getRepository(Category::class);
        $category=$categoryRepository->findAll();

        // Passer les articles au template Twig pour l'affichage dans la page d'accueil
        return $this->render('home/home.html.twig', [
            'articles' => $articles,
            'category' =>$category,
        ]);
    }
}
