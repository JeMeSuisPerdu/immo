<?php
// src/Service/CategoryService.php
namespace App\Service;

use App\Repository\CategoryRepository;
use Symfony\Component\String\Slugger\SluggerInterface;

// src/Service/CategoryService.php
namespace App\Service;

use App\Repository\CategoryRepository;
use Symfony\Component\String\Slugger\SluggerInterface;

class CategoryService
{
    private $categoryRepository;
    private $slugger;

    public function __construct(CategoryRepository $categoryRepository, SluggerInterface $slugger)
    {
        $this->categoryRepository = $categoryRepository;
        $this->slugger = $slugger;
    }

    public function getCategoriesWithSubcategoriesAndAttributes(): array
    {
        try {
            // Récupération des catégories avec sous-catégories et attributs en une seule requête
            $categories = $this->categoryRepository->findAllWithSubcategoriesAndAttributes();

            $data = [];
            foreach ($categories as $category) {
                $subcategories = $category->getSubcategories();
                $subcategoryData = [];

                foreach ($subcategories as $subcategory) {
                    $attributes = $subcategory->getAttributes();
                    $attributeData = [];

                    foreach ($attributes as $attribute) {
                        $attributeData[] = [
                            'id' => $attribute->getId(),
                            'name' => $attribute->getName(),
                            'type' => $attribute->getType(),
                        ];
                    }

                    $subcategoryData[] = [
                        'id' => $subcategory->getId(),
                        'name' => $subcategory->getName(),
                        'slug' => $this->slugger->slug($subcategory->getName())->lower(),
                        'attributes' => $attributeData,
                    ];
                }

                $data[] = [
                    'id' => $category->getId(),
                    'name' => $category->getName(),
                    'slug' => $this->slugger->slug($category->getName())->lower(),
                    'subcategories' => $subcategoryData,
                ];
            }

            return $data;
        } catch (\Exception $e) {
            // Gérer l'erreur ici : logger, exception personnalisée, etc.
            throw new \RuntimeException('Failed to retrieve categories.', 0, $e);
        }
    }
}

