<?php
// src/DataFixtures/AttributeFixtures.php

namespace App\DataFixtures;

use App\Entity\Attributes;
use App\Entity\Choice;
use App\Entity\Subcategory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AttributesFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $subcategories = $manager->getRepository(Subcategory::class)->findAll();
        $subcategoryMap = [];

        foreach ($subcategories as $subcategory) {
            $subcategoryMap[$subcategory->getName()] = $subcategory;
        }

        /**
        * Fonction pour générer les choix d'années pour le champ 'Année de construction'
        */
        function generateYearChoices($startYear = 1910) {
            $currentYear = (int)date('Y'); // Année actuelle
            $years = [];
            for ($year = $startYear; $year <= $currentYear; $year++) {
                $years[] = (string)$year;
            }
            // Ajouter l'option "Inconnue" au début de la liste
            array_unshift($years, 'Inconnue');
            return $years;
        }  

        // Attributs pour Appartement et Maison 
        $houseAppartSubcategories = ['Appartement', 'Maison'];
        foreach ($houseAppartSubcategories as $subcategoryName) {
            if (isset($subcategoryMap[$subcategoryName])) {
                // Crée les attributs pour chaque sous-catégorie dynamique
                $this->createAttribute($manager, $subcategoryMap[$subcategoryName], 'Surface', 'integer');
                $this->createAttribute($manager, $subcategoryMap[$subcategoryName], 'Année de fabrication', 'choice', generateYearChoices());
                $this->createAttribute($manager, $subcategoryMap[$subcategoryName], 'Nombre de pièces', 'choice', ['1', '2', '3', '4', '5', '6 ou plus']);
                $this->createAttribute($manager, $subcategoryMap[$subcategoryName], 'Nombre de chambres', 'choice', ['1', '2', '3', '4', '5', '6 ou plus']);
                $this->createAttribute($manager, $subcategoryMap[$subcategoryName], 'Nombre d\'Étages', 'choice', ['Rez de chaussée', '1', '2', '3', '4', '5', '6', '7', '8', '9 ou plus']);
                $this->createAttribute($manager, $subcategoryMap[$subcategoryName], 'Ascenseur', 'choice',['Avec', 'Sans']);
                $this->createAttribute($manager, $subcategoryMap[$subcategoryName], 'Garage', 'choice',['Avec', 'Sans']);
                $this->createAttribute($manager, $subcategoryMap[$subcategoryName], 'Places de parking', 'choice', ['Aucune', '1', '2', '3', '4', '5', '6', '7', '8', '9 ou plus']);
                $this->createAttribute($manager, $subcategoryMap[$subcategoryName], 'Extérieur', 'choice', ['Sans extérieur', 'Balcon', 'Terrasse', 'Jardin']);
            }
        }

        // Attribut pour Terrain
        if (isset($subcategoryMap['Terrain'])) {
            $this->createAttribute($manager, $subcategoryMap['Terrain'], 'Surface', 'integer');
        }

        // Attributs pour Voitures
        if (isset($subcategoryMap['Voitures'])) {
            $this->createAttribute($manager, $subcategoryMap['Voitures'], 'Couleur', 'choice', ["Noir",
                "Blanc","Gris Clair","Gris Foncé","Argent","Bleu Clair","Bleu Marine","Rouge",
                "Vert","Jaune","Or","Orange","Violet","Marron","Beige","Rose","Turquoise",
                "Rouge Bordeaux","Argent Métallique","Bleu Ciel","Vert Forêt","Bleu Electric",
                "Gris Métallique","Brun"
            ]);
            $this->createAttribute($manager, $subcategoryMap['Voitures'], 'Marque', 'choice',[
                "Audi","BMW","Mercedes-Benz","Volkswagen","Toyota","Honda","Ford","Chevrolet",
                "Nissan","Hyundai","Kia","Subaru","Mazda","Land Rover","Jaguar","Porsche",
                "Lexus","Volvo","Acura","Buick","GMC","Chrysler","Jeep","Ram","Cadillac",
                "Infiniti","Alfa Romeo","Fiat","Mitsubishi","Tesla","Lincoln","Mini",
                "Aston Martin","Bentley","Rolls-Royce","Ferrari","Lamborghini",
                "McLaren","Pagani","Bugatti", "Autre"
            ]);
            $this->createAttribute($manager, $subcategoryMap['Voitures'], 'Année', 'integer');
            $this->createAttribute($manager, $subcategoryMap['Voitures'], 'Boîte de vitesse', 'choice', ['Manuelle', 'Automatique']);
            $this->createAttribute($manager, $subcategoryMap['Voitures'], 'Nombre de portes', 'choice', ['3','4','5', '6 ou plus']);
            $this->createAttribute($manager, $subcategoryMap['Voitures'], 'Nombre de places', 'choice', ['2','3','4','5', '6 ou plus']);
            $this->createAttribute($manager, $subcategoryMap['Voitures'], 'Kilométrage', 'integer');
            $this->createAttribute($manager, $subcategoryMap['Voitures'], 'Carburant', 'choice', ['Essence', 'Diesel', 'Électrique', 'Hybride']);
        }

        // Attribut pour Motos
        if (isset($subcategoryMap['Motos'])) {
            $this->createAttribute($manager, $subcategoryMap['Motos'], 'Type de moteur', 'choice',['2-temps', '4-temps', 'Électrique', 'Autre']);
            $this->createAttribute($manager, $subcategoryMap['Motos'], 'Cylindrée (cc)', 'choice', ['125cc', '250cc', '500cc', '1000cc', '1500cc', '2000cc ou plus']);
            $this->createAttribute($manager, $subcategoryMap['Motos'], 'Kilométrage (km)', 'choice',['Moins de 5 000 km', '5 000 - 10 000 km', '10 000 - 20 000 km', '20 000 - 50 000 km', '50 000 km - 100 000', '100 000 ou plus']);
            $this->createAttribute($manager, $subcategoryMap['Motos'], 'Année de fabrication', 'choice',generateYearChoices());
            $this->createAttribute($manager, $subcategoryMap['Motos'], 'Marque', 'choice',['Honda', 'Yamaha', 'Kawasaki', 'Suzuki', 'BMW', 'Ducati', 'KTM', 'Autre']);
            $this->createAttribute($manager, $subcategoryMap['Motos'], 'Couleur', 'choice',['Noir', 'Blanc', 'Rouge', 'Bleu', 'Gris', 'Vert', 'Autre']);
        }

        // Attributs pour Vêtements (Homme, Femme, Enfant)
        $clothingSubcategories = ['Homme', 'Femme', 'Enfant'];
        foreach ($clothingSubcategories as $subcategoryName) {
            if (isset($subcategoryMap[$subcategoryName])) {
                $this->createAttribute(
                    $manager,
                    $subcategoryMap[$subcategoryName],
                    'Marque',
                    'choice',
                    [
                        "Chanel", "Louis Vuitton", "Gucci", "Prada", "Dior", "Versace", "Burberry", "Hermès",
                        "Valentino", "Givenchy", "Alexander McQueen", "Balmain", "Elie Saab", "Jean Paul Gaultier",
                        "Supreme", "Off-White", "BAPE", "Stüssy", "Palace", "Nike", "Adidas", "Vans",
                        "Zara", "H&M", "Uniqlo", "Forever 21", "Primark", "Shein", "Mango", "Topshop",
                        "Puma", "Under Armour", "Reebok", "New Balance", "Asics",
                        "The North Face", "Patagonia", "Columbia", "Arc'teryx", "Salomon", "Jack Wolfskin", "Mammut", "Levi's", "Other"
                    ]
                );

                $this->createAttribute(
                    $manager,
                    $subcategoryMap[$subcategoryName],
                    'Taille',
                    'choice',
                    [
                        "XS", "S", "M", "L", "XL", "XXL", "XXXL"
                    ]
                );

                $this->createAttribute(
                    $manager,
                    $subcategoryMap[$subcategoryName],
                    'Couleur',
                    'choice',
                    [
                        "Noir", "Blanc", "Gris", "Rouge", "Bleu", "Vert", "Jaune", "Orange", "Rose", "Violet",
                        "Marron", "Beige", "Bordeaux", "Turquoise", "Or", "Argent"
                    ]
                );
            }
        }

        // Attributs pour Salon
        if (isset($subcategoryMap['Salon'])) {
            $this->createAttribute($manager, $subcategoryMap['Salon'], 'Type de Meuble', 'choice', [
                "Canapé", "Fauteuil", "Table basse", "Meuble TV", "Bibliothèque", "Étagère","Autre"
            ]);
            $this->createAttribute($manager, $subcategoryMap['Salon'], 'Matériau', 'choice', [
                "Bois", "Métal", "Verre", "Cuir", "Tissu", "Plastique","Autre"
            ]);
            $this->createAttribute($manager, $subcategoryMap['Salon'], 'Couleur', 'choice', [
                "Noir", "Blanc", "Gris", "Bleu", "Vert", "Rouge", "Beige", "Marron", "Autre"
            ]);
            $this->createAttribute($manager, $subcategoryMap['Salon'], 'Style', 'choice', [
                "Moderne", "Classique", "Contemporain", "Vintage", "Rustique"
            ]);
            $this->createAttribute($manager, $subcategoryMap['Salon'], 'Marque', 'choice', [
                "IKEA", "Maisons du Monde", "Conforama", "Leroy Merlin", "Autre"
            ]);
            $this->createAttribute($manager, $subcategoryMap['Salon'], 'Confort', 'choice', [
                "Très confortable", "Confortable", "Moyennement confortable", "Peu confortable"
            ]);
            $this->createAttribute($manager, $subcategoryMap['Salon'], 'Fonctionnalité', 'choice', [
                "Convertible", "Modulable", "Avec rangement", "Non convertible"
            ]);
        }        

        // Attributs pour Salle à Manger
        if (isset($subcategoryMap['Salle à Manger'])) {
            $this->createAttribute($manager, $subcategoryMap['Salle à Manger'], 'Type de Meuble', 'choice', [
                "Table à manger", "Chaise", "Buffet", "Vaisselier", "Console", "Crédence","Autre"
            ]);
            $this->createAttribute($manager, $subcategoryMap['Salle à Manger'], 'Matériau', 'choice', [
                "Bois", "Métal", "Verre", "Cuir", "Tissu", "Plastique","Autre"
            ]);
            $this->createAttribute($manager, $subcategoryMap['Salle à Manger'], 'Couleur', 'choice', [
                "Noir", "Blanc", "Gris", "Beige", "Bois naturel", "Rouge", "Bleu", "Autre"
            ]);
            $this->createAttribute($manager, $subcategoryMap['Salle à Manger'], 'Style', 'choice', [
                "Moderne", "Classique", "Contemporain", "Rustique", "Vintage"
            ]);
            $this->createAttribute($manager, $subcategoryMap['Salle à Manger'], 'Marque', 'choice', [
                "IKEA", "Maisons du Monde", "Conforama", "Leroy Merlin", "Autre"
            ]);
            $this->createAttribute($manager, $subcategoryMap['Salle à Manger'], 'Fonctionnalité', 'choice', [
                "Extensible", "Avec rangement", "Modulable", "Non extensible"
            ]);
            $this->createAttribute($manager, $subcategoryMap['Salle à Manger'], 'Confort', 'choice', [
                "Très confortable", "Confortable", "Moyennement confortable", "Peu confortable"
            ]);
            $this->createAttribute($manager, $subcategoryMap['Salle à Manger'], 'État', 'choice', [
                "Très bon état", "Bon état", "Satisfaisant"
            ]);
        }

        // Attributs pour Chambre
        if (isset($subcategoryMap['Chambre'])) {
            $this->createAttribute($manager, $subcategoryMap['Chambre'], 'Type de Meuble', 'choice', [
                "Lit", "Commode", "Armoire", "Table de chevet", "Bureau", "Chaise", "Meuble TV","Autre"
            ]);
            $this->createAttribute($manager, $subcategoryMap['Chambre'], 'Matériau', 'choice', [
                "Bois", "Métal", "Verre", "Tissu", "Cuir", "Plastique","Autre"
            ]);
            $this->createAttribute($manager, $subcategoryMap['Chambre'], 'Couleur', 'choice', [
                "Noir", "Blanc", "Gris", "Beige", "Bois naturel", "Bleu", "Vert", "Rouge", "Autre"
            ]);
            $this->createAttribute($manager, $subcategoryMap['Chambre'], 'Type de Matelas', 'choice', [
                "Mousse", "Ressorts", "Mémoire de forme", "Latex", "Autre"
            ]);
            $this->createAttribute($manager, $subcategoryMap['Chambre'], 'Nombre de Places', 'choice', [
                "Simple", "Double", "Queen", "King"
            ]);
            $this->createAttribute($manager, $subcategoryMap['Chambre'], 'Style', 'choice', [
                "Moderne", "Classique", "Contemporain", "Rustique", "Vintage"
            ]);
            $this->createAttribute($manager, $subcategoryMap['Chambre'], 'Marque', 'choice', [
                "IKEA", "Maisons du Monde", "Conforama", "Leroy Merlin", "Autre"
            ]);
            $this->createAttribute($manager, $subcategoryMap['Chambre'], 'Fonctionnalité', 'choice', [
                "Avec rangement", "Lit coffre", "Modulable", "Convertible", "Non convertible"
            ]);
            $this->createAttribute($manager, $subcategoryMap['Chambre'], 'État', 'choice', [
                "Très bon état", "Bon état", "Satisfaisant"
            ]);
        }

        // Attributs pour Bureau
        if (isset($subcategoryMap['Bureau'])) {
            $this->createAttribute($manager, $subcategoryMap['Bureau'], 'Type de Bureau', 'choice', [
                "Bureau d'angle", "Bureau droit", "Bureau modulable", "Bureau sur pied", "Bureau avec retour","Autre"
            ]);
            $this->createAttribute($manager, $subcategoryMap['Bureau'], 'Matériau', 'choice', [
                "Bois", "Métal", "Verre", "MDF", "Plastique", "Autre"
            ]);
            $this->createAttribute($manager, $subcategoryMap['Bureau'], 'Couleur', 'choice', [
                "Noir", "Blanc", "Gris", "Bois naturel", "Chêne", "Ébène", "Autre"
            ]);
            $this->createAttribute($manager, $subcategoryMap['Bureau'], 'Type de Rangement', 'choice', [
                "Avec étagères", "Avec tiroirs", "Avec placard", "Sans rangement"
            ]);
            $this->createAttribute($manager, $subcategoryMap['Bureau'], 'Style', 'choice', [
                "Moderne", "Classique", "Contemporain", "Industriel", "Minimaliste"
            ]);
            $this->createAttribute($manager, $subcategoryMap['Bureau'], 'Marque', 'choice', [
                "IKEA", "Maisons du Monde", "Conforama", "Leroy Merlin", "Autre"
            ]);
            $this->createAttribute($manager, $subcategoryMap['Bureau'], 'Fonctionnalité', 'choice', [
                "Réglable en hauteur", "Avec roulettes", "Avec support pour clavier", "Pliable", "Convertible"
            ]);
            $this->createAttribute($manager, $subcategoryMap['Bureau'], 'État', 'choice', [
                "Très bon état", "Bon état", "Satisfaisant"
            ]);
        }

        // Attributs pour Autre
        if (isset($subcategoryMap['Autre'])) {
            $this->createAttribute($manager, $subcategoryMap['Autre'], 'Type de Meuble', 'choice', [
                "Chaise", "Table", "Étagère", "Meuble de rangement", "Pouf", "Banc", "Autre"
            ]);
            $this->createAttribute($manager, $subcategoryMap['Autre'], 'Matériau', 'choice', [
                "Bois", "Métal", "Verre", "Tissu", "Cuir", "Plastique", "Autre"
            ]);
            $this->createAttribute($manager, $subcategoryMap['Autre'], 'Couleur', 'choice', [
                "Noir", "Blanc", "Gris", "Bois naturel", "Bleu", "Rouge", "Vert", "Autre"
            ]);
            $this->createAttribute($manager, $subcategoryMap['Autre'], 'Fonctionnalité', 'choice', [
                "Modulable", "Avec rangement", "Pliable", "Convertible", "Avec roulettes", "Non pliable"
            ]);
            $this->createAttribute($manager, $subcategoryMap['Autre'], 'Style', 'choice', [
                "Moderne", "Classique", "Contemporain", "Rustique", "Vintage", "Autre"
            ]);
            $this->createAttribute($manager, $subcategoryMap['Autre'], 'Marque', 'choice', [
                "IKEA", "Maisons du Monde", "Conforama", "Leroy Merlin", "Autre"
            ]);
            $this->createAttribute($manager, $subcategoryMap['Autre'], 'État', 'choice', [
                "Très bon état", "Bon état", "Satisfaisant"
            ]);
        }
        
        // Attributs pour Jeux et Jouets
        if (isset($subcategoryMap['Jeux & Jouets'])) {
            $this->createAttribute($manager, $subcategoryMap['Jeux & Jouets'], 'Type de Jeu', 'choice', [
                "Puzzle", "Jeu de société", "Jeu de construction", "Jouet éducatif"
            ]);

            $this->createAttribute($manager, $subcategoryMap['Jeux & Jouets'], 'Âge Recommandé', 'choice', [
                "0-3 ans", "3-6 ans", "6-12 ans", "Adulte"
            ]);

            $this->createAttribute($manager, $subcategoryMap['Jeux & Jouets'], 'Matériau', 'choice', [
                "Plastique", "Bois", "Carton"
            ]);

            $this->createAttribute($manager, $subcategoryMap['Jeux & Jouets'], 'Couleur', 'choice', [
                "Multicolore", "Rouge", "Bleu", "Vert"
            ]);

            $this->createAttribute($manager, $subcategoryMap['Jeux & Jouets'], 'Marque', 'choice', [
                "LEGO", "Hasbro", "Mattel"
            ]);

            $this->createAttribute($manager, $subcategoryMap['Jeux & Jouets'], 'État', 'choice', [
                "Très bon état", "Bon état", "Satisfaisant"
            ]);
        }

        // Attributs pour Instruments de Musique
        if (isset($subcategoryMap['Instruments de Musique'])) {
            $this->createAttribute($manager, $subcategoryMap['Instruments de Musique'], 'Type d\'Instrument', 'choice', [
                "Guitare", "Piano", "Batterie", "Violon", "Flûte"
            ]);

            $this->createAttribute($manager, $subcategoryMap['Instruments de Musique'], 'Matériau', 'choice', [
                "Bois", "Métal", "Synthétique"
            ]);

            $this->createAttribute($manager, $subcategoryMap['Instruments de Musique'], 'Couleur', 'choice', [
                "Noir", "Blanc", "Rouge", "Naturel"
            ]);

            $this->createAttribute($manager, $subcategoryMap['Instruments de Musique'], 'Marque', 'choice', [
                "Fender", "Yamaha", "Gibson"
            ]);

            $this->createAttribute($manager, $subcategoryMap['Instruments de Musique'], 'État', 'choice', [
                "Très bon état", "Bon état", "Satisfaisant"
            ]);

            $this->createAttribute($manager, $subcategoryMap['Instruments de Musique'], 'Accessoires Inclus', 'choice', [
                "Étui", "Métronome", "Accordeur", "Autre"
            ]);
        }

        // Attributs pour Camping et Randonnée
        if (isset($subcategoryMap['Camping & Randonnée'])) {
            $this->createAttribute($manager, $subcategoryMap['Camping & Randonnée'], 'Type d\'Équipement', 'choice', [
                "Tente", "Sac de couchage", "Sac à dos", "Lampe de poche"
            ]);

            $this->createAttribute($manager, $subcategoryMap['Camping & Randonnée'], 'Matériau', 'choice', [
                "Nylon", "Polyester", "Aluminium"
            ]);

            $this->createAttribute($manager, $subcategoryMap['Camping & Randonnée'], 'Couleur', 'choice', [
                "Vert", "Bleu", "Gris", "Rouge"
            ]);

            $this->createAttribute($manager, $subcategoryMap['Camping & Randonnée'], 'Marque', 'choice', [
                "The North Face", "Columbia", "Quechua"
            ]);

            $this->createAttribute($manager, $subcategoryMap['Camping & Randonnée'], 'État', 'choice', [
                "Très bon état", "Bon état", "Satisfaisant"
            ]);

            $this->createAttribute($manager, $subcategoryMap['Camping & Randonnée'], 'Caractéristiques', 'choice', [
                "Imperméable", "Résistant au vent", "Compact", "Léger"
            ]);
        }

        // Attributs pour Art et Artisanat
        if (isset($subcategoryMap['Art & Artisanat'])) {
            $this->createAttribute($manager, $subcategoryMap['Art & Artisanat'], 'Type de Produit', 'choice', [
                "Peinture", "Fournitures de dessin", "Kits de bricolage"
            ]);

            $this->createAttribute($manager, $subcategoryMap['Art & Artisanat'], 'Matériau', 'choice', [
                "Papier", "Toile", "Acrylique", "Huile"
            ]);

            $this->createAttribute($manager, $subcategoryMap['Art & Artisanat'], 'Couleur', 'choice', [
                "Multicolore", "Blanc", "Noir"
            ]);

            $this->createAttribute($manager, $subcategoryMap['Art & Artisanat'], 'Marque', 'choice', [
                "Winsor & Newton", "Faber-Castell"
            ]);

            $this->createAttribute($manager, $subcategoryMap['Art & Artisanat'], 'État', 'choice', [
                "Très bon état", "Bon état", "Satisfaisant"
            ]);
        }

        // Attributs pour Modélisme
        if (isset($subcategoryMap['Modélisme'])) {
            $this->createAttribute($manager, $subcategoryMap['Modélisme'], 'Type de Modèle', 'choice', [
                "Maquettes d'avion", "Voitures miniatures", "Trains"
            ]);

            $this->createAttribute($manager, $subcategoryMap['Modélisme'], 'Échelle', 'choice', [
                "1/10", "1/24", "1/48"
            ]);

            $this->createAttribute($manager, $subcategoryMap['Modélisme'], 'Matériau', 'choice', [
                "Plastique", "Métal", "Bois"
            ]);

            $this->createAttribute($manager, $subcategoryMap['Modélisme'], 'Couleur', 'choice', [
                "Blanc", "Noir", "Rouge", "Bleu"
            ]);

            $this->createAttribute($manager, $subcategoryMap['Modélisme'], 'Marque', 'choice', [
                "Revell", "Tamiya", "Airfix"
            ]);

            $this->createAttribute($manager, $subcategoryMap['Modélisme'], 'État', 'choice', [
                "Très bon état", "Bon état", "Satisfaisant"
            ]);
        }

        $manager->flush();
    }

    private function createAttribute(ObjectManager $manager, $subcategory, $name, $type, $choices = []): void
    {
        $attribute = new Attributes();
        $attribute->setName($name);
        $attribute->setSubcategory($subcategory);
        $attribute->setType($type);
        $manager->persist($attribute);

        if ($type === 'choice') {
            foreach ($choices as $choiceValue) {
                $choice = new Choice();
                $choice->setAttribute($attribute);
                $choice->setValue($choiceValue);
                $manager->persist($choice);
            }
        }
    }
}



