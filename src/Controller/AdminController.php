<?php

namespace App\Controller;

use App\Entity\Article;
use App\Repository\ArticleRepository;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\BrowserKit\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    #[Route('/admin', name: 'app_admin')]
    public function index(): Response
    {
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }

    #[Route('/admin/articles', name: 'admin_articles')]
    public function adminArticles(ArticleRepository $repo, EntityManagerInterface $manager)
    {

        $colonnes =$manager->getClassMetadata(Article::class)->getFieldNames();

        // dd($colonnes);
        $articles = $repo->findAll();
        return $this->render('admin/admin_articles.html.twig', [
            'articles' => $articles,
            'colonnes' => $colonnes
        ]);
    }

    #[Route('/admin/article/new', name:'admin_new_article')]
    #[Route('/admin/article/edit/{id}', name:'admin_edit_article')]
    public function formArticle(Request $globals, EntityManagerInterface $manager, Article $article = null)
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
            $this->addFlash('success', "L'article a bien été enregistré !");
            //permet de creer un message qui sera affiché une fois a l'utilisateur
            return $this->redirectToRoute('admin_articles');
        }

        return $this->renderForm("admin/form_article.html.twig", [
            "formArticle" => $form,
            "editMode" => $article->getId() !== null
        ]);
    }




}
