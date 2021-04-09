<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\ArticleType;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Article;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use App\Service\FileUploader;

class AdminArticleController extends AbstractController
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
     * @Route("/administration", name="admin_article_home")
     */
    public function home(): Response
    {
        $articles = $this->repository->findAll();
        return $this->render('admin_article/home.html.twig', [
            "articles" => $articles
        ]);
    }
    
    /**
     * @Route("/administration/{id}", name="admin_article_update", requirements={"id": "[0-9]*"}, methods="GET|POST")
     */
    public function update(Article $article, Request $request, FileUploader $fileUploader) {
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()){
            /** @var UploadedFile **/
            $imageFile = $form->get('image')->getData();
            
            if($imageFile){
                $image = $fileUploader->upload($imageFile);
                $article->setImage($image);
            }
            
            $article->setUpdatedAt(new \DateTime());
            $this->entityManager->flush();
            $this->addFlash('success', "Modification réussie");
            return $this->redirectToRoute('admin_article_home');
        }
        
        return $this->render('admin_article/update.html.twig', [
            'form' => $form->createView()
        ]);
    }
    
    /**
     * @Route("/administration/nouveau", name="admin_article_create")
     */
    public function create(Request $request, FileUploader $fileUploader) {
        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()){
            /** @var UploadedFile **/
            $imageFile = $form->get('image')->getData();
            
            if($imageFile){
                $image = $fileUploader->upload($imageFile);
                $article->setImage($image);
            }
            
            $this->entityManager->persist($article);
            $this->entityManager->flush();
            $this->addFlash('success', "Le nouvel élément a été créé avec succès");
            return $this->redirectToRoute('admin_article_home');
        }
        
        return $this->render('admin_article/create.html.twig', [
            'form' => $form->createView()
        ]);
    }
    
    /**
     * @Route("/administration/{id}", name="admin_article_delete", requirements={"id": "[0-9]*"}, methods="DELETE")
     */
    public function delete(Article $article, Request $request) {
        if($this->isCsrfTokenValid("delete" . $article->getId(), $request->get("_token"))){
            $this->entityManager->remove($article);
            $this->entityManager->flush();
            $this->addFlash('success', "L'élément indiqué a bien été supprimé");
        }
        return $this->redirectToRoute("admin_article_home");
    }
}
