<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

final class LoginController extends AbstractController
{
    #[Route('/Login', name: 'app_login')]
    public function index(AuthenticationUtils $authenticationUtils): Response
    {
        // 🔍 Debug : Vérifier si le contrôleur est bien appelé
        dump($_SERVER); die();
        // Récupère l'erreur de connexion s'il y en a
        $error = $authenticationUtils->getLastAuthenticationError();

        // 🔍 Debug : Voir l'erreur d'authentification

        // Récupère le dernier nom d'utilisateur saisi
        $lastUsername = $authenticationUtils->getLastUsername();

        // 🔍 Debug : Voir l'utilisateur récupéré
        ($lastUsername);

        // Arrêter l'exécution ici pour voir les dumps

        return $this->render('login/index.html.twig', [

            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }
}
