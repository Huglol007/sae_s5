<?php

namespace App\Controller;

use App\Entity\Creneau;
use App\Form\CreneauType;
use App\Repository\CreneauRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/creneau')]
final class CreneauController extends AbstractController
{
    #[Route(name: 'app_creneau_index', methods: ['GET'])]
    public function index(CreneauRepository $creneauRepository): Response
    {
        return $this->render('creneau/index.html.twig', [
            'creneaus' => $creneauRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_creneau_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $creneau = new Creneau();
        $form = $this->createForm(CreneauType::class, $creneau);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($creneau);
            $entityManager->flush();

            return $this->redirectToRoute('app_creneau_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('creneau/new.html.twig', [
            'creneau' => $creneau,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_creneau_show', methods: ['GET'])]
    public function show(Creneau $creneau): Response
    {
        return $this->render('creneau/show.html.twig', [
            'creneau' => $creneau,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_creneau_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Creneau $creneau, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CreneauType::class, $creneau);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_creneau_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('creneau/edit.html.twig', [
            'creneau' => $creneau,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_creneau_delete', methods: ['POST'])]
    public function delete(Request $request, Creneau $creneau, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $creneau->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($creneau);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_creneau_index', [], Response::HTTP_SEE_OTHER);
    }

    // // // // //
    #[Route('/creneaux', name: 'app_creneaux', methods: ['GET'])]
    public function getCreneaux(CreneauRepository $creneauRepository): JsonResponse
    {
        $creneaux = $creneauRepository->findAll();

        $data = [];
        foreach ($creneaux as $creneau) {
            $data[] = [
                'id' => $creneau->getId(),
                'title' => $creneau->getMatiere()->getName(), // Nom de la matière
                'start' => $creneau->getDate()->format('Y-m-d') . 'T' . date('H:i:s', strtotime('+' . $creneau->getDuree() . ' minutes', strtotime($creneau->getDate()->format('H:i:s')))),
                'end' => $creneau->getDate()->format('Y-m-d') . 'T' . $creneau->getDate()->format('H:i:s'),
                'color' => '#007bff', // Couleur personnalisée pour différencier les cours
            ];
        }

        return $this->json($data);
    }








}
