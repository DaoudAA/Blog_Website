<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Article;
use App\Repository\ArticleRepository;
use App\Form\ArticleType;
use App\Entity\Comment;
use App\Form\CommentType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType as SymfonyTextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType as SymfonyTextareaType;

class BlogController extends AbstractController
{
    #[Route('/blog', name: 'blog')]
    public function index(ArticleRepository $repository): Response
    {
        $articles = $repository->findAll();

        return $this->render('blog/index.html.twig', [
            'controller_name' => 'BlogController',
            'articles' => $articles,
        ]);
    }

    #[Route('/', name: 'home')]
    public function home(): Response
    {
        return $this->render('blog/home.html.twig', [
            'title' => 'Bienvenue',
            
        ]);
    }


    #[Route('/blog/new', name: 'blog_create')]
    #[Route('/blog/{id}/edit', name:'blog_edit')]
    public function create(Article $article =null, Request $request,EntityManagerInterface $manager):Response
    {   
        if(!$article){
            $article=new Article();

        }

    /* $form=$this->createFormBuilder($article)
                    ->add('title',SymfonyTextType::class , [
                        'attr' => [
                            'placeholder' => "Titre de l'article"
                        ]
                        ])
                    ->add('content',SymfonyTextareaType::class , [
                        'attr' => [
                            'placeholder' => "Contenu de l'article"
                        ]
                        ])
                    ->add('image',SymfonyTextType::class , [
                        'attr' => [
                            'placeholder' => "Image de l'article"
                        ]
                        ])
                    
                    ->getForm();
*/
        $form=$this->createForm(ArticleType::class,$article);
            $form->handleRequest($request);
            dump($article);
            if(($form->isSubmitted())&&($form->isValid())){
                if(!$article->getId()){
                    $article->setCreatedAt(new \DateTime());
                }

                $manager->persist($article);
                $manager->flush();
                return $this->redirectToRoute('blog_show',['id'=> $article->getId()]);
            }
        return $this->render('blog/create.html.twig',[
            'formArticle' => $form->createView(),
        'editMode'=> $article->getId() !== null 
        ]);
    }

    #[Route('/blog/{id}', name: 'blog_show')]
    public function show($id,ArticleRepository $repository,Request $request,EntityManagerInterface $manager): Response
    {
    
        $article = $repository->find($id);
        if (!$article) {
            throw $this->createNotFoundException('Article not found');
        }

        $comment=new Comment();
        $form=$this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);
if($form->isSubmitted () && $form->isValid()){
    $comment->setCreatedAt(new \DateTime())
            ->setArticle($article);
$manager->persist ($comment);
$manager->flush();
return $this->redirectToRoute('blog_show',['id'=>$article->getId()]);
}
        
        return $this->render('blog/show.html.twig', [
            'article' => $article,
            'commentForm' => $form->createView()
        ]);
    }

    
}