<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Article;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ArticleRepository;

class MainController extends AbstractController
{
    /**
     * @var ArticleRepository 
     */
    public $repository;

    /**
     * @var EntityManagerInterface
     */
    public $entityManager;

    public function __construct(ArticleRepository $repository, EntityManagerInterface $entityManager) {
        $this->repository = $repository;
        $this->entityManager = $entityManager;
    }
    
    /**
     * @Route("/", name="main_home")
     */
    public function home(): Response
    {
        return $this->render('main/home.html.twig');
        
        /*
        $article = new Article();
        $article->setTitle("Second article")
                ->setContent("Contenu du second article");

        $this->entityManager->persist($article);
        $this->entityManager->flush();
         * 
         */
     
    }
    
    /**
     * @Route("/formation", name="main_training")
     */
    public function training(ArticleRepository $articleRepository): Response
    {
        $articles = $this->repository->findBy([
            "category" => "Formation",
            "sold" => false
        ]);
        return $this->render('main/all.html.twig',[
            'articles' => $articles
        ]);
    }
    
    /**
     * @Route("/construction", name="main_building")
     */
    public function building(ArticleRepository $articleRepository): Response
    {
        $articles = $this->repository->findBy([
            "category" => "Construction",
            "sold" => false
        ]);
        return $this->render('main/all.html.twig',[
            'articles' => $articles
        ]);
    }
    
    
    /**
     * @Route("/etude", name="main_study")
     */
    public function study(ArticleRepository $articleRepository): Response
    {
        $articles = $this->repository->findBy([
            "category" => "Etude",
            "sold" => false
        ]);
        return $this->render('main/all.html.twig',[
            'articles' => $articles
        ]);
    }
    
    /**
     * @Route("/immobilier", name="main_immovable")
     */
    public function immovable(ArticleRepository $articleRepository): Response
    {
        $articles = $this->repository->findBy([
            "category" => "Immobilier",
            "sold" => false
        ]);
        return $this->render('main/all.html.twig',[
            'articles' => $articles
        ]);
    }
    
    
    /**
     * @Route("/contact", name="main_contact")
     */
    public function contact(): Response
    {
        return $this->render('main/empty.html.twig');        
    }
     
    
    /**
     * @Route("/a-propos", name="main_about")
     */
    public function about(): Response
    {
        return $this->render('main/empty.html.twig');        
    }
    
    
    /**
     * @Route("/articles", name="main_all")
     */
    public function all(ArticleRepository $articleRepository): Response
    {
        $articles = $this->repository->findLatest();
        return $this->render('main/all.html.twig',[
            'articles' => $articles
        ]);
    }
    
    
}
