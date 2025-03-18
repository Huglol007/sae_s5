<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Entity\User;


final class HomeController extends AbstractController

{



    #[Route('/test-login', name: 'app_test_login')]
    public function testLogin(EntityManagerInterface $em, UserPasswordHasherInterface $passwordHasher)
    {
        $user = $em->getRepository(User::class)->findOneBy(['email' => 'referent1@example.com']);

        if (!$user) {
            return new Response('Utilisateur non trouvé');
        }

        $isValid = $passwordHasher->isPasswordValid($user, 'password123');

        return new Response($isValid ? 'Connexion OK' : 'Mot de passe incorrect');
    }
    #[Route('/home', name: 'app_home')]
    public function index(): Response
    {
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }
    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        // Symfony gère automatiquement la déconnexion
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
