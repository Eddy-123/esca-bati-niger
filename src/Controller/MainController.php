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
