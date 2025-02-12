<?php

namespace App\Controller;

use App\Entity\Creneau;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/jour-non-ouvre')]
class JourNonOuvreController extends AbstractController
{
    #[Route(path: '/', name: 'app_jour_non_ouvre_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        // Récupérer uniquement les jours non ouvrés, vacances, SAE et fériés
        $jours = $entityManager->getRepository(Creneau::class)->findBy([
            'type' => ['jour-non-ouvre', 'vacances', 'sae', 'ferie'],
        ]);

        // Transmettre les jours à la vue
        return $this->render('jour_non_ouvre/index.html.twig', [
            'jours' => $jours,
        ]);
    }


    #[Route('/ajouter-jour', name: 'app_ajouter_jour', methods: ['POST'])]
    public function ajouterJour(Request $request, EntityManagerInterface $entityManager): Response
    {
        $startDate = $request->request->get('start_date');
        $endDate = $request->request->get('end_date');
        $type = $request->request->get('type');

        if (!$startDate || !$endDate || !$type) {
            $this->addFlash('error', 'Toutes les informations sont requises.');
            return $this->redirectToRoute('app_jour_non_ouvre_index');
        }

        try {
            $start = new \DateTime($startDate);
            $end = new \DateTime($endDate);

            // Vérification des dates
            if ($end < $start) {
                $this->addFlash('error', 'La date de fin ne peut pas être antérieure à la date de début.');
                return $this->redirectToRoute('app_jour_non_ouvre_index');
            }

            // Vérification des doublons
            $existingCreneau = $entityManager->getRepository(Creneau::class)->findOneBy([
                'start_date' => $start,
                'end_date' => $end,
                'type' => $type,
            ]);
            if ($existingCreneau) {
                $this->addFlash('error', 'Un créneau du même type existe déjà pour cette période.');
                return $this->redirectToRoute('app_jour_non_ouvre_index');
            }

            // Création du créneau
            $creneau = new Creneau();
            $creneau->setStartDate($start);
            $creneau->setEndDate($end);
            $creneau->setType($type);

            $entityManager->persist($creneau);
            $entityManager->flush();

            $this->addFlash('success', 'Le créneau a été ajouté avec succès !');
        } catch (\Exception $e) {
            $this->addFlash('error', 'Erreur : ' . $e->getMessage());
        }

        return $this->redirectToRoute('app_jour_non_ouvre_index');
    }




    #[Route('/supprimer-jour-non-ouvre', name: 'app_supprimer_jour', methods: ['POST'])]
    public function supprimerJour(Request $request, EntityManagerInterface $entityManager): Response
    {
        $startDateStr = $request->request->get('start_date');
        $endDateStr = $request->request->get('end_date');

        if (!$startDateStr || !$endDateStr) {

            return new JsonResponse(['error' => 'Les dates sont manquantes dans la requête.'], 400);
        }

        try {
            $startDate = new \DateTime($startDateStr);
            $endDate = new \DateTime($endDateStr);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => 'Format de date invalide.'], 400);
        }
        $endDate = (clone $endDate)->modify('-1 day'); // Enlever 1 jour pour correspondre à la base
        // Recherche du créneau correspondant aux dates données
        $jour = $entityManager->getRepository(Creneau::class)->findOneBy([
            'start_date' => $startDate,
            'end_date' => $endDate
        ]);

        if (!$jour) {
            return new JsonResponse(['error' => 'Créneau introuvable.'], 404);
        }

        // Suppression du créneau
        $entityManager->remove($jour);
        $entityManager->flush();

        return $this->redirectToRoute('app_jour_non_ouvre_index');
    }



}
