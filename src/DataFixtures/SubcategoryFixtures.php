<?php
// src/DataFixtures/SubcategoryFixtures.php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Subcategory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class SubcategoryFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $categories = $manager->getRepository(Category::class)->findAll();
        $categoryMap = [];

        foreach ($categories as $category) {
            $categoryMap[$category->getName()] = $category;
        }

        $subcategories = [
            ['category' => 'Immobilier', 'name' => 'Appartement'],
            ['category' => 'Immobilier', 'name' => 'Maison'],
            ['category' => 'Immobilier', 'name' => 'Terrain'],
            ['category' => 'Immobilier', 'name' => 'Autre'],
            ['category' => 'Véhicules', 'name' => 'Voitures'],
            ['category' => 'Véhicules', 'name' => 'Motos'],
            ['category' => 'Véhicules', 'name' => 'Caravanes'],
            ['category' => 'Véhicules', 'name' => 'Utilitaires'],
            ['category' => 'Véhicules', 'name' => 'Equipement Auto'],
            ['category' => 'Véhicules', 'name' => 'Equipement Moto'],
            ['category' => 'Véhicules', 'name' => 'Equipement Caravanes'],
            ['category' => 'Véhicules', 'name' => 'Nautisme'],
            ['category' => 'Véhicules', 'name' => 'Equipement Nautique'],
            ['category' => 'Véhicules', 'name' => 'Quads'],
            ['category' => 'Vêtements', 'name' => 'Homme'],
            ['category' => 'Vêtements', 'name' => 'Femme'],
            ['category' => 'Vêtements', 'name' => 'Enfant'],
            ['category' => 'Électronique', 'name' => 'Téléphones & Tablettes'],
            ['category' => 'Électronique', 'name' => 'Ordinateurs & Composants'],
            ['category' => 'Électronique', 'name' => 'Télévisions & Video'],
            ['category' => 'Électronique', 'name' => 'Audio'],
            ['category' => 'Électronique', 'name' => 'Photographie & Caméras'],
            ['category' => 'Électronique', 'name' => 'Appareils Électroménagers'],
            ['category' => 'Électronique', 'name' => 'Objets Connectés'],
            ['category' => 'Meubles', 'name' => 'Salon'],
            ['category' => 'Meubles', 'name' => 'Salle à Manger'],
            ['category' => 'Meubles', 'name' => 'Chambre'],
            ['category' => 'Meubles', 'name' => 'Bureau'],
            ['category' => 'Meubles', 'name' => 'Autre'],
            ['category' => 'Loisirs', 'name' => 'Jeux & Jouets'],
            ['category' => 'Loisirs', 'name' => 'Instruments de Musique'],
            ['category' => 'Loisirs', 'name' => 'Camping & Randonnée'],
            ['category' => 'Loisirs', 'name' => 'Art & Artisanat'],
            ['category' => 'Loisirs', 'name' => 'Modélisme']
        ];

        foreach ($subcategories as $data) {
            if (!isset($categoryMap[$data['category']])) {
                throw new \Exception(sprintf('Category "%s" not found!', $data['category']));
            }

            $subcategory = new Subcategory();
            $subcategory->setCategory($categoryMap[$data['category']]);
            $subcategory->setName($data['name']);
            $manager->persist($subcategory);
        }

        $manager->flush();
    }
}
