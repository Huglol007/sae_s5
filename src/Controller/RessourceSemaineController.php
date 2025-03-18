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
use Symfony\Component\Validator\Constraints\DateTime;
use Carbon\Carbon;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;


#[Route('/ressource/semaine')]
#[IsGranted('ROLE_PROF_REFERENT')]
final class RessourceSemaineController extends AbstractController
{
    /**
     * 📌 Affiche la liste des ressources
     */
    #[Route(name: 'app_ressource_semaine_index', methods: ['GET'])]
    public function index(RessourceRepository $ressourceRepository): Response
    {
        return $this->render('ressource_semaine/index.html.twig', [
            'ressources' => $ressourceRepository->findAll(), // Récupère toutes les ressources
        ]);
    }

    /**
     * 📌 Sélectionne une ressource et affiche le formulaire des semaines
     */
    #[Route('/new', name: 'app_ressource_semaine_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, RessourceRepository $ressourceRepository, RessourceSemaineRepository $ressourceSemaineRepository): Response
    {
        // Vérifie si une ressource est passée en paramètre
        $ressourceId = $request->query->get('ressource');
        if (!$ressourceId) {
            return $this->redirectToRoute('app_ressource_semaine_index');
        }

        // Récupère la ressource
        $ressource = $ressourceRepository->find($ressourceId);
        if (!$ressource) {
            throw $this->createNotFoundException('Ressource non trouvée.');
        }
        $existingSemaines = $ressourceSemaineRepository->findBy(['ressource' => $ressource]);

        if (!empty($existingSemaines)) {
            // Si des semaines existent déjà, on redirige vers edit !
            return $this->redirectToRoute('app_ressource_semaine_edit', ['id' => $ressource->getId()]);
        }

        // 📅 Détermine les semaines du mois en cours
        $now = Carbon::now(); // Date actuelle
        $monthStart = $now->copy()->startOfMonth(); // Premier jour du mois
        $monthEnd = $now->copy()->endOfMonth(); // Dernier jour du mois
        $currentMonday = $monthStart->copy()->startOfWeek(Carbon::MONDAY);


        $ressourceSemaines = [];
        while ($currentMonday->lte($monthEnd)) {
            $ressourceSemaine = new RessourceSemaine();
            $semaine = $currentMonday->format('Y-m-W'); // Format YYYY-MM-WW
            $ressourceSemaine->setSemaine($semaine);
            $ressourceSemaine->setRessource($ressource);
            $ressourceSemaines[] = $ressourceSemaine;

            $currentMonday->addWeek(); // Passe au lundi suivant
        }


        // 🔄 Formulaire avec la collection de semaines
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
    #[Route('/edit/{id}', name: 'app_ressource_semaine_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, EntityManagerInterface $em, $id, $month = null): Response
    {
        // Récupérer la ressource concernée
        $ressource = $em->getRepository(Ressource::class)->find($id);

        if (!$ressource) {
            throw $this->createNotFoundException('Ressource non trouvée');
        }

        // Déterminer le mois en cours ou sélectionné
        $currentDate = new \DateTime();
        if ($month && is_numeric($month) && $month >= 1 && $month <= 12) {
            $currentDate->setDate((int) $currentDate->format('Y'), (int) $month, 1);
        } else {
            $month = (int) $currentDate->format('m');
        }




        // Trouver tous les lundis du mois sélectionné
        $monthName = $currentDate->format('F Y');
        $firstDayOfMonth = new \DateTime($currentDate->format('Y-m-01'));
        $lastDayOfMonth = new \DateTime($currentDate->format('Y-m-t'));
        $interval = new \DateInterval('P1D');
        $period = new \DatePeriod($firstDayOfMonth, $interval, $lastDayOfMonth->modify('+1 day'));

        $lundis = [];
        foreach ($period as $day) {
            if ($day->format('N') == 1) { // 1 = Lundi
                $lundis[] = $day->format('Y-m-d');
            }
        }

        // Récupérer les semaines existantes pour la ressource
        $ressourceSemaines = $em->getRepository(RessourceSemaine::class)->findBy(['ressource' => $ressource]);

        // Associer les lundis aux semaines existantes
        $semainesData = [];
        foreach ($lundis as $lundi) {
            $semaine = array_filter($ressourceSemaines, function ($s) use ($lundi) {
                $date = $s->getSemaine();
                if ($date instanceof \DateTime) {
                    $formattedDate = $date->format('Y-m-d');
                } elseif (is_string($date) && !empty($date)) {
                    try {
                        $formattedDate = (new \DateTime($date))->format('Y-m-d');
                    } catch (\Exception $e) {
                        $formattedDate = null; // Gestion des erreurs si la date est invalide
                    }
                } else {
                    $formattedDate = null;
                }

                return $formattedDate === $lundi;
            });
            $semainesData[] = $semaine ? array_values($semaine)[0] : new RessourceSemaine();
        }

        // Formulaire de modification
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

        if ($form->isSubmitted() && $form->isValid()) {
            foreach ($semainesData as $semaine) {
                if ($semaine->getCm() || $semaine->getTd() || $semaine->getTp() || $semaine->getDs() || $semaine->getSae()) {
                    $em->persist($semaine);
                } else {
                    // Supprime les entrées si elles sont toutes à 0
                    $em->remove($semaine);
                }
            }
            $em->flush();
            return $this->redirectToRoute('app_ressource_semaine_edit', ['id' => $id, 'month' => $currentDate->format('m')]);
        }

        return $this->render('ressource_semaine/edit.html.twig', [
            'ressource' => $ressource,
            'form' => $form->createView(),
            'month' => $month,
            'year' => $currentDate->format('Y'),
            'monthName' => $monthName

        ]);
    }


    /**
     * 📌 Supprime une semaine d'une ressource
     */
    #[Route('/{id}', name: 'app_ressource_semaine_delete', methods: ['POST'])]
    public function delete(Request $request, RessourceSemaine $ressourceSemaine, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$ressourceSemaine->getId(), $request->request->get('_token'))) {
            $entityManager->remove($ressourceSemaine);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_ressource_semaine_index', [], Response::HTTP_SEE_OTHER);
    }
}
