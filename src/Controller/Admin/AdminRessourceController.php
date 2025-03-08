<?php

namespace App\Controller\Admin;

use App\Entity\Ressource;
use App\Repository\RessourceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\MatiereRepository;


#[Route('/admin/ressources')]
class AdminRessourceController extends AbstractController
{
    #[Route('/', name: 'admin_ressource_index')]
    public function index(RessourceRepository $ressourceRepository, MatiereRepository $matiereRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN'); // 🔒 Protection pour admin

        return $this->render('admin/ressource/index.html.twig', [
            'ressources' => $ressourceRepository->findAll(),
            'matieres' => $matiereRepository->findAll() // 🔥 Ajout des matières ici

        ]);
    }

    #[Route('/add', name: 'admin_ressource_add', methods: ['POST'])]
    public function add(Request $request, EntityManagerInterface $entityManager, MatiereRepository $matiereRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $name = $request->request->get('name');
        $type = $request->request->get('type');
        $state = $request->request->get('state');
        $semestre = $request->request->get('semestre'); // 🔥 Récupère le semestre
        $matiereIds = $request->request->all('matieres'); // Liste des matières sélectionnées

        if ($name && $type && $semestre) {
            $ressource = new Ressource();
            $ressource->setName($name);
            $ressource->setType($type);
            $ressource->setState($state);
            $ressource->setSemestre($semestre); // 🔥 Stocke le semestre

            foreach ($matiereIds as $matiereId) {
                $matiere = $matiereRepository->find($matiereId);
                if ($matiere) {
                    $ressource->addMatiere($matiere);
                }
            }

            $entityManager->persist($ressource);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin_ressource_index');
    }




    #[Route('/delete/{id}', name: 'admin_ressource_delete')]
    public function delete(Ressource $ressource, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN'); // 🔒 Protection pour admin

        $entityManager->remove($ressource);
        $entityManager->flush();

        return $this->redirectToRoute('admin_ressource_index');
    }
}
