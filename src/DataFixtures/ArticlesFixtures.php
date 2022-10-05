<?php

namespace App\DataFixtures;

use app\Entity\Article;
use App\Entity\Comment;
use App\Entity\Category;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class ArticlesFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);
        // for($i = 1; $i <= 10; $i++)
        // {
        //     $article = new Article;
        //     $article->setTitle("Titre de l'article n°$i")
        //             ->setContent("<p>Contenu de l'article n°$i</p>")
        //             ->setImage("http://picsum.photos/250/150")
        //             ->setCreatedAt(new \DateTime);
        //     $manager->persist($article);
        // }
        $faker = \Faker\Factory::create('fr_FR');

            for($i = 1; $i <=3; $i++)
            {
                $category = new Category;
                $category->setTitle($faker->sentence(3, false));

                $manager->persist($category);

                for($j =1; $j <= mt_rand(4, 6); $j++)
                {
                    $article = new Article;
                    $content = '<p>' . join('<p></p>', $faker->paragraphs(5)) .'</p>';
                    $article->setTitle($faker->sentence())
                            ->setContent($content)
                            ->setImage($faker->imageUrl())
                            ->setCreatedAt($faker->dateTimeBetween("-6 months"))
                            ->setCategory($category);
                    $manager->persist($article);
                    // for($k =1; $k <= mt_rand(5, 10); $k++)
                    // {
                    //     $comment =new Comment;
                    //     $content = "<p>" . join('<p></p>', $faker->paragraphs(2)) .'</p>';

                    //     $now = new \DateTime;
                    //     $interval = $now->diff($article->getCreatedAt());
                    //     $days = $interval->days;

                    //     $comment
                    //             ->setContent($content)
                    //             ->setCreatedAt($faker->dateTimeBetween('-' . $days . ' days'))
                    //             ->setArticle($article);
                    //     $manager->persist($comment);
                    // }
                }

            }





        
        $manager->flush();
    }
}
