<?php

namespace App\Controller;

use App\Entity\Tache;
use App\Repository\TacheRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class TacheController extends AbstractController
{
    #[Route('/taches', name: 'app_taches')]
    public function index(TacheRepository $repository): Response
    {
        $taches = $repository->findBy([], ['terminee' => 'ASC']);

        return $this->render('taches/index.html.twig', [
            'taches' => $taches,
        ]);
    }

    #[Route('/taches/ajouter', name: 'app_tache_new')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        if ($request->isMethod('POST')) {
            $tache = new Tache();
            $tache->setTitre($request->request->get('titre', ''));
            $tache->setDescription($request->request->get('description') ?: null);
            $tache->setDateCreation(new \DateTime());

            $entityManager->persist($tache);
            $entityManager->flush();

            return $this->redirectToRoute('app_taches');
        }

        return $this->render('taches/new.html.twig');
    }

    #[Route('/taches/{id}', name: 'app_tache_show', requirements: ['id' => '\d+'])]
    public function show(int $id, TacheRepository $repository): Response
    {
        $tache = $repository->find($id);

        if (!$tache) {
            throw $this->createNotFoundException('Task not found');
        }

        return $this->render('taches/show.html.twig', [
            'tache' => $tache,
        ]);
    }

    #[Route('/taches/{id}/terminer', name: 'app_tache_terminer', requirements: ['id' => '\d+'])]
    public function terminer(int $id, TacheRepository $repository, EntityManagerInterface $entityManager): Response
    {
        $tache = $repository->find($id);

        if (!$tache) {
            throw $this->createNotFoundException('Task not found');
        }

        $tache->setTerminee(true);
        $entityManager->flush();

        return $this->redirectToRoute('app_taches');
    }
}
