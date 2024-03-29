<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Article;
use App\Entity\Category;
use App\Entity\Comment;

class ArticleFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);
        $faker=\Faker\Factory::create('fr_FR');
        for($i=1;$i<=3;$i++){
          $category=new Category();
          $category->setTitle($faker->sentence());
          $manager->persist($category);

          for($j=1;$j<=mt_rand(4,6);$j++){
            $article=new Article();
            $cont='<p>' . join('</p><p>',$faker->paragraphs(5)) .'</p>';

            $article->setTitle($faker->sentence())
                    ->setContent($cont)
                    ->setImage($faker->imageUrl())
                    ->setCreatedAt($faker->dateTimeBetween('-6 months'))
                    ->setCategory($category);
                    $manager->persist($article);


                    for($l=1;$l<=mt_rand(4,6);$l++){
                      $comment= new Comment();

                      $content='<p>' . join('</p><p>',$faker->paragraphs(2)) .'</p>';
                      $days=(new \DateTime())->diff($article->getCreatedAt())->days;
                      $comment->setAuthor($faker->name)
                              ->setContent($content)
                              ->setCreatedAt($faker->dateTimeBetween('-' . $days . 'days'))
                              ->setArticle($article);
                              $manager->persist($comment);
                            }
                          }
                        }
                        $manager->flush();
    }
}