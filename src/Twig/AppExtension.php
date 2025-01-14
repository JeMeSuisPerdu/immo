<?php
// src/Twig/AppExtension.php
namespace App\Twig;

use App\Service\CategoryService;
use Twig\Extension\AbstractExtension;
use Twig\Extension\GlobalsInterface;

class AppExtension extends AbstractExtension implements GlobalsInterface
{
    private $categoryService;

    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    public function getGlobals(): array
    {
        return [
            'categories' => $this->categoryService->getCategoriesWithSubcategoriesAndAttributes(),
        ];
    }
}

