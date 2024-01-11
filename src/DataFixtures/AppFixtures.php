<?php

namespace App\DataFixtures;

use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // Création d'une vingtaine de livres ayant pour titre
        for ($i = 0; $i < 20; $i++) {
            $livre = new Product;
            $livre->setName('Product ' . $i);
            $manager->persist($livre);
        }

        $manager->flush();
    }
}
