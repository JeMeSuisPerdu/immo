<?php

namespace App\Controller;

use App\Entity\Photo;
use App\Entity\Article;
use App\Entity\AttributesValue;
use App\Repository\AttributesRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\SubcategoryRepository;
use App\Repository\ArticleRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\ArticlePublicationType;

class ArticlePublicationController extends AbstractController
{
    private $entityManager;
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/publish_an_article', name: 'app_publish_article')]
    public function publish(Request $request, AttributesRepository $attributeRepository): Response
    {
        // Récupération des données de la requête JSON
        $price = $request->request->get('price');

        $article = new Article();
        $article->setOwner($this->getUser());

        $form = $this->createForm(ArticlePublicationType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $article = $form->getData();
            $article->setPrice((int)$price); 

            $subcategory = $article->getSubcategory();

            if ($subcategory) {
                // Récupération des attributs liés à la sous-catégorie
                $attributes = $attributeRepository->findBy(['subcategory' => $subcategory]);         
                // Récupération des données soumises
                $submittedData = $request->request->all();
                $submittedAttributes = $submittedData['attributes'] ?? [];      
                // Traitement des attributs dynamiques
                foreach ($attributes as $attribute) {
                    $attributeId = $attribute->getId();
                    $submittedValue = $submittedAttributes[$attributeId] ?? null;
        
                    if ($submittedValue !== null) {
                        $attributeValue = new AttributesValue();
                        $attributeValue->setAttribute($attribute);
                        $attributeValue->setArticle($article);
        
                        switch ($attribute->getType()) {
                            case 'boolean':
                                $value = ($submittedValue === '1' || $submittedValue === true) ? true : false;
                                $attributeValue->setValueBoolean($value);
                                break;
                            case 'integer':
                                $attributeValue->setValueInteger((int) $submittedValue);
                                break;
                            case 'date':
                                $attributeValue->setValueDate(new \DateTime($submittedValue));
                                break;
                            case 'string':
                            case 'choice':
                                $attributeValue->setValueString($submittedValue);
                                break;
                            default:
                                $attributeValue->setValueString($submittedValue);
                        }
        
                        $this->entityManager->persist($attributeValue);
                    }
                }
            }
            $uploadedFiles = $request->files->get('new_photos');
            if ($uploadedFiles) {
                foreach ($uploadedFiles as $uploadedFile) {
                    if ($uploadedFile) {
                        $newFilename = uniqid().'.'.$uploadedFile->guessExtension();
                        try {
                            $uploadedFile->move(
                                $this->getParameter('photo_directory'),
                                $newFilename
                            );
                        } catch (FileException $e) {
                            // Gestion de l'erreur si nécessaire
                            continue;
                        }
            
                        $photo = new Photo();
                        $photo->setUrl($newFilename);
                        $photo->setArticle($article); // Associer la photo à l'article
                        $article->addPhoto($photo);
                    }
                }
            }
            

            $this->entityManager->persist($article);
            $this->entityManager->flush();
            return $this->redirectToRoute('app_home');
        }

        if ($request->isXmlHttpRequest()) {
            return new JsonResponse(['status' => 'Form is invalid'], 400);
        }

        return $this->render('article_publication/article.html.twig', [
            'article' => $article,
            'articleForm' => $form->createView(),
        ]);
    }

    #[Route('/get-subcategories/{categorytitle}', name: 'get_subcategories')]
    public function getSubcategories(int $categoryId, SubcategoryRepository $subcategoryRepository): Response
    {
        $subcategories = $subcategoryRepository->findBy(['category' => $categoryId]);

        if (!$subcategories) {
            return $this->json(['message' => 'No subcategories found for this category.'], 404);
        }

        $data = [];
        foreach ($subcategories as $subcategory) {
            $data[] = [
                'id' => $subcategory->getId(),
                'name' => $subcategory->getName(),
            ];
        }

        return $this->json($data);
    }

    #[Route('/get-attributes/{subcategoryId}', name: 'get_attributes')]
    public function getAttributes(int $subcategoryId, AttributesRepository $attributeRepository): Response
    {
        $attributes = $attributeRepository->findBy(['subcategory' => $subcategoryId]);

        if (!$attributes) {
            return $this->json(['message' => 'No attributes found for this subcategory.'], 404);
        }

        $data = [];
        foreach ($attributes as $attribute) {
            $attributeData = [
                'id' => $attribute->getId(),
                'name' => $attribute->getName(),
                'type' => $attribute->getType(),
            ];

            if ($attribute->getType() === 'choice') {
                $choices = $attribute->getChoices();
                $attributeData['choices'] = $choices;
            }

            $data[] = $attributeData;
        }

        return $this->json($data);
    }

    #[Route('/article/{id}', name: 'app_show_article', requirements: ['id' => '\d+'])]
    public function show(int $id, ArticleRepository $articleRepository): Response
    {
        $article = $articleRepository->find($id);
    
        if (!$article) {
            throw $this->createNotFoundException('Article non trouvé');
        }
    
        $attributes = $article->getAttributeValues();
        $nonNullAttributes = [];
    
        foreach ($attributes as $attributeValue) {
            if ($attributeValue->getValueString() !== null ||
                $attributeValue->getValueInteger() !== null ||
                $attributeValue->getValueBoolean() !== null ||
                $attributeValue->getValueDate() !== null) {
                $nonNullAttributes[] = $attributeValue;
            }
        }
    
        return $this->render('article_publication/show.html.twig', [
            'article' => $article,
            'attributes' => $nonNullAttributes,
        ]);
    }
}

