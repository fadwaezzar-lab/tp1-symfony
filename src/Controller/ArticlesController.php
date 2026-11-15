<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

final class ArticlesController extends AbstractController
{
    #[Route('/articles', name: 'app_articles')]
    public function index(
        ArticleRepository $repository,
        RequestStack $requestStack
    ): Response {
        $session = $requestStack->getSession();

        $nbVisites = $session->get('nb_visites', 0);
        $session->set('nb_visites', $nbVisites + 1);

        $articles = $repository->findAll();
$derniersArticles = $repository->findLastPublished(3);
        return $this->render('articles/index.html.twig', [
            'articles' => $articles,
            'nbVisites' => $nbVisites + 1,'derniersArticles' => $derniersArticles,
        ]);
    }

    #[Route('/articles/nouveau', name: 'app_article_new')]
    #[IsGranted('ROLE_USER')]
    public function new(
        Request $request,
        EntityManagerInterface $entityManager
    ): Response {
        $article = new Article();
        $article->setAuteurUser($this->getUser());
        $article->setAuteur($this->getUser()->getUserIdentifier());

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

    #[Route('/articles/{id}', name: 'app_article_show', methods: ['GET'])]
    public function show(Article $article): Response
    {
        return $this->render('articles/show.html.twig', [
            'article' => $article,
        ]);
    }

    #[Route('/articles/{id}/modifier', name: 'app_article_modifier', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_USER')]
    public function modifier(
        Article $article,
        Request $request,
        EntityManagerInterface $entityManager
    ): Response {
        if ($article->getAuteurUser() !== $this->getUser()) {
            throw $this->createAccessDeniedException();
        }

        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'Article modifié avec succès !');

            return $this->redirectToRoute('app_articles');
        }

        return $this->render('articles/edit.html.twig', [
            'formulaire' => $form,
            'article' => $article,
        ]);
    }

    #[Route('/articles/{id}/supprimer', name: 'app_article_supprimer', methods: ['POST'])]
    #[IsGranted('ROLE_USER')]
    public function supprimer(
        Article $article,
        Request $request,
        EntityManagerInterface $entityManager
    ): Response {
        if ($article->getAuteurUser() !== $this->getUser()) {
            throw $this->createAccessDeniedException();
        }

        if ($this->isCsrfTokenValid('delete'.$article->getId(), $request->request->get('_token'))) {
            $entityManager->remove($article);
            $entityManager->flush();

            $this->addFlash('success', 'Article supprimé avec succès !');
        }

        return $this->redirectToRoute('app_articles');
    }
}
