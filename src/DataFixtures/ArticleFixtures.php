<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Article;

class ArticleFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {

       
        for ($count = 0; $count < 10; $count++) {
            $article = new Article();
            $article->setTitre("Titre " . $count);
            $article->setTexte("contenu de l'article " . $count);
            $article->setDateCreation(new \DateTime());
            $article->setPhoto("http://placehold.it/350*150");
            $manager->persist($article);
        }

        $manager->flush();
    }
}
