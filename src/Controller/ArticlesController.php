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

    #[Route('/articles/{id}/modifier', name: 'app_article_modifier', requirements: ['id' => '\d+'])]
    public function modifier(
        int $id,
        ArticleRepository $repository,
        Request $request,
        EntityManagerInterface $entityManager
    ): Response {
        $article = $repository->find($id);

        if (!$article) {
            throw $this->createNotFoundException('Article not found');
        }

        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'Article modifié avec succès !');

            return $this->redirectToRoute('app_article_show', [
                'id' => $article->getId(),
            ]);
        }

        return $this->render('articles/modifier.html.twig', [
            'formulaire' => $form,
            'article' => $article,
        ]);
    }

    #[Route('/articles/{id}/supprimer', name: 'app_article_supprimer', requirements: ['id' => '\d+'], methods: ['POST'])]
    public function supprimer(
        int $id,
        ArticleRepository $repository,
        Request $request,
        EntityManagerInterface $entityManager
    ): Response {
        $article = $repository->find($id);

        if (!$article) {
            throw $this->createNotFoundException('Article not found');
        }

        if ($this->isCsrfTokenValid(
            'supprimer_' . $article->getId(),
            $request->request->get('_token')
        )) {
            $entityManager->remove($article);
            $entityManager->flush();

            $this->addFlash('success', 'Article supprimé avec succès !');
        } else {
            $this->addFlash('danger', 'Token CSRF invalide !');
        }

        return $this->redirectToRoute('app_articles');
    }
}
