<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Article;
use App\Repository\ArticleRepository;

class ArticleController extends AbstractController
{
    /**
     * @var ArticleRepository
     */
    public $repository;
    
    public function __construct(ArticleRepository $repository) {
        $this->repository = $repository;
    }
    
    /**
     * @Route("/article/{slug}-{id}", name="article_read", requirements={"slug": "[0-9a-z\-]*"})
     */
    public function read(Article $article, $slug): Response
    {
        if($article->getSlug() !== $slug){
            return $this->redirectToRoute('article_read', [
                "id" => $article->getId(),
                "slug" => $article->getSlug()
            ], 301);
        }
        
        $suggestions = $this->repository->findLatest();
        return $this->render('article/read.html.twig', [
            'article' => $article,
            'suggestions' => $suggestions
        ]);
    }
}
