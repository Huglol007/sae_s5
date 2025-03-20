<?php

namespace App\Controller;

use App\Entity\RessourceSemaine;
use App\Entity\Ressource;
use App\Form\RessourceSemaineType;
use App\Form\SemaineType;
use App\Repository\RessourceSemaineRepository;
use App\Repository\RessourceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Carbon\Carbon;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

#[Route('/ressource/semaine')]
final class RessourceSemaineController extends AbstractController
{
    /**
     * 📌 Affiche la liste des ressources
     */
    #[Route(name: 'app_ressource_semaine_index', methods: ['GET'])]
    public function index(RessourceRepository $ressourceRepository): Response
    {
        return $this->render('ressource_semaine/index.html.twig', [
            'ressources' => $ressourceRepository->findAll(),
        ]);
    }

    /**
     * 📌 Sélectionne une ressource et affiche le formulaire des semaines
     */
    #[Route('/new', name: 'app_ressource_semaine_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, RessourceRepository $ressourceRepository, RessourceSemaineRepository $ressourceSemaineRepository): Response
    {
        $ressourceId = $request->query->get('ressource');
        if (!$ressourceId) {
            return $this->redirectToRoute('app_ressource_semaine_index');
        }

        $ressource = $ressourceRepository->find($ressourceId);
        if (!$ressource) {
            throw $this->createNotFoundException('Ressource non trouvée.');
        }

        $existingSemaines = $ressourceSemaineRepository->findBy(['ressource' => $ressource]);
        if (!empty($existingSemaines)) {
            return $this->redirectToRoute('app_ressource_semaine_edit', ['id' => $ressource->getId()]);
        }

        // 📅 Trouver tous les lundis du mois en cours
        $currentDate = new \DateTime();
        $firstMonday = (clone $currentDate)->modify('first monday of this month');
        $lundis = [];

        while ($firstMonday->format('m') == $currentDate->format('m')) {
            $lundis[] = $firstMonday->format('Y-m-d');
            $firstMonday->modify('+7 days');
        }

        $ressourceSemaines = [];
        foreach ($lundis as $lundi) {
            $ressourceSemaine = new RessourceSemaine();
            $ressourceSemaine->setSemaine($lundi);
            $ressourceSemaine->setRessource($ressource);
            $ressourceSemaines[] = $ressourceSemaine;
        }

        $form = $this->createForm(SemaineType::class, ['ressource_semaines' => $ressourceSemaines]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            foreach ($ressourceSemaines as $semaine) {
                $entityManager->persist($semaine);
            }
            $entityManager->flush();

            return $this->redirectToRoute('app_ressource_semaine_index');
        }

        return $this->render('ressource_semaine/edit.html.twig', [
            'form' => $form->createView(),
            'ressource' => $ressource,
        ]);
    }

    /**
     * 📌 Affiche les détails d'une ressource/semaine
     */
    #[Route('/{id}', name: 'app_ressource_semaine_show', methods: ['GET'])]
    public function show(RessourceSemaine $ressourceSemaine): Response
    {
        return $this->render('ressource_semaine/show.html.twig', [
            'ressource_semaine' => $ressourceSemaine,
        ]);
    }

    /**
     * 📌 Modifie une semaine d'une ressource
     */

     #[Route('/edit/{id}', name: 'app_ressource_semaine_edit')]
     public function edit(
         Request $request,
         EntityManagerInterface $em,
         RessourceRepository $ressourceRepository,
         RessourceSemaineRepository $ressourceSemaineRepository,
         int $id
     ): Response {
         // 🔍 Récupérer la ressource
         $ressource = $ressourceRepository->find($id);
         if (!$ressource) {
             throw $this->createNotFoundException('Ressource non trouvée');
         }
     
         // 📅 Définir le mois actuel ou sélectionné
         $month = $request->query->get('month', date('m')); // Récupère dans l'URL ou met le mois courant
         $year = date('Y'); // Année courante
         $currentDate = new \DateTime("$year-$month-01");
         $monthName = $currentDate->format('F Y');
     
         // 📌 Trouver tous les lundis du mois
         $firstDayOfMonth = new \DateTime($currentDate->format('Y-m-01'));
         $lastDayOfMonth = new \DateTime($currentDate->format('Y-m-t'));
         $period = new \DatePeriod($firstDayOfMonth, new \DateInterval('P1D'), $lastDayOfMonth->modify('+1 day'));
     
         $lundis = [];
         foreach ($period as $day) {
             if ($day->format('N') == 1) { // 1 = Lundi
                 $lundis[] = $day->format('Y-m-d');
             }
         }
     
         // 📌 Récupérer les semaines déjà enregistrées
         $ressourceSemaines = $ressourceSemaineRepository->findBy(['ressource' => $ressource, 'mois' => $month]);
     
         // 📌 Associer chaque lundi à une ressource semaine
         $semainesData = [];
         foreach ($lundis as $lundi) {
             $semaine = array_filter($ressourceSemaines, function ($s) use ($lundi) {
                 return $s->getSemaine() === $lundi;
             });
     
             if ($semaine) {
                 $semainesData[] = array_values($semaine)[0];
             } else {
                 // Nouvelle semaine
                 $newSemaine = new RessourceSemaine();
                 $newSemaine->setSemaine($lundi);
                 $newSemaine->setMois($month);
                 $newSemaine->setRessource($ressource);
                 $semainesData[] = $newSemaine;
             }
         }
     
         // 🔄 Formulaire
         $form = $this->createFormBuilder()
             ->add('ressource_semaines', CollectionType::class, [
                 'entry_type' => RessourceSemaineType::class,
                 'entry_options' => ['label' => false],
                 'allow_add' => true,
                 'allow_delete' => true,
                 'by_reference' => false,
             ])
             ->setData(['ressource_semaines' => $semainesData])
             ->getForm();
     
         $form->handleRequest($request);
     
         // 📌 Traitement du formulaire
         if ($form->isSubmitted() && $form->isValid()) {
             foreach ($semainesData as $semaine) {
                 $em->persist($semaine);
             }
             $em->flush();
             return $this->redirectToRoute('app_ressource_semaine_edit', ['id' => $id, 'month' => $month]);
         }
        
         return $this->render('ressource_semaine/edit.html.twig', [
             'ressource' => $ressource,
             'ressourceSemaines' => $ressourceSemaines,
             'form' => $form->createView(),
             'month' => $month,
             'year' => $year,
             'monthName' => $monthName,
         ]);
     }
     

    /**
     * 📌 Supprime une semaine d'une ressource
     */
    #[Route('/{id}', name: 'app_ressource_semaine_delete', methods: ['POST'])]
    public function delete(Request $request, RessourceSemaine $ressourceSemaine, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $ressourceSemaine->getId(), $request->request->get('_token'))) {
            $entityManager->remove($ressourceSemaine);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_ressource_semaine_index');
    }
}
