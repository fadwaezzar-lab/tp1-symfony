<?php

namespace App\Controller;

use App\Entity\Article;
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
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        if ($request->isMethod('POST')) {
            $article = new Article();

            $article->setTitre($request->request->get('titre'));
            $article->setContenu($request->request->get('contenu'));
            $article->setAuteur($request->request->get('auteur'));
            $article->setDateCreation(new \DateTime());
            $article->setPublie($request->request->get('publie') === '1');

            $entityManager->persist($article);
            $entityManager->flush();

            return $this->redirectToRoute('app_articles');
        }

        return $this->render('articles/new.html.twig');
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
