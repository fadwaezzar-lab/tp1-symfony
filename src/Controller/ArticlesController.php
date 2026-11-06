<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ArticlesController extends AbstractController
{
    #[Route('/articles', name: 'app_articles')]
    public function index(ArticleRepository $repository): Response
    {
        $articles = $repository->findAll();

        return $this->render('articles/index.html.twig', [
            'articles' => $articles,
        ]);
    }

    #[Route('/articles/nouveau', name: 'app_article_new')]
    public function new(
        Request $request,
        EntityManagerInterface $entityManager
    ): Response {
        $article = new Article();

        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($article);
            $entityManager->flush();

            $this->addFlash('success', 'Article créé avec succès !');

            return $this->redirectToRoute('app_articles');
        }

        return $this->render('articles/new.html.twig', [
            'formulaire' => $form,
        ]);
    }

    #[Route('/articles/{id}', name: 'app_article_show', requirements: ['id' => '\d+'])]
    public function show(int $id, ArticleRepository $repository): Response
    {
        $article = $repository->find($id);

        if (!$article) {
            throw $this->createNotFoundException('Article not found');
        }

        return $this->render('articles/show.html.twig', [
            'article' => $article,
        ]);
    }
}
