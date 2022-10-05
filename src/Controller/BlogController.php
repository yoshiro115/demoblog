<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Comment;
use App\Entity\User;
use App\Form\ArticleType;
use App\Form\CommentType;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BlogController extends AbstractController
{
    #[Route('/', name: "home")]
    public function home() 
    {
       return $this->render('blog/home.html.twig', [
            'slogan' => "La démo d'un blog",
            "age" => 27
       ]);
       // pour renvoyer des variables depuis le controller , la méthode render()
       // prend en 2ème argument un tableau associatif
    
    }



    #[Route('/blog', name: 'app_blog')]
    public function index(ArticleRepository $repo): Response
    {
        $articles=$repo->findAll();
        return $this->render('blog/index.html.twig', [
            'articles' => $articles,
        ]);
    }
    #[Route("/blog/show/{id}", name:"blog_show")]
    public function show($id, ArticleRepository $repo, Request $globals, EntityManagerInterface $manager, User $user=null){
        $article = $repo->find($id);

        if ($this->getUser()) {
            $user = $this->getUser();
        }    

        $comment = new Comment;
        
        $form = $this->createForm(CommentType::class, $comment );
        $form->handleRequest($globals);

        if($form->isSubmitted() && $form->isValid())
        {
            $comment->setCreatedAt(new \DateTime);
            $comment->setArticle($article);
            $comment->setUser($user);
            $manager->persist($comment);
            $manager->flush();
            return $this->redirectToRoute('blog_show', [
                'id' => $article->getId()
            ]);
        }






        return $this->renderForm('blog/show.html.twig', [
            'article' => $article,
            'form' => $form
        ]);
    }
    #[Route("/blog/new", name:"blog_create")]
    #[Route("/blog/edit/{id}", name:"blog_edit")]
    public function form(Request $globals, EntityManagerInterface $manager, Article $article= null)
    {
        
        if($article ==null):
            $article = new Article;
        endif;


        $form= $this->createForm(ArticleType::class, $article );

        $form->handleRequest($globals);



        //dump($article);

        if($form->isSubmitted() && $form->isValid())
        {
            $article->setCreatedAt(new \DateTime);
            $manager->persist($article);
            $manager->flush();
            return $this->redirectToRoute('blog_show', [
                'id' => $article->getId()
            ]);
        }

        return $this->renderForm("blog/form.html.twig", [
            "formArticle" => $form,
            "editMode" => $article->getId() !== null
        ]);

    }
    #[Route("/blog/delete/{id}", name:"blog_delete")]
    public function delete($id, EntityManagerInterface $manager, ArticleRepository $repo)
    {
        $article= $repo->find($id);

        $manager->remove($article);
        $manager->flush();
        return $this->redirectToRoute('app_blog');
    }
    
}
