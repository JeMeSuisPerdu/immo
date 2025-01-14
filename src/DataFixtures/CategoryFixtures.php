<?php
// src/DataFixtures/CategoryFixtures.php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CategoryFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $categories = [
            ['name' => 'Immobilier'],
            ['name' => 'Véhicules'],
            ['name' => 'Vêtements'],
            ['name' => 'Électronique'],
            ['name' => 'Meubles'],
            ['name' => 'Loisirs']
        ];

        foreach ($categories as $data) {
            $category = new Category();
            $category->setName($data['name']);
            $manager->persist($category);
        }

        $manager->flush();
    }
}

